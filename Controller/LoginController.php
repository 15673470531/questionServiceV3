<?php

namespace Controller;

use Dto\Request;
use Model\CodeModel;
use Model\TokenModel;
use Service\LoginService;
use Service\TokenService;
use Service\UserService;
use Utils\Utils;

class LoginController extends Controller {

    /**
     * 通过公众号进行登录
     * @param Request $request
     * @return array
     */
    public function wxLogin(Request $request): array
    {
        $username     = $request->getParam('username');
        $password     = $request->getParam('password');
        $inviteUserId = $request->getParam('invite', 0);
        $loginService = new LoginService();
        return $loginService->wxLogin($username, $password, $inviteUserId);
    }

    public function hasAUth(Request $request){
        $login1 = $request->getParam('login1');
        $login2 = $request->getParam('login2');
        $a = 1;
        $n = 2;
    }

    public function recharge(Request $request): array
    {
        $opUserid    = $request->getUserId();
        $money       = $request->getParam('amount', 0);
        $userid      = $request->getParam('user_id', 0);
        $userService = new UserService();
        return $userService->recharge($opUserid, $userid, $money);
    }

}
