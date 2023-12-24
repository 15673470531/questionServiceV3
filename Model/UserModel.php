<?php

namespace Model;

use Dto\UserDto;

class UserModel extends Model {
    private string $tableName = 'q_user';

    public function __construct() {
        $this->table = 'q_user';
        parent::__construct();
    }

    //注册新用户
    public function create(UserDto $userDto) {
        $createdTime = date(FORMAT_DATETIME);
        $sql         = sprintf('insert into %s (`order_id`,`open_id`,`created_time`,`balance_token`) values (\'%s\', \'%s\',\'%s\',\'%s\')',
            $this->tableName, $userDto->getOrderId(), $userDto->getOpenId(), $createdTime, $userDto->getBalanceToken() ?? 0
        );
        log_sql(__METHOD__, $sql);
        if ($this->dbConn->query($sql)) {
            $data = $this->findByOpenId($userDto->getOpenId());
        }
        return $data['id'] ?? 0;
    }

    public function findByOpenId($openId): ?array {
        $sql  = sprintf('select * from %s where open_id = \'%s\'', $this->tableName, $openId);
        $rows = $this->dbConn->query($sql);
        $data = [];
        while ($row = $rows->fetch_assoc()) {
            $data = $row;
        }
        return $data;
    }

    public function findByUserId($userid): ?array {
        $sql  = sprintf('select * from %s where id = %s', $this->tableName, $userid);
        $rows = $this->dbConn->query($sql);
        return $rows->fetch_assoc();
    }

    public function findByPassword(string $password): ?array {
        $sql = sprintf('select * from %s where password = \'%s\'', $this->tableName, $password);
        return $this->find($sql);
    }

    public function reduceBalanceToken($userid, $spentTokens) {
        $sql = sprintf('update q_user set `balance_token` = `balance_token` - %s where `id` = %s', $spentTokens, $userid);
        log_sql(__METHOD__, $sql);
        return $this->dbConn->query($sql);
    }

    public function rechargeBalanceToken($userid, $newTokens) {
        $sql = sprintf('update q_user set `balance_token` = `balance_token` + %s where `id` = %s', $newTokens, $userid);
        log_sql(__METHOD__, $sql);
        return $this->dbConn->query($sql);
    }

    public function isExistPassword(string $password) {
    }

    public function incrLoginTimes(int $userid) {
        $sql = sprintf('update q_user set `login_times` = `login_times` + %s where `id` = %s', 1, $userid);
        log_sql(__METHOD__, $sql);
        return $this->dbConn->query($sql);
    }

    public function findAllNeedExpireTokenUsers(): array
    {
        $expireDatetime = date(FORMAT_DATETIME,strtotime('- 1 month'));
        $sql = sprintf('select * from %s where recharge_time < \'%s\' and balance_token > 0', $this->tableName, $expireDatetime);
        return $this->select($sql);
    }
}
