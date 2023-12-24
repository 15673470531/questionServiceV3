<?php

namespace Dto;

class Request {
    private $params;

    private $jsonBody;

    private $userId;

    /**
     * @return mixed
     */
    public function getUserId() {
        return intval($this->userId);
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
    public function getJsonBody() {
        return $this->jsonBody;
    }



    /**
     * @param mixed $jsonBody
     */
    public function setJsonBody($jsonBody): void {
        $this->jsonBody = $jsonBody;
    }



    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function getParam($key, $default = null){
        $val = $this->params[$key] ?? $default;
        if (isset($default)){
            if (is_numeric($default)){
                $val = intval($val);
            }elseif (is_string($default)){
                $val = strval($val);
            }
        }
        return $val;
    }

    public function __construct(array $params) {
        $this->params = $params;
    }

    public function getJsonParams(){
        return json_encode($this->params,JSON_UNESCAPED_UNICODE);
    }
}
