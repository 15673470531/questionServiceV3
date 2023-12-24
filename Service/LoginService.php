<?php

namespace Service;

use Dto\TokenDto;
use Model\CodeModel;
use Model\TokenModel;
use Model\UserModel;
use Throwable;
use Utils\ApiException;

class LoginService extends BaseService {

    public function wxLogin($username, $password, $inviteUserId = 0): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        $codeModel = new CodeModel();
        $codeModel->startTrans();
        try {
            if (empty($password)){
                throw new ApiException('验证码为空');
            }

            $userid    = $codeModel->checkingWxCode($username, strval($password));

            //登录成功流程
            $tokenService = new TokenService();
            $token        = $tokenService->createAccessToken();

            $tokenDto = new TokenDto();
            $tokenDto->setUserid($userid);
            $tokenDto->setToken($token);

            $tokenModel    = new TokenModel();
            $createSuccess = $tokenModel->create($tokenDto);
            if (empty($createSuccess)) {
                throw new ApiException('token创建失败,请尝试刷新重试');
            }

            if (UserService::isNewUser($userid) && $inviteUserId){
                //填入推荐人并且给推荐人加token
                $userMd = new UserModel();
                $userMd->updateById(intval($userid), ['referee_uid' => $inviteUserId]);
                $tokenService->addTokens(intval($userid),intval($inviteUserId), TokenService::INVITE_TOKENS,'邀请新用户注册奖励');
            }

            $this->afterLoginSuccess($userid);

            $data['token'] = $token;
            $data['userid'] = $userid;
            $codeModel->commitTrans();
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
        }
        !$ok && $codeModel->rollbackTrans();
        log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        return [$ok, $msg, $data];
    }

    private function afterLoginSuccess(int $userid) {
        $userMd = new UserModel();
        $datetime = date(FORMAT_DATETIME);
        $userMd->updateById($userid,['last_login_time' => "'$datetime'"]);

        try {
            $userMd->incrLoginTimes($userid);
        }catch (Throwable $e){

        }
    }
}
