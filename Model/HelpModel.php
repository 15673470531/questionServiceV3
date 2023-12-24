<?php

namespace Model;

class HelpModel extends Model {

    private $tableName = 'q_help';

    public function __construct() {
        $this->table = $this->tableName;
        parent::__construct();
    }

    public function create($userid, $content) {
        $date = date('Y-m-d H:i:s');
        $sql = sprintf('insert into %s (`%s`,`%s`,`%s`) values (\'%s\', \'%s\',\'%s\')',$this->tableName,'user_id','content','created_time',$userid,$content,$date);
        return $this->dbConn->query($sql);
    }
}
