<?php

namespace Service;

use Constants\RedisKeyConstant;
use Dto\QuestionDto;
use Model\QuestionModel;
use Throwable;
use Utils\ApiException;
use Utils\RedisClient;
use Utils\Utils;

class QuestionService extends BaseService {
    private QuestionModel $questionMd;

    public function __construct() {
        $this->questionMd = new QuestionModel();
    }

    public function commit() {

    }

    public function saveQuestionLog(QuestionDto $questionDto): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            /*$questionSpentTokens = AiService::spentToken($questionDto->getQuestion());
            $replySpentTokens    = AiService::spentToken($questionDto->getReplyContent());

            $questionDto->setTitleSpent($questionSpentTokens);
            $questionDto->setContentSpent($replySpentTokens);*/

            $questionDto = $this->questionMd->create($questionDto);
            if (empty($questionDto)) {
                throw new ApiException('保存问题记录失败');
            }
            $data = $questionDto;
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

    public function questionCommit2($userid, $orderId, $content): array {
        $ok    = true;
        $msg   = ApiException::getSuccessDesc();
        $data  = [];
        $redis = RedisClient::getClient();
        try {
            if (false == ($redis->setnx($key = RedisKeyConstant::makeKeyQuestionCommit($userid), 1))) {
                throw new ApiException('您输入太快啦');
            }
            //防止锁失效
            $redis->expire($key, 10);

            if (empty(trim($content))) {
                throw new ApiException('请输入正确消息内容');
            }

            //查询余额是否充足
            $userService = new UserService();
            list($hasOk, $hasMsg, $balanceData) = $userService->hasBalance($userid);
            if (empty($hasOk)) {
                throw new ApiException($hasMsg);
            }

            //获取ai回答
            $aiService = new AiService();
            list($getOk, $getMsg, $getData) = $aiService->getAnswerByAi($content, $userid);
            if (empty($getOk)) {
                throw new ApiException($getMsg);
            }

            //todo 优化成异步的方式
            //保存问题记录
            $replyContent = $getData['data'];
            $questionDto  = new QuestionDto();
            $questionDto->setOrderId($orderId);
            $questionDto->setQuestion($content);
            $questionDto->setUserid($userid);
            $questionDto->setReplyContent($replyContent ?? '');
            $questionService = new QuestionService();

            /** @var QuestionDto $saveQuestionDto */
            list($saveOk, $saveMsg, $saveQuestionDto) = $questionService->saveQuestionLog($questionDto);
            if (empty($saveOk)) {
                log_e(__METHOD__, sprintf('保存问题失败: ok:%s, msg:%s, data:%s', $saveOk, $saveMsg, _j($saveQuestionDto)));
//                throw new ApiException($saveMsg);
            }

            //扣费处理
            list($reduceOk, $reduceMsg,) = $userService->reduceBalanceToken($userid, $questionDto->getTotalSpentTokens(), '正常使用');
            if (!$reduceOk) {
                throw new ApiException($reduceMsg);
            }

            $data['answer']        = $replyContent;
            $data['balance_token'] = $balanceData['balance_token'] - $questionDto->getSpentTokens();
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
            log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        }
        $redis->del(RedisKeyConstant::makeKeyQuestionCommit($userid));
        return [$ok, $msg, $data];
    }


    public function questionCommit($userid, $orderId, $content): array {
        $ok    = true;
        $msg   = ApiException::getSuccessDesc();
        $data  = [];
        $redis = RedisClient::getClient();
        try {
            if (false == ($redis->setnx($key = RedisKeyConstant::makeKeyQuestionCommit($userid), 1))) {
                throw new ApiException('您输入太快啦');
            }
            //防止锁失效
            $redis->expire($key, 10);

            if (empty(trim($content))) {
                throw new ApiException('请输入正确消息内容');
            }

            //查询余额是否充足
            $userService = new UserService();
            list($hasOk, $hasMsg, $balanceData) = $userService->hasBalance($userid);
            if (empty($hasOk)) {
                throw new ApiException($hasMsg);
            }

            //获取ai回答
            $aiService = new AiService();
            list($getOk, $getMsg, $getData) = $aiService->getAnswerByAi($content, $userid);
            if (empty($getOk)) {
                throw new ApiException($getMsg);
            }

            //todo 优化成异步的方式
            //保存问题记录
            $replyContent = $getData['data'];
            $questionDto  = new QuestionDto();
            $questionDto->setOrderId($orderId);
            $questionDto->setQuestion($content);
            $questionDto->setUserid($userid);
            $questionDto->setReplyContent($replyContent ?? '');
            $questionService = new QuestionService();

            /** @var QuestionDto $saveQuestionDto */
            list($saveOk, $saveMsg, $saveQuestionDto) = $questionService->saveQuestionLog($questionDto);
            if (empty($saveOk)) {
                log_e(__METHOD__, sprintf('保存问题失败: ok:%s, msg:%s, data:%s', $saveOk, $saveMsg, _j($saveQuestionDto)));
//                throw new ApiException($saveMsg);
            }

            //扣费处理
            list($reduceOk, $reduceMsg,) = $userService->reduceBalanceToken($userid, $questionDto->getTotalSpentTokens(), '正常使用');
            if (!$reduceOk) {
                throw new ApiException($reduceMsg);
            }

            $data['answer']        = $replyContent;
            $data['balance_token'] = $balanceData['balance_token'] - $questionDto->getSpentTokens();
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
            log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        }
        $redis->del(RedisKeyConstant::makeKeyQuestionCommit($userid));
        return [$ok, $msg, $data];
    }

    public function listSummaryQuestions(int $opUserId): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            $maxLength = 90;
            $questions = $this->questionMd->findAllSummaryQuestions($opUserId);

            $isAdmin = isAdmin($opUserId);
            foreach ($questions as &$question) {
                if (!$isAdmin) {
                    $question['created_uid'] = 0;
                    $question['commit_version'] = 0;
                }
                $replyContent              = $question['reply_content'];

                $question['reply_content'] = Utils::omitRedundantChinese($replyContent, $maxLength);
                $question['question'] = Utils::omitRedundantChinese($question['question'], 50);
            }
            unset($question);
            $data = $questions;
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

    public function listLatelyQuestions($userid): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            $questions = $this->questionMd->findNewestQuestion($userid);
            $data['list']      = [];
            $data['newest_question_id'] = $questions['id'];
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

    public function listSameTimeQuestions(int $userid, int $questionId): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            if (empty($questionId)) {
                throw new ApiException('请求出错啦');
            }
            $questionMd = new QuestionModel();
            $question   = $questionMd->findById($questionId);
            $datetime   = date(FORMAT_DATE, strtotime($question['created_time']));

            $cond = [
                'id >= '           => $questionId,
                'created_time >= ' => sprintf('\'%s 00:00:00\'', $datetime),
                'created_time <= ' => sprintf('\'%s 23:59:59\'', $datetime),
//                'delete'           => 0,
            ];

            if (!isMyself($userid)) {
                $cond = array_merge($cond, ['created_uid' => $userid]);
            }
            $questions = $questionMd->selectByConditions($cond);

            $questions = $this->voListQuestions($questions);

            $data['questions'] = $questions;
            $data['datetime']  = $datetime;
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

    private function voListQuestions(array $questions): array {
        foreach ($questions as &$question) {
            $question['reply_content'] = htmlspecialchars_decode($question['reply_content']);
        }
        unset($question);
        return $questions;
    }
}
