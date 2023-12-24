<?php

namespace Lib\Curl;

use Lib\Msg\MsgConfig;

class HttpRequest
{
    public function fileGetContents(string $url)
    {
        return file_get_contents($url);
    }

    /**
     * curl请求
     * @param string $url
     * @param array $params
     * @param bool $isPost
     * @return mixed
     */
    public function curlRequest(string $url,array $params = [],bool $isPost = false){
        $header = $params['header'];
        unset($params['header']);
        $params = array_filter($params,function ($param){
            return !empty($param);
        });
        if (!$isPost && $params){
            $url = sprintf('%s?%s',$url,http_build_query($params));
        }
        $headerArray =array("Content-type:application/json;","Accept:application/json");
        if($header)
        {
            $headerArray = array_merge($headerArray,$header);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERPWD, MsgConfig::MSG_SECRET);

        if ($isPost){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output,true);
    }
}
