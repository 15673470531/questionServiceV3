<?php

namespace Dto;

class BillDto extends Dto {
    private $type;
    private $token;
    private $userId;
    private $money;
    private $remark;
    private $balance;

    /**
     * @return mixed
     */
    public function getBalance() {
        return $this->balance ?? 0;
    }

    /**
     * @param mixed $balance
     */
    public function setBalance($balance): void {
        $this->balance = $balance;
    }

    /**
     * @return mixed
     */
    public function getRemark() {
        return $this->remark;
    }

    /**
     * @param mixed $remark
     */
    public function setRemark($remark): void {
        $this->remark = $remark;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getMoney() {
        return $this->money;
    }

    /**
     * @param mixed $money
     */
    public function setMoney($money): void {
        $this->money = $money;
    }
}
