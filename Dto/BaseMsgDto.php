<?php

namespace Dto;

class BaseMsgDto extends Dto {
    private $toUserName;
    private $fromUserName;
    private $createTime;
    private $msgType;
    private $content;
    private $msgId;

    /**
     * @return mixed
     */
    public function getToUserName() {
        return $this->toUserName;
    }

    /**
     * @param mixed $toUserName
     */
    public function setToUserName($toUserName): void {
        $this->toUserName = $toUserName;
    }

    /**
     * @return mixed
     */
    public function getFromUserName() {
        return trim($this->fromUserName);
    }

    /**
     * @param mixed $fromUserName
     */
    public function setFromUserName($fromUserName): void {
        $this->fromUserName = $fromUserName;
    }

    /**
     * @return mixed
     */
    public function getCreateTime() {
        return $this->createTime;
    }

    /**
     * @param mixed $createTime
     */
    public function setCreateTime($createTime): void {
        $this->createTime = $createTime;
    }

    /**
     * @return mixed
     */
    public function getMsgType() {
        return $this->msgType;
    }

    /**
     * @param mixed $msgType
     */
    public function setMsgType($msgType): void {
        $this->msgType = $msgType;
    }

    /**
     * @return mixed
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getMsgId() {
        return $this->msgId;
    }

    /**
     * @param mixed $msgId
     */
    public function setMsgId($msgId): void {
        $this->msgId = $msgId;
    }
}
