<?php
namespace Dto;
use Service\AiService;

class QuestionDto extends Dto{
    private $orderId;
    private $question;
    private $userid;
    private $replyContent;
    private $time;
    private $spentTokens;
    private $titleSpent;
    private $contentSpent;
    private $commitVersion;
    private $questionString;
    private $replyContentString;

    /**
     * @return mixed
     */
    public function getCommitVersion() {
        return $this->commitVersion ?? 1;
    }

    /**
     * @param mixed $commitVersion
     */
    public function setCommitVersion($commitVersion): void {
        $this->commitVersion = $commitVersion;
    }

    /**
     * @return mixed
     */
    public function getTitleSpent() {
        return $this->titleSpent;
    }

    /**
     * @param mixed $titleSpent
     */
    public function setTitleSpent($titleSpent): void {
        $this->titleSpent = $titleSpent;
    }

    /**
     * @return mixed
     */
    public function getContentSpent() {
        return $this->contentSpent;
    }

    /**
     * @param mixed $contentSpent
     */
    public function setContentSpent($contentSpent): void {
        $this->contentSpent = $contentSpent;
    }

    /**
     * @return mixed
     */
    public function getSpentTokens() {
        return $this->spentTokens;
    }

    /**
     * @param mixed $spentTokens
     */
    public function setSpentTokens($spentTokens): void {
        $this->spentTokens = $spentTokens;
    }

    /**
     * @return mixed
     */
    public function getTime() {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time): void {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getReplyContent() {
        return htmlspecialchars_decode($this->replyContent);
    }

    /**
     * @param mixed $replyContent
     */
    public function setReplyContent($replyContent): void {
        $this->replyContent = htmlspecialchars($replyContent);
    }

    /**
     * @return mixed
     */
    public function getUserid() {
        return $this->userid;
    }

    /**
     * @param mixed $userid
     */
    public function setUserid($userid): void {
        $this->userid = $userid;
    }

    /**
     * @return mixed
     */
    public function getOrderId() {
        return $this->orderId ?? 0;
    }

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId): void {
        $this->orderId = $orderId;
    }

    /**
     * @return mixed
     */
    public function getQuestion() {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question): void {
        $this->question = $question;
    }

    public function getTitleSpentTokens(){
        return AiService::spentToken($this->questionString ?? $this->getQuestion());
    }

    public function getContentSpentTokens(){
        return AiService::spentToken($this->replyContentString ?? $this->getReplyContent());
    }

    public function getTotalSpentTokens(){
        return $this->getTitleSpentTokens() + $this->getContentSpentTokens();
    }

    public function setQuestionString($questionsString)
    {
        $this->questionString = $questionsString;
    }

    public function setReplyContentString($replyContentsString)
    {
        $this->replyContentString = $replyContentsString;
    }
}
