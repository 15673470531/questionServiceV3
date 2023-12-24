<?php

use Controller\Question;

include_once dirname(__DIR__) . '/Common/Common.php';


$action = $_GET['action'] ?? '';


$questionController = new Question();
switch ($action){
    case 'commit':
        $orderId = $_GET['order_id'] ?? '';
        $content = $_GET['content'] ?? '';
        list($ok, $msg, $data) = $questionController->commit($orderId,$content);
        break;
    case 'list':
        $userid = $_GET['userid'] ?? '';
        list($ok, $msg, $data) = $questionController->list($userid);
        break;
    case 'detail':
        $questionId = $_GET['question_id'] ?? 0;
        list($ok, $msg, $data) = $questionController->detail($questionId);
        break;
    default:
        $ok = false;
        $msg = '未定义的路由: ' . $action;
        $data = [];
}
responseSuccess($ok, $msg, $data);





