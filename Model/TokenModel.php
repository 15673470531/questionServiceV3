<?php

namespace Model;

use Dto\TokenDto;

class TokenModel extends Model {
    private string $tableName = 'q_token';
    public function create(TokenDto $tokenDto){
        $createdTime = date(FORMAT_DATETIME);
        $sql = sprintf("insert into q_token (`user_id`, `access_token`,`created_time`) values ('%s', '%s', '%s')", $tokenDto->getUserid(), $tokenDto->getToken(), $createdTime);
        return $this->dbConn->query($sql);
    }

    public function findByToken($token): ?array {
        $sql = sprintf("select * from q_token where access_token = '%s' order by id desc", $token);
        $rows = $this->dbConn->query($sql);
        return $rows->fetch_assoc();
    }

    public function checkingToken($token){

    }
}
