<?php

namespace Controller;

use Constants\RedisKeyConstant;
use Dto\BillDto;
use Dto\QuestionDto;
use Model\UserModel;
use Service\BillService;
use Service\QuestionService;
use Service\UserService;
use Utils\ApiException;
use Utils\RedisClient;

class CronController {
    public function autoExpireToken()
    {
        $userModel            = new UserModel();
        $needExpireTokenUsers = $userModel->findAllNeedExpireTokenUsers();
        $logs                 = [];
        foreach ($needExpireTokenUsers as $needExpireTokenUser) {
            $userid       = $needExpireTokenUser['id'];
            $expireTokens = $needExpireTokenUser['balance_token'];

            $ok  = true;
            $msg = 'success';
            try {
                $userModel->startTrans();
                $newBalanceTokens      = 0;
                $updateBalanceTokenRes = $userModel->updateById($userid, ['balance_token' => $newBalanceTokens]);
                if (!$updateBalanceTokenRes) {
                    throw new ApiException('更新失败');
                }

                //记录流水
                $billDto = new BillDto();
                $billDto->setToken($expireTokens);
                $billDto->setUserId($userid);
                $billDto->setType(0);
                $billDto->setRemark('已过期');
                $billDto->setBalance($newBalanceTokens);
                $billService = new BillService();
                list($billOk, $billMsg, $billRes) = $billService->recordBilling($billDto);
                if (!$billOk) {
                    throw new ApiException($billMsg);
                }

                $userModel->commitTrans();
            } catch (ApiException | \Throwable $e) {
                $ok  = false;
                $msg = $e->getMessage();
                $userModel->rollbackTrans();
            }
            log_d(__METHOD__, sprintf('userid: %s,ok:%s, msg:%s', $userid, $ok, $msg));
            $logs[] = [
                'datetime' => date(FORMAT_DATETIME),
                'user_id'       => $userid,
                'expire_tokens' => $expireTokens,
                'ok'            => $ok,
                'msg'           => $msg
            ];
        }
        echo _j($logs);
    }

    public function consumeQueueSaveQuestion()
    {
        $key   = RedisKeyConstant::makeKeyQueueSaveQuestion();
        $redis = RedisClient::getClient();

        $maxExecuteTime  = 60;
        $questionService = new QuestionService();
        $userService     = new UserService();
        $executeTime     = time();

        $logs             = [];
        $logs['datetime'] = date(FORMAT_DATETIME);
        while (time() - $executeTime <= $maxExecuteTime) {
            $val = $redis->rPop($key);
            if (empty($val)) {
                usleep(100000);
                continue;
            }
            $logs['val'] = $val;
            /** @var QuestionDto $questionDto */
            $questionDto = unserialize($val);
            if (!is_object($questionDto)) {
                $logs['msg'] = '错误格式';
                log_d(__METHOD__, sprintf('错误格式: %s', _j($questionDto)));
                continue;
            }
            $questionDto->setCommitVersion(2);
            /** @var QuestionDto $saveQuestionDto */
            list($saveOk, $saveMsg, $saveQuestionDto) = $questionService->saveQuestionLog($questionDto);
            if (empty($saveOk)) {
                $msg             = sprintf('保存问题失败: ok:%s, msg:%s, data:%s', $saveOk, $saveMsg, _j($saveQuestionDto));
                $logs['saveMsg'] = $msg;
                log_e(__METHOD__, $msg);
            }

            //扣费处理
            list($reduceOk, $reduceMsg, $reduceData) = $userService->reduceBalanceToken($questionDto->getUserid(), $questionDto->getTotalSpentTokens(), '正常使用');
            if (!$reduceOk) {
                $msg               = sprintf('扣费失败: ok:%s, msg:%s, data:%s', $reduceOk, $reduceMsg, _j($reduceData));
                $logs['reduceMsg'] = $msg;
                log_e(__METHOD__, $msg);
            }
        }
        echo $logs . PHP_EOL;
    }
}
