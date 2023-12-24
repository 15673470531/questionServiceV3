<?php

namespace Service;

use Swoole\OpenAi\OpenAi;
use Utils\Utils;

class AiService extends BaseService {
    //全中文时，一个中文算2token
    const chineseSpentTokens = 2;
    //全英文的时，一个英文字符算1token
    const englishSpentTokens = 1;

    const API_KEY = '37556-C4CF33B4B0F3A8C1A8E1C8E570AEFDCDD5CF7496';

    public function getAnswerByAi($content, $userid): array {
        /*if ($userid == 1){
            sleep(5);
            return [
                true,
                'success',
                ['data' => '测试a成功bc']
            ];
        }*/
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
    }




    public static function spentToken(string $text){
        if (Utils::hasChinese($text)){
            //有中文直接按照两个token算
            $length = mb_strlen($text);
            $spentTokens = $length * self::chineseSpentTokens;
        }else{
            //没有中文，全是英文
            $length = strlen($text);
            $spentTokens = $length * self::englishSpentTokens;
        }
        return $spentTokens;
    }



    /*    public function getAnswerByAi2(){
        $open_ai = new OpenAi(self::API_KEY);
        $messages[] = ["role" => "system", "content" => "You are a helpful assistant."];
        $messages[] = ["role" => "user", "content" => "ChatGPT 的出现对于 AI 行业的影响有哪些 ?"];
        $text = $error = '';
        $complete = $open_ai->chat([
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'temperature' => 1.0,
            'max_tokens' => 4000,
            'stream' => true,
        ], function ($curl_info, $data) use (&$text, &$error) {
            // 请求结束
            if ($data === '[DONE]') {
                return;
            }
            $chunk = json_decode($data, true);
            // 发生了错误
            if (isset($chunk['error'])) {
                $error = $chunk;
            } else {
                $text .= $chunk;
            }
        });
        echo $text;
    }*/
}
