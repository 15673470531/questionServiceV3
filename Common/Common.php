<?php

const APP_NAME = 'questionService';
define("APP_ROOT_PATH", dirname(__DIR__));

const FORMAT_DATETIME        = 'Y-m-d H:i:s';
const FORMAT_DATE            = 'Y-m-d';
const FORMAT_DAY_END         = 'Y-m-d 23:59:59';
const FORMAT_DATE_YUE_RI_HAN = 'n月j日';
const FORMAT_DATEH           = 'Y-m-d H';
const FORMAT_TIME            = 'H:i:s';
const FORMAT_SIMPLE_DATE     = 'm-d H:i';

// 定义自动加载函数
spl_autoload_register(function ($className) {
    // 按照约定的命名规则转换类名到文件路径
    $filePath = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    $path = APP_ROOT_PATH . '/' . $filePath;
    // 检查文件是否存在，如果存在则引入
    if (file_exists($path)) {
        require_once $path;
    }
});


function responseSuccess($ok = true, $msg = 'success', $obj = []) {
    header('Content-type:application/json');
    $data['ok']  = $ok;
    $data['msg'] = $msg;
    $data['obj'] = $obj;
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function responseFail($msg = 'fail', $obj = []) {
    $ok = false;
    header('Content-type:application/json');
    $data['ok']  = $ok;
    $data['msg'] = $msg;
    $data['obj'] = $obj;
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function varDumpExit(...$params) {
    foreach ($params as $param) {
        var_dump($param);
    }
    exit;
}

function log_d(string $method, string $log) {
    $logPath = APP_ROOT_PATH . '/' . 'logs/debug.log';
    $logData = sprintf('%s,%s,%s%s', date(FORMAT_DATETIME), $method, $log, PHP_EOL);
    file_put_contents($logPath, $logData, FILE_APPEND | LOCK_EX);
}

function log_e(string $method, string $log) {
    $logPath = APP_ROOT_PATH . '/' . 'logs/error.log';
    $logData = sprintf(' %s,%s,%s%s', date(FORMAT_DATETIME), $method, $log, PHP_EOL);
    file_put_contents($logPath, $logData, FILE_APPEND | LOCK_EX);
}

function _j($array) {
    return json_encode($array, JSON_UNESCAPED_UNICODE);
}

function responseJson($data) {
    header("Content-Type:application/json");
    echo _j($data);
    exit;
}

function log_important(string $method, string $log) {
    $prefix = 'important_log';
    log_d($method, $prefix . ' | ' . $log);
}

function log_sql(string $method, string $log) {
    $prefix = 'important_log_sql';
    log_d($method, $prefix . ' | ' . $log);
}

function isMyself(int $userid): bool {
//    return in_array($userid, [1, 10], true);
    return in_array($userid, [1], true);
}

function isAdmin(int $userid): bool {
    return isMyself($userid);
}

