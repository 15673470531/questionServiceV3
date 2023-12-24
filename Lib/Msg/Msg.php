<?php

namespace Lib\Msg;

use Lib\Curl\HttpRequest;
use Utils\Utils;

class Msg {
    public static function send($mobile): array {
        $ok   = true;
        $msg  = 'success';
        $data = [];
        try {
            if (empty($mobile) || !Utils::checkingMobile($mobile)) {
                throw new \Exception('手机号格式不正确');
            }
            $sendUrl     = MsgConfig::URL;
            $message     = Utils::getRandomNums() . '【大数据提问】';
            $httpRequest = new HttpRequest();
            $params      = [
                'mobile'  => $mobile,
                'message' => $message,
            ];
            $data        = $httpRequest->curlRequest($sendUrl, $params, true);

            if ($data['error'] != 0) {
                throw new \Exception($data['msg']);
            }
        } catch (\Exception $e) {
            $ok   = false;
            $msg  = $e->getMessage();
            $data = [];
        }
        return [$ok, $msg, $data];
    }
}
