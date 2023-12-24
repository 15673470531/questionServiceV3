<?php

use Service\TokenService;
use Service\UserService;

require_once __DIR__ . '/Common/Common.php';
$url       = $_SERVER['REQUEST_URI'];
$getParams = $_GET;
if (empty($getParams)) {
    header('location:/questionService/View/home.php');
}
$a = $getParams['a'] ?? '';
$c = $getParams['c'] ?? '';
unset($getParams['a'], $getParams['c']);
$otherParams = array_merge($getParams, $_POST);

log_d(__METHOD__, sprintf('controller:%s, action:%s ,接收参数:%s', $c, $a, _j($otherParams)));
$jsonBody = file_get_contents('php://input');
if (empty($jsonBody)) {
    $jsonBody = $_SERVER['REQUEST_BODY'];
}

if (empty($c) || empty($a)) {
    responseFail('访问链接错误');
}


$files = 'Controller';
if (strstr($c, '.') !== false) {
    //说明是有多层文件
    list($file, $c) = explode('.', $c);
    $files = 'Controller/' . $file;
}
$controllerFilePath = './' . $files . '/' . ucfirst($c) . '.php';
$controllerSuffix   = '';

if (!file_exists($controllerFilePath)) {
    $controllerSuffix   = 'Controller';
    $controllerFilePath = './' . $files . '/' . ucfirst($c) . 'Controller.php';
}
//varDumpExit($controllerFilePath, $controllerSuffix);

if (file_exists($controllerFilePath)) {
    $files      = str_replace('/', '\\', $files);
    $controller = $files . '\\' . ucfirst($c);

    if ($controllerSuffix) {
        $controller = $controller . $controllerSuffix;
    }

    try {
        $object = new $controller();

    } catch (Throwable $e) {
        $errorMsg = $e->getMessage();
        if (strstr($errorMsg, 'not found') !== false) {
            $controller .= 'Controller';

            $object     = new $controller();
        }

    }

    if (!method_exists($object, $a)) {
        responseFail('该方法不存在,action: ' . $a);
    }

    $notNeedVerifyControllers = [
        'Controller\OfficialAccount\CallbackController',
        'Controller\LoginController',
    ];
    $userid                   = 0;

    if (!in_array($controller, $notNeedVerifyControllers)) {
        //校验token
        $tokenService = new TokenService();
        $accessToken  = $getParams['access_token'] ?? '';

        list($verifyOk, $verifyMsg, $verifyData) = $tokenService->verifyToken($accessToken);
        if (empty($verifyOk)) {
            responseSuccess($verifyOk, $verifyMsg, $verifyData);
        }
        $userid = $verifyData['user_id'];

        $userService = new UserService();
        //todo 异步优化
        $userService->updateLastActiveTime($userid);
    }

    $requestClass  = 'Dto\\' . 'Request';
    $requestObject = new $requestClass($otherParams);
    $requestObject->setJsonBody($jsonBody);
    $requestObject->setUserId($userid);

    list($ok, $msg, $data) = $object->$a($requestObject);
    if (!$ok) {
        $logData = is_array($data) ? _j($data) : $data;
        log_d(__METHOD__, sprintf('错误记录,ok:%s, msg:%s, data:%s', $ok, $msg, $logData));
    }
    responseSuccess($ok, $msg, $data);
} else {
    responseFail('控制器不存在,file: ' . $controllerFilePath);
}


