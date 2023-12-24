<?php

namespace Dto;

class UserDto {
    private $orderId;
    private $openId;

    private $balanceToken;

    /**
     * @return mixed
     */
    public function getBalanceToken() {
        return $this->balanceToken;
    }

    /**
     * @param mixed $balanceToken
     */
    public function setBalanceToken($balanceToken): void {
        $this->balanceToken = $balanceToken;
    }

    /**
     * @return mixed
     */
    public function getOrderId() {
        return $this->orderId;
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
    public function getOpenId() {
        return $this->openId;
    }

    /**
     * @param mixed $openId
     */
    public function setOpenId($openId): void {
        $this->openId = $openId;
    }
}
