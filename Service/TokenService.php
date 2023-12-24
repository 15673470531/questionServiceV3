<?php

namespace Service;

use Constants\ErrorCode;
use Constants\MoneyTokenMap;
use Constants\RechargeTokenConstant;
use Model\TokenModel;
use Model\UserModel;
use Throwable;
use Utils\ApiException;

class TokenService extends BaseService {
    //token七天的有效期
    const EXPIRE_TIME_TOKEN = 7 * 24 * 60 * 60;

    //新注册用户有5000个token, 邀请赠送1万
    //决定升级为 3 万
    const NEW_REGISTER_TOKEN = 1000;
    const INVITE_TOKENS      = 10000 * 3;

    public function createAccessToken() {
        $tokenLen = 20;
        if (file_exists('/dev/urandom')) { // Get 100 bytes of random data
            $randomData = file_get_contents('/dev/urandom', false, null, 0, 100) . uniqid(mt_rand(), true);
        } else {
            $randomData = mt_rand() . mt_rand() . mt_rand() . mt_rand() . microtime(true) . uniqid(mt_rand(), true);
        }
        return substr(hash('sha512', $randomData), 0, $tokenLen);
    }

    public function verifyToken(string $token): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            if (empty($token)) {
                throw new ApiException('token为空,请先登录', ErrorCode::ERROR_CODE_TOKEN_EMPTY);
            }
            $tokenModel = new TokenModel();
            $tokenData  = $tokenModel->findByToken($token);
            if (empty($tokenData)) {
                throw new ApiException('该token未找到，请重新登录', ErrorCode::ERROR_CODE_TOKEN_EXPIRED);
            }

            $tokenCreatedTimestamp = strtotime($tokenData['created_time']);

            if (!isAdmin($tokenData['user_id']) && time() - $tokenCreatedTimestamp > self::EXPIRE_TIME_TOKEN) {
                throw new ApiException('token已过期，请重新登录', ErrorCode::ERROR_CODE_TOKEN_EXPIRED);
            }

            $userMd = new UserModel();
            $user   = $userMd->findByUserId($tokenData['user_id']);
            if (empty($user)) {
                throw new ApiException('您的账号不存在，请重新登录', ErrorCode::ERROR_CODE_TOKEN_EXPIRED);
            }
            $data['user_id'] = $tokenData['user_id'];
        } catch (ApiException $e) {
            $ok                 = false;
            $msg                = $e->getMessage();
            $data['error_code'] = $e->getCode();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
        }
        log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        return [$ok, $msg, $data];
    }

    public function rechargeTokenByMoney2(int $money): array {
        if (empty($money)) {
            return [false, '充值金额错误', []];
        }
        $moneyTokenMap = RechargeTokenConstant::getMoneyTokenMap();

        $newToken = $moneyTokenMap[$money]['tokens'] ?? 0;
        if (empty($newToken)) {
            return [false, '充值金额大小未定义,money: ' . $money, []];
        }
        $data['new_token'] = $newToken;
        return [true, 'success', $data];
    }


    public function rechargeTokenByMoney(int $money): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            if (empty($money)) {
                throw new ApiException('充值金额错误');
            }
            $moneyTokenMap = RechargeTokenConstant::getMoneyTokenMap();

            $newToken      = $moneyTokenMap[$money]['tokens'] ?? 0;
            if (empty($newToken)) {
                throw new ApiException('充值金额大小未定义,money: ' . $money);
            }
            $data['new_token'] = $newToken;
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
            log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        }
        return [$ok, $msg, $data];
    }

    public function addTokens(int $userid, int $inviteUserid, int $tokens, $remark = ''): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            $userService = new UserService();
            list($ok, $msg, $data) = $userService->rechargeBalanceToken($inviteUserid, $tokens, 0, $remark);
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

}
