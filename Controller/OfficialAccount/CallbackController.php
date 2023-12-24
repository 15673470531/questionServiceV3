<?php

namespace Controller\OfficialAccount;

use Controller\Controller;
use Dto\BaseMsgDto;
use Dto\Request;
use Dto\UserDto;
use Model\CodeModel;
use Model\UserModel;
use Service\TokenService;
use Service\UserService;
use Throwable;
use Utils\ApiException;
use Utils\Utils;

class CallbackController extends Controller {
    const TOKEN              = 'guoqing';
    const FLAG_LOGIN_MAX_NUM = 10000;

    private static function getNoRepeatCode(): string {
        $code      = Utils::getRandomNums();
        $codeModel = new CodeModel();
        while ($codeModel->isExistCode($code)) {
            $code = Utils::getRandomNums();
        }
        return $code;
    }




    public function receiveRequest(Request $request): array { //gh_9305e7af750e
        log_d(__FUNCTION__, sprintf('接受公众号消息: %s, bodyData: %s', $request->getJsonParams(), $request->getJsonBody()));
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            $params = $request->getJsonParams();
//            $params = '{"signature":"608ec3391342bc862098fe8b41aea9a6b21d35e3","echostr":"7413400286621847293","timestamp":"1695403093","nonce":"292933483"}';
            if ($params) {
                $params = json_decode($params, true);
            }
            if (empty($params)) {
                throw new ApiException('参数为空');
            }
            $sign      = $params['signature'];
            $echoStr   = $params['echostr'];
            $timestamp = $params['timestamp'];
            $nonce     = $params['nonce'];

            $checkingResult = $this->checkSignature($sign, $timestamp, $nonce);
            if (empty($checkingResult)) {
                throw new ApiException('校验失败');
            }

            $this->callbackProcessing($request);
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

    private function checkSignature($sign, $timestamp, $nonce): bool {
        $signature = $sign;
        $timestamp = $timestamp;
        $nonce     = $nonce;

        $token  = self::TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * <xml><ToUserName><![CDATA[gh_9305e7af750e]]></ToUserName>
     * <FromUserName><![CDATA[oh0-H6NPz6h9OBeUo8J0NK8iNT0E]]></FromUserName>
     * <CreateTime>1695475441</CreateTime>
     * <MsgType><![CDATA[text]]></MsgType>
     * <Content><![CDATA[4]]></Content>
     * <MsgId>24273370205815980</MsgId>
     * </xml>
     * 回调处理
     * @param Request $request
     * @throws ApiException
     */
    private function callbackProcessing(Request $request) {
        $jsonBody = $request->getJsonBody();

        $xmlData      = simplexml_load_string($jsonBody);
        $fromUserName = $xmlData->FromUserName; //发送方的openID
        $content      = trim($xmlData->Content);
        $event = $xmlData->Event;

        $baseMsgDto = new BaseMsgDto();
        $baseMsgDto->setToUserName($xmlData->ToUserName);
        $baseMsgDto->setFromUserName($xmlData->FromUserName);
        $baseMsgDto->setCreateTime($xmlData->CreateTime);
        $baseMsgDto->setMsgType($xmlData->MsgType);
        $baseMsgDto->setContent($xmlData->Content);
        $baseMsgDto->setMsgId($xmlData->MsgId);

        if ($fromUserName) {
            //创建用户
            $userDto = new UserDto();
            $userDto->setOrderId(0);
            $userDto->setOpenId($fromUserName);
            $userDto->setBalanceToken(TokenService::NEW_REGISTER_TOKEN);

            //注册并且记录流水
            $userService = new UserService();
            list($registerOk, $registerMsg, $registerData) = $userService->registerNewUser($userDto);
            if (empty($registerOk)) {
                log_d(__METHOD__,sprintf('记录流水失败,ok:%s, msg:%s, data:%s', $registerOk, $registerMsg, _j($registerData)));
                throw new ApiException($registerMsg);
            }

            $userid = $registerData['user_id'];
            if (empty($userid)) {
                throw new ApiException(sprintf('创建或者查询用户失败,openId: %s', $fromUserName));
            }
            if (isset($event) && $event == 'subscribe'){
                //关注事件
                $this->eventSubscribe($baseMsgDto);
            }elseif (is_numeric($content) && intval($content) <= self::FLAG_LOGIN_MAX_NUM) {
                //接受验证码
                $this->sendCode($userid, $baseMsgDto);
            } else {
                $this->eventUndefined($baseMsgDto);
            }
        }
    }

    /**
     * @param $userid
     * @param baseMsgDto $baseMsgDto
     * @throws ApiException
     */
    private function sendCode($userid, BaseMsgDto $baseMsgDto) {
        //生成验证码并且发送给公众号进行登录
        $code      = self::getNoRepeatCode();
        $codeModel = new CodeModel();
        log_d(__METHOD__, sprintf('userid: %s, baseMsgDeto: %s', $userid, _j($baseMsgDto)));
        $createCode = $codeModel->create(0, $code, $userid, $baseMsgDto->getContent());
        if (empty($createCode)) {
            throw new ApiException('生成验证码失败,userId: %s, code: %s', $userid, $code);
        }
        $msg = sprintf('您本次登录的验证码:%s%s', PHP_EOL,$code) . self::getPublicMsg();
        $this->replyMsg($baseMsgDto->getFromUserName(), $msg);
    }

    public function replyMsg($receiveOpenId, $msg) {
        $wxId    = 'gh_9305e7af750e';
        $xmlData = self::getReplyXml($receiveOpenId, $wxId, $msg);
        echo $xmlData;
        exit;
    }

    private static function getReplyXml($openId, $wxId, $replyContent): string {
        $time = time();
        return <<<XML
<xml><ToUserName><![CDATA[$openId]]></ToUserName><FromUserName><![CDATA[$wxId]]></FromUserName><CreateTime>$time</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[$replyContent]]></Content></xml>
XML;
    }

    public static function getAddGroupMsg(): string {
        return sprintf('%s欢迎加入学习交流QQ群:%s%s%s',PHP_EOL,PHP_EOL,'901893668',PHP_EOL);
    }

    public static function getPublicMsg(): string {
//        return self::getOpenSourceMsg() . self::getAddGroupMsg();
        return self::getOpenSourceMsg() . self::getContactAuthorMsg();
    }

    public static function getContactAuthorMsg(): string {
        return sprintf('%s客服微信号:%s%s%s',PHP_EOL,PHP_EOL,'q13396945846',PHP_EOL);
    }

    public static function getOpenSourceMsg(){
        return sprintf('%s%s一些开源项目源码:%s%s%s', PHP_EOL,PHP_EOL,PHP_EOL,'敬请期待',PHP_EOL);
    }

    public static function getChatUrlMsg(){
        $url = 'http://82.156.139.209/';
        $msg = sprintf('chatGPT登录网址：%s%s%s%schatGPT登录注意事项：%s1. 验证码有效时间五分钟之内，登录成功之后上次的验证码就会失效%s2. 若小主发现接受不到验证码，可能是服务器出现了故障，可以联系客服进行反馈，这边将为您尽快安排修复'
            ,PHP_EOL, $url,PHP_EOL,PHP_EOL,PHP_EOL,PHP_EOL,
        );
        return $msg;
    }

    /**
     * 其他事件
     * @param $baseMsgDto
     */
    private function eventUndefined($baseMsgDto) {
        $baseMsg = sprintf('该命令我还未学会😭%s%s',PHP_EOL, PHP_EOL);
        $msg = $baseMsg . self::getChatUrlMsg() . self::getPublicMsg();
        //todo 别的消息，暂时不处理
        $this->replyMsg($baseMsgDto->getFromUserName(), $msg);
    }


    /**
     * 关注事件
     * @param $baseMsgDto
     */
    private function eventSubscribe($baseMsgDto) {
        $baseMsg = sprintf('欢迎小主的关注%s%s',PHP_EOL, PHP_EOL);
        $msg = $baseMsg . self::getChatUrlMsg() . self::getPublicMsg();
        $this->replyMsg($baseMsgDto->getFromUserName(), $msg);
    }

    /**
     * 接受验证码失败的补救措施
     * @param BaseMsgDto $baseMsgDto
     */
    private function sendCodeRemedy(BaseMsgDto $baseMsgDto) {
        $password = Utils::getNoRepeatPasswordString();
        $url = 'http://82.156.139.209/';
        $msg = sprintf('欢迎小主的关注%s%schatGPT登录网址：%s%s%s%s登录注意事项：%s1. 必须要页面上的登录码和收到的验证码对应上才能登录哦%s2. 若发现接受不到验证码，请发送命令：帮助，系统将会自动为您创建一个专属登录密码发送给您',
            PHP_EOL,PHP_EOL,PHP_EOL, $url,PHP_EOL,PHP_EOL,PHP_EOL,PHP_EOL
        );
        //todo 别的消息，暂时不处理
        $this->replyMsg($baseMsgDto->getFromUserName(), $msg);
    }
}
