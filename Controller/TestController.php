<?php

namespace Controller;

use Model\CodeModel;
use Model\TokenModel;
use Service\AiService;
use Utils\RedisClient;
use Utils\Utils;

class TestController {
    public function login(){
        $mobile = '15555555555';
        $password = '123';

    }

    public function test_SpentToken(){
        $res = AiService::spentToken('1+1呢');
        varDumpExit($res);
    }








    public function testRedis(){
        log_e(__METHOD__,'出错了');
        log_d(__METHOD__,'233');
        $redis = RedisClient::getClient();
        varDumpExit($redis);
    }

    public function test(){
//        varDumpExit(date(FORMAT_DATETIME,strtotime('-5 min')));
        $validTime = strtotime('-5 min');
        $validDatetime = date(FORMAT_DATETIME, $validTime);
        varDumpExit($validDatetime);

        $password = Utils::getNoRepeatPasswordString();
        varDumpExit($password);


        $codeModel = new CodeModel();
        $userid    = $codeModel->checkingWxCode(1012, strval(494595));
        varDumpExit($userid);

        $token = '4441755d4132c666d015';
        $tokenModel = new TokenModel();
        $tokenData  = $tokenModel->findByToken($token);
        varDumpExit($tokenData);
    }

    public function calculate(){

    }
}
