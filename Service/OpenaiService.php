<?php

namespace Service;

use Model\QuestionModel;

class OpenaiService {
    private $questionMd;

    public function __construct()
    {
        $this->questionMd = new QuestionModel();
    }

    /**
     * $messages[] = ["role" => "user", "content" => '你猜我家里今天住了多少人'];
     * $messages[] = ["role" => "assistant", "content" => '三个人吗'];
     * @param int $userid
     * @param string $newQuestion
     * @param int $newestQuestionId
     * @return array
     */
    public function makeMessages(int $userid, string $newQuestion, int $newestQuestionId): array
    {
        $messages            = [];
        $questionsString     = '';
        $replyContentsString = '';
        $afterQuestions      = $this->questionMd->findAfterQuestionByQuestionId($newestQuestionId, $userid);
        foreach ($afterQuestions as $afterQuestion) {
            $curQuestion     = $afterQuestion['question'];
            $curReplyContent = $afterQuestion['reply_content'];
            $messages[]      = ["role" => "user", "content" => $curQuestion];
            $messages[]      = ["role" => "assistant", "content" => $curReplyContent];

            $questionsString     .= $curQuestion;
            $replyContentsString .= $curReplyContent;
        }
        $messages[]      = ["role" => "user", "content" => $newQuestion];
        $questionsString .= $newQuestion;
        return [
            'messages'              => $messages,
            'questions_string'      => $questionsString,
            'reply_contents_string' => $replyContentsString,
        ];
    }
}
