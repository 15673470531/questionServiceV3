<?php

namespace Service;

use Config\WxConfig;
use Model\WxTokenModel;
use Throwable;
use Utils\ApiException;
use Utils\HttpRequest;

class WxService extends BaseService {
    const TOKEN_EXPIRE = 7200;

    private static function getMockCustomMenuParams() {
        $json = ' {
     "button":[
     {	
          "type":"click",
          "name":"今日歌曲",
          "key":"V1001_TODAY_MUSIC"
      },
      {
           "name":"菜单",
           "sub_button":[
           {	
               "type":"view",
               "name":"搜索",
               "url":"http://www.soso.com/"
            },
            {
                 "type":"miniprogram",
                 "name":"wxa",
                 "url":"http://mp.weixin.qq.com",
                 "appid":"wx286b93c14bbf93aa",
                 "pagepath":"pages/lunar/index"
             },
            {
               "type":"click",
               "name":"赞一下我们",
               "key":"V1001_GOOD"
            }]
       }]
 }';
        return json_decode($json, true);
    }

    private static function isInValidTokenData($tokenData): bool {
        return time() - strtotime($tokenData['created_time']) > self::TOKEN_EXPIRE;
    }


    /**
     * 获取token
     * @return array
     */
    public function getAccessToken(): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            $wxTokenMd = new WxTokenModel();
            $tokenData = $wxTokenMd->findLatestToken();
            if (self::isInValidTokenData($tokenData)) {
                //token过期了自动刷新
                list($refreshOk, $refreshMsg, $refreshData) = $this->refreshAccessToken();
                if (empty($refreshOk)) {
                    throw new ApiException($refreshMsg);
                }
                $data['access_token'] = $refreshData['access_token'];
            } else {
                $data['access_token'] = $tokenData['access_token'];
            }
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
            log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        }
        return [$ok, $msg, $data];
    }


    /**
     * 刷新token
     * @return array
     */
    public function refreshAccessToken(): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            $appId     = WxConfig::APPID;
            $appSecret = WxConfig::AppSecret;

            $url         = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s', $appId, $appSecret);
            $httpRequest = new HttpRequest();
            $data        = $httpRequest->curlRequest($url);
            if (empty($accessToken = $data['access_token'])) {
                throw new ApiException('获取token失败');
            }

            $wxTokenMd  = new WxTokenModel();
            $createdRes = $wxTokenMd->create($accessToken);
            if (empty($createdRes)) {
                throw new ApiException('token保存失败');
            }
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
            log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        }
        return [$ok, $msg, $data];

    }

    public function customMenu(): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            list($getOk, $getMsg, $getData) = $this->getAccessToken();
            if (empty($getOk)){
                throw new ApiException($getMsg);
            }
            $token = $getData['access_token'];
            $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $token;

            $httpRequest = new HttpRequest();
            $params = self::getMockCustomMenuParams();
            $res = $httpRequest->curlRequest($url,$params,true);
            varDumpExit($res);
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
        }
        log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        return [$ok, $msg, $data];

    }
}
