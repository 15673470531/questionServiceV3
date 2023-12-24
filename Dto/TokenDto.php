<?php

namespace Dto;

class TokenDto extends Dto {
    private $userid;
    private $token;

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
    public function getToken() {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void {
        $this->token = $token;
    }
}
