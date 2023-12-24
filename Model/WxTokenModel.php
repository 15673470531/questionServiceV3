<?php

namespace Model;

class WxTokenModel extends Model {

    public function create($accessToken) {
        $datetime = date(FORMAT_DATETIME);
        $sql      = sprintf('insert into q_wx_token (`access_token`,`created_time`) value (\'%s\',\'%s\')', $accessToken, $datetime);
        return $this->dbConn->query($sql);
    }

    public function findLatestToken(): array {
        $sql = sprintf('select * from q_wx_token order by id desc limit 1');
        return $this->select($sql)[0] ?? [];
    }
}
