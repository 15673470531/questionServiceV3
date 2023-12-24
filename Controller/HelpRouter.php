<?php

use Controller\HelpController;

include_once dirname(__DIR__) . '/Common/Common.php';
$action = $_GET['action'] ?? '';

try {
    $helpController = new HelpController();
    switch ($action){
        case 'commit':
            $userid = $_GET['userid'] ?? 0;
            $content = trim($_GET['content']) ?? '';
            if (empty($content)){
                throw new Exception('提交内容不能为空');
            }
            list($ok, $msg, $data) = $helpController->create($userid, $content);
            break;
        default:
            $ok = false;
            $msg = '该路由未定义';
            $data = [];
    }
    responseSuccess($ok,$msg,$data);
}catch (Exception $e){
    responseFail($e->getMessage());
}

