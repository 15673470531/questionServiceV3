<?php
ob_end_flush();
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');


//require dirname(__DIR__) . '/vendor/autoload.php';
//include_once dirname(__DIR__) . '../Common/Common.php';

$documentRoot = $_SERVER['DOCUMENT_ROOT'] .'/questionService/vendor/autoload.php';
require $documentRoot;
use Config\Ai;
use Swoole\OpenAi\OpenAi;

$apiKey = '37556-C4CF33B4B0F3A8C1A8E1C8E570AEFDCDD5CF7496';
$open_ai = new OpenAi($apiKey);
$open_ai->setBaseURL('https://chat.swoole.com');
//$messages[] = ["role" => "system", "content" => "You are a helpful assistant."];
//$messages[] = ["role" => "user", "content" => "Who won the world series in 2020?"];
//$messages[] = ["role" => "assistant", "content" => "The Los Angeles Dodgers won the World Series in 2020."];
//$messages[] = ["role" => "user", "content" => "世界上最高的山峰是什么?"];
$messages[] = ["role" => "user", "content" => "1+2等于多少呢"];
$txt = $error = '';

log_d(__METHOD__, '开始请求');
$complete = $open_ai->chat([
    'model' => 'gpt-3.5-turbo',
    'messages' => $messages,
    'temperature' => 1.0,
    'stream' => true,
], function ($curl_info, $data) use (&$txt, &$error) {

    log_d(__METHOD__, sprintf('curl_info:%s,data:%s', _j($curl_info),$data));
    // 请求结束
    if ($data === '[DONE]') {
        log_d(__METHOD__, sprintf('结束请求,close:%s',$data));
        echo "event:close\n";
        ob_flush();
        flush();
        return;
    }
    $chunk = json_decode($data, true);

    // 发生了错误
    if (isset($chunk['error'])) {
        $error = $chunk;
        log_d(__METHOD__, sprintf('error:%s',$error));
    } else {
        log_d(__METHOD__, sprintf('text:%s,data:%s', $txt, _j($data)));
        echo "event:open\n";
        echo "data:$txt,$data \n\n";
        ob_flush();
        flush();
//        $txt .= $chunk;
    }
    /*echo "event:open\n";
    echo "data:$txt \n\n";
    flush();*/

    // 发生了错误
    /*if (is_array($chunk)) {
        $error = $chunk;
    } else {
        echo "event:open\n";
        echo "data:$chunk \n\n";
        flush();
//        $txt .= $chunk;
    }*/
});
/*if ($complete) {
    echo "event:open\n";
    echo "data:$txt \n\n";
} else {
    var_dump($open_ai->getError(), $open_ai->getErrno());
}*/


function log_d(string $method, string $log) {
    $logPath = '/usr/share/nginx/html/questionService/logs/debug.log';
//    $logPath = dirname(__DIR__) . '../' . 'logs/debug.log';
    $logData = sprintf('%s,%s,%s%s', date('Y-m-d H:i:s'), $method, $log, PHP_EOL);
    file_put_contents($logPath, $logData, FILE_APPEND | LOCK_EX);
}

function _j($array) {
    return json_encode($array, JSON_UNESCAPED_UNICODE);
}
