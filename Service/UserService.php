<?php

namespace Service;

use Constants\RedisKeyConstant;
use Dto\BillDto;
use Dto\QuestionDto;
use Dto\Request;
use Dto\TokenDto;
use Dto\UserDto;
use Model\CodeModel;
use Model\TokenModel;
use Model\UserModel;
use Throwable;
use Utils\ApiException;
use Utils\RedisClient;

class UserService extends BaseService {
    const UPDATE_ACTIVE_TIME = 5 * 60; //五分钟记录一次
    private $userMd;

    public function __construct() {
        $this->userMd = new UserModel();
    }

    const leastTokens = 50;

    public static function isNewUser(int $userid): bool {
        $userMd = new UserModel();
        $user   = $userMd->findByUserId($userid);
        return $user['last_login_time'] == null;
    }

    private static function overMinutes(string $oldDatetime): bool {
        return time() - strtotime($oldDatetime) > self::UPDATE_ACTIVE_TIME;
    }

    public function hasBalance(int $userid): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            $userMd = new UserModel();
            $user   = $userMd->findByUserId($userid);
            if ($user['open_id'] == 'oh0-H6NPz6h9OBeUo8J0NK8iNT0E') {
                return [true, 'success', ['balance_token' => 0]];
            }
            if (empty($user['balance_token'])) {
                throw new ApiException('您的token数量已经为0,您可邀请好友注册或者充值获得token');
            }
            if ($user['balance_token'] < self::leastTokens) {
                throw new ApiException(sprintf('您的token数量小于可用阈值: %s,您可邀请好友注册或者充值获得token', self::leastTokens));
            }

            $data['balance_token'] = $user['balance_token'];
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
        }
        log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        return [$ok, $msg, $data];
    }

    public function reduceBalanceToken($userid, $spentTokens, $remark): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            $this->userMd->startTrans();
            $user            = $this->userMd->findByUserId($userid);
            $newBalanceToken = $user['balance_token'] - $spentTokens;

            //扣费
            $reduceRes = $this->userMd->updateById($userid, ['balance_token' => $newBalanceToken]);
            if (empty($reduceRes)) {
                throw new ApiException('扣费失败');
            }

            //记录流水
            $billDto = new BillDto();
            $billDto->setToken($spentTokens);
            $billDto->setUserId($userid);
            $billDto->setType(0);
            $billDto->setRemark($remark);
            $billDto->setBalance($newBalanceToken);
            $billService = new BillService();
            list($billOk, $billMsg, $billRes) = $billService->recordBilling($billDto);
            if (empty($billOk)) {
                throw new ApiException($billMsg);
            }
            $this->userMd->commitTrans();
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
        }
        log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        !$ok && $this->userMd->rollbackTrans();
        return [$ok, $msg, $data];
    }

    /**
     * 充值
     * @param int $userid
     * @param int $money
     * @return array
     */
    public function recharge(int $opUserid, int $userid, int $money): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            if (!isMyself($opUserid)) {
                throw new ApiException('您没有权限操作充值');
            }

            $tokenService = new TokenService();
            list($getTokenOk, $getTokenMsg, $getTokenData) = $tokenService->rechargeTokenByMoney($money);
            if (empty($getTokenOk)) {
                throw new ApiException($getTokenMsg);
            }
            $newToken = $getTokenData['new_token'];
            list($rechargeOk, $rechargeMsg, $rechargeData) = $this->rechargeBalanceToken($userid, $newToken, $money, '充值');
            if (empty($rechargeOk)) {
                throw new ApiException($rechargeMsg);
            }

            //充值成功后记录当前充值的时间
            $userMd = new UserModel();
            $datetime = date(FORMAT_DATETIME);
            $rechargeTimeSaveRes = $userMd->updateById($userid,['recharge_time' => "'$datetime'"]);

            $data['recharge_money'] = $money;
            $data['new_token']      = $newToken;
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
        }
        log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s, rechargeTimeSaveRes: %s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? '', $rechargeTimeSaveRes ?? ''));
        return [$ok, $msg, $data];
    }

    public function rechargeBalanceToken(int $userid, $newToken, $money = 0, $remark = ''): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            $this->userMd->startTrans();

            $user = $this->userMd->findByUserId($userid);
            $oldBalance = max($user['balance_token'], 0);

            $updateTokens = $oldBalance + $newToken;

            //开始充值
            $rechargeRes = $this->userMd->updateById($userid, ['balance_token' => $updateTokens]);
            if (empty($rechargeRes)) {
                throw new ApiException('充值失败,请重新尝试');
            }

            //记录流水
            $billDto = new BillDto();
            $billDto->setType(1);
            $billDto->setUserId($userid);
            $billDto->setToken($newToken);
            $billDto->setMoney($money);
            $billDto->setRemark($remark);
            $billDto->setBalance($updateTokens);
            $billService = new BillService();
            list($billOk, $billMsg, $billData) = $billService->recordBilling($billDto);
            if (empty($billOk)) {
                throw new ApiException($billMsg);
            }
            $this->userMd->commitTrans();
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
        }
        log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s,user:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? '', _j($user)));
        !$ok && $this->userMd->rollbackTrans();
        return [$ok, $msg, $data];
    }

    public function registerNewUser(UserDto $userDto): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];

        $this->userMd->startTrans();
        try {
            if (empty($userDto->getOpenId())) {
                throw new ApiException('openId为空');
            }
            $user   = $this->userMd->findByOpenId($userDto->getOpenId());
            $userid = $user['id'];
            if (empty($user)) {
                $userid = $this->userMd->create($userDto);
                if (empty($userid)) {
                    throw new ApiException('用户创建失败');
                }

                //首次注册送token，则记录流水
                if ($userDto->getBalanceToken()) {
                    $billService = new BillService();
                    $billDto     = new BillDto();
                    $billDto->setToken($userDto->getBalanceToken());
                    $billDto->setBalance($userDto->getBalanceToken());
                    $billDto->setType(1);
                    $billDto->setUserId($userid);
                    $billDto->setRemark('注册送token');
                    list($billOk, $billMsg, $billData) = $billService->recordBilling($billDto);
                    if (empty($billOk)) {
                        log_e(__METHOD__, sprintf('流水创建失败,ok:%s, msg:%s, data:%s', $billOk, $billMsg, _j($billData)));
                    }
                }
            }

            $data['user_id'] = $userid;
            $this->userMd->commitTrans();
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
        }
        log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        !$ok && $this->userMd->rollbackTrans();
        return [$ok, $msg, $data];
    }

    public function balance(Request $request): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            $userid = $request->getUserId();
            $user   = $this->userMd->findByUserId($userid);
            if (empty($user)) {
                throw new ApiException('用户不存在,userid: %s', $userid);
            }
            $data['balance'] = max(0, $user['balance_token']);
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
        }
        log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        return [$ok, $msg, $data];
    }

    public function isExistPassword(string $password): ?array {
        return $this->userMd->findByPassword($password);
    }

    public function listUsers(): array {
//        $this->userMd->setOrder(['id desc']);
        $this->userMd->setOrder(['last_active_time desc,id desc']);
        $list = $this->userMd->selectByConditions([]);
        return [true,'success', $list];
    }

    public function updateLastActiveTime($userid) {
        $user = $this->userMd->findByUserId($userid);
        $oldDatetime = $user['last_active_time'];
        $curDatetime = date(FORMAT_DATETIME);
        if (empty($oldDatetime) || self::overMinutes($oldDatetime)){
            $this->userMd->updateById($userid,['last_active_time' => "'$curDatetime'"]);
        }
    }

    public function isCanCommitQuestion(string $userid,string $question,string $randomId,int $newestQuestionId): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            if (empty($question)){
                throw new ApiException('问题为空');
            }
            $question = urldecode($question);
            $redis = RedisClient::getClient();
            $redisVal = ['question' => $question,'newest_question_id' => $newestQuestionId];
            log_d(__METHOD__, sprintf('存储之前的问题样式： %s', $question));
            $redisRes = $redis->set(RedisKeyConstant::makeKeyUserCommitQuestion($userid, $randomId), _j($redisVal),1 * 60);
            list($balanceOk, $balanceMsg, $balanceData) = $this->hasBalance($userid);
            if (!$balanceOk){
                throw new ApiException($balanceMsg);
            }
            $data = $balanceData;
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
        }
        log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s,redisRes: %s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? '', $redisRes ?? ''));
        return [$ok, $msg, $data];
    }
}
