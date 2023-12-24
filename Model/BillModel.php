<?php

namespace Model;

use Dto\BillDto;

class BillModel extends Model {

    public function create(BillDto $billDto) {
        $createdTime = date(FORMAT_DATETIME);
        $sql = sprintf(
            'insert into q_bill (`type`,`token`,`user_id`,`money`,`remark`,`created_time`,`balance`) values (%s, \'%s\', %s,%s,\'%s\',\'%s\',%s)',
            $billDto->getType(),$billDto->getToken(),$billDto->getUserId(),$billDto->getMoney() ?? 0, $billDto->getRemark() ?? '',$createdTime,$billDto->getBalance()
        );
        log_d(__METHOD__, $sql);
        return $this->dbConn->query($sql);
    }

    public function findAllByUserid($userid): array {
        $sql = sprintf('select * from q_bill where user_id = %s order by id desc', $userid);
        return $this->select($sql);
    }
}
