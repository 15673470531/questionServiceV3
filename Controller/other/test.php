<?php
ob_end_flush();
//header('Content-Type:text/event-stream');
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
//header('Cache-Control: no-cache');
header('Connection: keep-alive');

/*echo "event: font\n";
echo "data: Arial\n\n";
echo "event: font\n";
echo "data: Times New Roman\n\n";

flush();*/
//echo 'event: sse_ini \n data:{}\n\n';
//flush();


log_d(__METHOD__, '开始请求');
$messages = array(
    "Hello",
    "World",
    "This",
    "Is",
    "Event",
    "Stream"
);

foreach ($messages as $message) {
    echo "event:open\n";
    echo "data:$message \n\n";
    log_d(__METHOD__, sprintf('开始输出,data:%s', $message));
    ob_flush();
    flush();
}
echo "event:close\n";
echo "data:wu\n\n";
log_d(__METHOD__, '输出完毕');
ob_flush();
flush();
exit;
/*$i = 0;
while ($i < 5){
    $event = 'my_event';
    $data = 'Hello sse';
    echo "event:font\n";
    echo "data: 233\n\n";
//    echo sprintf('event:%s \ndata: %s\n\n', $event, $data);
    ob_flush();
    flush();
    $i++;
    sleep(1);
}
ob_end_flush();*/

function log_d(string $method, string $log) {
    $logPath = '/usr/share/nginx/html/questionService/logs/debug.log';
//    $logPath = dirname(__DIR__) . '../' . 'logs/debug.log';
    $logData = sprintf('%s,%s,%s%s', date('Y-m-d H:i:s'), $method, $log, PHP_EOL);
    file_put_contents($logPath, $logData, FILE_APPEND | LOCK_EX);
}

function _j($array) {
    return json_encode($array, JSON_UNESCAPED_UNICODE);
}
