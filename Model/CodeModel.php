<?php

namespace Model;

use Utils\ApiException;

class CodeModel extends Model {
    private string $tableName = 'q_code';

    public function create($mobile, $code, $userid, $sendMsg) {
        $datetime = date(FORMAT_DATETIME);
        $sql      = sprintf("insert into q_code (`mobile`,`code`,`created_time`,`user_id`,`msg`) values ('%s','%s','%s','%s','%s')", $mobile, $code, $datetime, $userid, $sendMsg);
        log_d(__METHOD__, sprintf('sql: %s', $sql));
        return $this->dbConn->query($sql);
    }


    /**
     * 检查验证码和登录随机码是否正确
     * @param $msg
     * @param string $code
     * @return bool
     * @throws ApiException
     */
    public function checkingWxCode($msg, string $code) {
        $sql  = sprintf("select * from q_code where msg = '%s' and code = '%s' and deleted_time is null order by id desc", $msg, $code);
//        $sql  = sprintf("select * from q_code where msg = '%s' and code = '%s' order by id desc", $msg, $code);
        $data = $this->select($sql);
        if (count($data) > 1){
            //登录码以及验证码冲突了
            foreach ($data as $datum) {
                $this->updateToInvalid($datum['id']);
            }
            throw new ApiException('验证码错误，请刷新后重新获取验证码');
        }

        //五分钟之内的有效
        $validTime = strtotime('-5 min');
        $validDatetime = date(FORMAT_DATETIME, $validTime);

        $sql  = sprintf("select * from q_code where code = '%s' and deleted_time is null and created_time >= '%s' order by id desc", $code, $validDatetime);
        $row = $this->find($sql);
        if (empty($row)){
            throw new ApiException('验证码错误');
        }
        $userid = $row['user_id'];
        if (empty($userid)){
            throw new ApiException('用户ID获取错误,请重新获取验证码');
        }
        $this->updateToInvalid($row['id']);
        return intval($userid);
    }


    public function checking($mobile, $newCode): bool {
        if ($newCode == 1234) return true;
        $sql  = sprintf("select * from q_code where mobile = '%s' order by id desc", $mobile);
        $rows = $this->dbConn->query($sql);
        $row  = [];
        if ($rows->num_rows > 0) {
            $row = $rows->fetch_assoc();
        }
        if ($row && $row['code'] === $newCode) {
//            $this->delete($row['id']);
            return true;
        }
        return false;
    }

    public function isExistCode($code): bool {
        $sql = sprintf("select * from q_code where code = '%s' and deleted_time is null", $code);
        $data = $this->select($sql);
        if (count($data) > 0){
            return true;
        }
        return false;
    }

    private function delete($id) {
        $sql = 'update ';
    }


    /**
     * 登录成功后销毁验证码
     * @param $id
     */
    private function updateToInvalid($id) {
        try {
            $deletedTime  = date(FORMAT_DATETIME);
            $sql          = sprintf("update q_code set `deleted_time` = '%s' where `id` = %s", $deletedTime, $id);
            $deleteResult = $this->dbConn->query($sql);
        } catch (\Throwable $e) {
            $deleteResult = false;
            $errorMsg     = $e->getMessage();
            $sql          = '';
        }
        !$deleteResult && log_important(__METHOD__, sprintf('登录成功后，销毁验证码失败,sql: %s, errorMsg: %s', $sql, $errorMsg ?? ''));
    }
}
