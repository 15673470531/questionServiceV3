<?php

namespace Controller;

use Constants\RedisKeyConstant;
use Dto\QuestionDto;
use Dto\Request;
use Service\AiService;
use Service\OpenaiService;
use Swoole\OpenAi\OpenAi;
use Utils\RedisClient;

class AiController extends Controller {
    const MAX_LENGTH_FIRST_SEND_EVENT = 5;

    public function getAnswerByAi(Request $request): array
    {
        //todo 请求过于频繁
        $content = $request->getParam('content', '');
//        sleep(2);
//        return [true,'success', ['data' => '测试成功']];
        return $this->sendAi($content);
    }

    public function eventStream(Request $request)
    {
        ob_end_flush();
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering:no');

        $redis            = RedisClient::getClient();
        $userid           = $request->getUserId();
        $randomId         = $request->getParam('random_id');
        $redisVal         = $redis->get(RedisKeyConstant::makeKeyUserCommitQuestion($userid, $randomId));
        $redisData        = json_decode($redisVal, true);
        $question         = $redisData['question'];
        $newestQuestionId = $redisData['newest_question_id'];

        log_d(__METHOD__, sprintf('问题： %s', $question));
        if (empty($question)) {
            $this->sendErrorEvent('请先输入您的问题');
        }

        $test         = 0;
        $documentRoot = APP_ROOT_PATH . '/vendor/autoload.php';
        require $documentRoot;

        $apiKey  = '37556-C4CF33B4B0F3A8C1A8E1C8E570AEFDCDD5CF7496';
        $open_ai = new OpenAi($apiKey);
        $open_ai->setBaseURL('https://chat.swoole.com');
        if ($this->hasPer($userid)) {
            if ($newestQuestionId) {
                $openaiService    = new OpenaiService();
                $messageContainer = $openaiService->makeMessages($userid, $question, $newestQuestionId);
                $messages         = $messageContainer['messages'];

                $questionsString     = $messageContainer['questions_string'];
                $replyContentsString = $messageContainer['reply_contents_string'];
            } else {
                $messages[] = ["role" => "user", "content" => $question];
            }
//            $aiService2->getMessage($question);
//            $messages[] = ["role" => "user", "content" => '你猜我家里今天住了多少人'];
//            $messages[] = ["role" => "assistant", "content" => '三个人吗'];
        } else {
            $messages[] = ["role" => "user", "content" => $question];
        }

        $replyContent = '';

        $txt      = $error = '';
        $complete = $open_ai->chat([
            'model'       => 'gpt-3.5-turbo',
            //            'model'       => 'gpt',
            'messages'    => $messages,
            'temperature' => 0.8,
            'stream'      => true,
        ], function ($curl_info, $data) use (&$txt, &$error, &$replyContent) {
            // 请求结束
            if ($data === '[DONE]') {
                log_d(__METHOD__, '关闭1');
                $this->sendCloseEvent('[done]');
                return;
            }
            $chunk = json_decode($data, true);
            // 发生了错误
            if (is_array($chunk)) {
                $error = $chunk;
                $this->sendErrorEvent($error);
            } else {
                $replyContent .= $chunk;
                $this->sendEvent($chunk);
                usleep(10000);
            }
        });

        if ($complete) {
            try {
                $redis       = RedisClient::getClient();
                $questionDto = new QuestionDto();
                if ($this->hasPer($userid)) {
                    $replyContentsString .= $replyContent;
                    $questionDto->setQuestionString($questionsString);
                    $questionDto->setReplyContentString($replyContentsString);

                    log_d(__METHOD__, sprintf('replyContentString:%s, questionString:%s', $replyContentsString, $questionsString));
                }
                $questionDto->setQuestion($question);

                $questionDto->setUserid($userid);
                $questionDto->setReplyContent($replyContent);
                $redis->lPush(RedisKeyConstant::makeKeyQueueSaveQuestion(), serialize($questionDto));
            } catch (\Throwable $e) {
                log_e(__METHOD__, sprintf('redis错误: %s', $e->getMessage()));
            }
            $this->sendCloseEvent('[done]');
        }
    }

    function sendEvent($message, $event = 'message')
    {
        echo "event: $event\n";
        echo "data: $message\n\n";
        ob_flush();
        flush();
    }

    function sendErrorEvent($message, $event = 'error')
    {
        echo "event: $event\n";
        echo "data: $message\n\n";
        ob_flush();
        flush();
    }

    function sendCloseEvent($message, $event = 'close')
    {
        echo "event: $event\n";
        echo "data: $message\n\n";
        ob_flush();
        flush();
    }

    public function eventStreamTest(Request $request)
    {
        ob_end_flush();
        $question = $request->getParam('question');
        $key      = md5($question);
        $redis    = RedisClient::getClient();
        $redisKey = RedisKeyConstant::makeKeyEventStreamRepeat('');
        $isFirst  = $redis->setnx($redisKey, 1);
        log_d(__METHOD__, sprintf('redisKey: %s, isFirst: %s', $redisKey, $isFirst));
        if (!$isFirst) {
            log_d(__METHOD__, '不是第一次，中止');
            exit;
        }
        $redis->expire($redisKey, 10);
//header('Content-Type:text/event-stream');
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
//header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        log_d(__METHOD__, '开始请求');
        $messages = array(
            "Hello",
            "World",
            "This",
            "Is",
            "Event",
            "Stream",
            "Stream1",
            "Stream2",
            "Stream3",
            "Stream4",
            "Stream5",
            "Is",
            "Event",
            "Stream",
            "Stream1",
            "Stream2",
            "Stream3",
            "Stream4",
            "Stream5",
            "今天是个好天气啊",
            "是啊是啊",
            "哈哈哈哈哈",
        );

        $str = '';
        foreach ($messages as $message) {
            if (strlen($str) >= 5) {
                $str .= $message;
                $this->sendEvent($str);
                $str = '';
            } else {
                $str .= $message;
            }
            usleep(10000);
        }

        $this->sendEvent($str);
        $this->sendEvent('[done]');
        log_d(__METHOD__, '输出完毕');
//        ob_flush();
//        flush();

        $delRes = $redis->del($redisKey);
        log_d(__METHOD__, sprintf('释放redis:%s, result: %s', $redisKey, $delRes));
        exit;
    }

    /*    public function sendAi($content) {
            $documentRoot = APP_ROOT_PATH .'/vendor/autoload.php';
            require $documentRoot;

            $apiKey  = '37556-C4CF33B4B0F3A8C1A8E1C8E570AEFDCDD5CF7496';
            $open_ai = new OpenAi($apiKey);
            $open_ai->setBaseURL('https://chat.swoole.com');
    //$messages[] = ["role" => "system", "content" => "You are a helpful assistant."];
    //$messages[] = ["role" => "user", "content" => "Who won the world series in 2020?"];
    //$messages[] = ["role" => "assistant", "content" => "The Los Angeles Dodgers won the World Series in 2020."];
            $messages[] = ["role" => "user", "content" => $content];

            $txt        = $error = '';
            $complete   = $open_ai->chat([
                'model'       => 'gpt-3.5-turbo',
    //            'model'       => 'gpt',
                'messages'    => $messages,
                'temperature' => 0.8,
                'stream'      => true,
            ], function ($curl_info, $data) use (&$txt, &$error) {
                // 请求结束
                if ($data === '[DONE]') {
                    return;
                }
                $chunk = json_decode($data, true);
                // 发生了错误
                if (is_array($chunk)) {
                    $error = $chunk;
                } else {
                    $txt .= $chunk;
                }
            });
            if ($complete) {
    //            var_dump($txt, $error);
                return [true, 'success', ['data' => $txt,'error' => $error]];
            } else {
                return [false,$open_ai->getError(), []];
    //            var_dump($open_ai->getError(), $open_ai->getErrno());
            }
        }*/
    private function hasPer($userid)
    {
        return true;
        return $userid == 1;
    }
}
