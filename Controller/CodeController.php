<?php

namespace Controller;

use Dto\Request;
use Model\CodeModel;
use Utils\Utils;

class CodeController extends Controller {
    public function send(Request $request): array {
        $username = $request->getParam('username');
        $userid = $request->getUserId();

        if (!Utils::checkingMobile($username)){
            return [false,'手机号格式错误', []];
        }
        //todo 发送验证码成功
        $code = Utils::getRandomNums();

        //记录入库
        $codeModel = new CodeModel();
        $createCodeSuccess = $codeModel->create($username, $code);
        return [true,'发送验证码成功', ['create' => $createCodeSuccess]];
    }
}
