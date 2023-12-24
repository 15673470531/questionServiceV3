<?php

namespace Model;

class AnswerModel extends Model {
    private $tableName = 'q_answer';

    public function create($userid,$questionId, $replyContent) {
        $date = date('Y-m-d H:i:s');
        $sql = sprintf('insert into %s (`%s`,`%s`,`%s`) values (\'%s\', \'%s\',\'%s\')',$this->tableName,'userid','content','created_time',$userid,$content,$date);
        return $this->dbConn->query($sql);
    }
}
