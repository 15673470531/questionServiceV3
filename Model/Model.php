<?php

namespace Model;
use Utils\Utils;

class Model {
    protected $dbConn;
    protected $table;
    protected $order;

    /**
     * @return mixed
     */
    public function getOrder() {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order): void {
        $this->order = $order;
    }

    public function __construct() {
        $this->dbConn = Utils::getDbCond();
    }

    private static function isNotEqual(string $field): bool {
        if (strstr($field,' ') !== false){
            list($field, $term) = explode(' ', $field);
            if ($field && $term){
                return true;
            }
        }
        return false;
    }

    private static function formatToSql(array $conditions) {
        $sql = '';
        foreach ($conditions as $field => $value) {
            if (self::isNotEqual($field)){
                $conditionSql = sprintf('%s%s', $field, $value);
                $conditionSql .= ' and ';
                $sql .= $conditionSql;
            }else{
                $conditionSql = sprintf('%s%s%s%s%s', $field,' ','=',' ', $value);
                $conditionSql .= ' and ';
                $sql .= $conditionSql;
            }

        }
        $sql = rtrim($sql,'and ');
        return $sql;
    }

    public function find($sql) {
        $rows = $this->dbConn->query($sql);
        $data = [];
        while ($row = $rows->fetch_assoc()){
            $data[] = $row;
        }
        return $data[0] ?? [];
    }

    protected function select($sql): array {
        $rows = $this->dbConn->query($sql);
        $data = [];
        while ($row = $rows->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }

    public function startTrans(){
        //设置mysql不自动提交,必须手动用commit提交
        $this->dbConn->query('SET AUTOCOMMIT=0');
    }

    public function commitTrans(){
        $this->dbConn->query('COMMIT');
        $this->stopTrans();
    }

    public function rollbackTrans(){
        $this->dbConn->query('ROLLBACK');
        $this->stopTrans();
    }

    public function stopTrans(){
        $this->dbConn->query('SET AUTOCOMMIT=1');
    }

    public function updateById(int $id, array $array) {
        $field = key($array);
        $value = $array[$field];
        $sql = sprintf('update %s set `%s` = %s where `id` = %s', $this->table, $field, $value, $id);
        log_sql(__METHOD__,$sql);
        return $this->dbConn->query($sql);
    }

    public function findById($id): array {
        $sql = sprintf('select * from %s where `id` = %s', $this->table, $id);
        return $this->find($sql);
    }

    public function selectByConditions(array $cond){
        $conditionSql = self::formatToSql($cond);
        $sql = sprintf('select * from %s',$this->table);

        if ($this->order){
            $order = implode(',', $this->order);
            $sql .= ' order by ' . $order;
        }
        if ($conditionSql){
            $sql .= ' where ' . $conditionSql;
        }
        return $this->select($sql);
    }
}
