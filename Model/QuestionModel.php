<?php

namespace Model;

use Dto\QuestionDto;

class QuestionModel extends Model {
    private $tableName = 'q_question';

    public function __construct() {
        $this->table = $this->tableName;
        parent::__construct();
    }

    public function create(QuestionDto $questionDto) {
        $orderId      = $questionDto->getOrderId();
        $question     = $questionDto->getQuestion();
        $replyContent = $questionDto->getReplyContent();
        $userid       = $questionDto->getUserid();

        $spentTokens = $questionDto->getTotalSpentTokens();

//        $replyContent = "'php+html怎么实现接口的流试输出,就是那种不是一次性返回数据,而是一点一点返回,前端也是一点点渲染','2023-09-26 15:14:10','1','要实现接口的流式输出，可以使用 PHP 的 flush() 函数将数据立即发送给浏览器，然后使用 ob_flush() 函数刷新输出缓冲区。同时，你还需要设置响应头，确保浏览器按照流的方式处理响应。\n\n下面是一个简单的示例代码，演示如何实现接口的流式输出：\n\nphp\n<?php\nob_end_clean(); \/\/ 清空缓冲区并关闭缓冲\n\n\/\/ 设置响应头，确保浏览器按照流的方式处理响应\nheader('Content-Type: text\/html; charset=utf-8');\nheader('Transfer-Encoding: chunked');\nheader('Connection: keep-alive');\n\nfunction sendChunk($data) {\n echo dechex(strlen($data)), \"\\r\\n\"; \/\/ 发送本次数据的字节数\n echo $data, \"\\r\\n\"; \/\/ 发送数据\n ob_flush(); \/\/ 刷新输出缓冲区\n flush(); \/\/ 将输出发送给浏览器\n}\n\n\/\/ 模拟一次性返回大量数据的情况\n$data = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';\nfor ($i = 0; $i < 10; $i++) {\n sendChunk($data); \/\/ 发送一部分数据\n sleep(1); \/\/ 模拟处理时间\n}\n\n\n在前端，你可以使用 JavaScript 来处理接收到的数据，并一点一点地渲染到页面上。可以使用 fetch 或 XMLHttpRequest 对象来获取接口数据，然后使用 DOM 操作方法将数据添加到页面元素中。\n\n以下是一个简单的示例代码，演示如何使用 JavaScript 渲染流式输出的数据";
        $createdTime = date(FORMAT_DATETIME);
        $sql         = sprintf('insert into %s (`%s`,`%s`,`%s`,`%s`,`%s`,`title_spent`,`content_spent`,`spent_tokens`,`commit_version`) values (\'%s\', \'%s\',\'%s\',\'%s\',\'%s\',%s,%s,%s,%s)',
            $this->tableName, 'order_id', 'question', 'created_time', 'created_uid', 'reply_content', $orderId, htmlspecialchars($question), $createdTime, $userid, htmlspecialchars($replyContent),
            $questionDto->getTitleSpentTokens(), $questionDto->getContentSpentTokens(), $spentTokens, $questionDto->getCommitVersion()
        );

        log_d(__METHOD__, sprintf('创建问题,sql: %s', $sql));
        $createdRes = $this->dbConn->query($sql);
        if ($createdRes) {
            return $questionDto;
        } else {
            return false;
        }
    }

    public function findAfterQuestionByQuestionId(int $newestQuestionId, int $userid): array
    {
        $sql       = sprintf('select * from %s where `delete` = 0 and `created_uid` = %s and id > %s order by id asc', $this->tableName, $userid, $newestQuestionId);
        return $this->select($sql);
    }

    public function findNewestQuestion(int $userid){
        $sql       = sprintf('select * from %s where `delete` = 0 and `created_uid` = %s order by id desc', $this->tableName, $userid);
        return $this->find($sql);
    }

    public function findLeastQuestions(int $userid, int $limit = 2): array {
        $time      = date(FORMAT_DATE);
        $timeStart = $time . ' 00:00:00';
        $timeEnd   = $time . ' 23:59:59';
//        $sql       = sprintf('select * from %s where created_time >= \'%s\' and created_time <= \'%s\'  and `delete` = 0 and `created_uid` = %s order by id asc limit %s', $this->tableName, $timeStart, $timeEnd, $userid, $limit);
        $sql       = sprintf('select * from %s where created_time >= \'%s\' and created_time <= \'%s\'  and `delete` = 0 and `created_uid` = %s', $this->tableName, $timeStart, $timeEnd, $userid);
        return $this->select($sql);
    }

    public function findAllSummaryQuestions(int $userid): array {
        if (isMyself($userid)){
            $sql = sprintf('select * from %s where `delete` = 0 order by id desc', $this->tableName);
        }else{
            $sql = sprintf('select * from %s where `delete` = 0 and `created_uid` = %s order by id desc', $this->tableName, $userid);
        }
        return $this->select($sql);
    }

    public function list(int $userid, $time, $num): array {
        if ($userid) {
            $sql = sprintf('select * from %s where created_uid = %s and delete = 0 order by id desc', $this->tableName, $userid);
        } else {
            if ($time) {
                $timeStart = $time . ' 00:00:00';
                $timeEnd   = $time . ' 23:59:59';
                $sql       = sprintf('select * from %s where created_time >= \'%s\' and created_time <= \'%s\'  and `delete` = 0', $this->tableName, $timeStart, $timeEnd);
            } elseif ($num) {
                $sql = sprintf('select * from %s  where `delete` = %s order by id desc limit %s', $this->tableName, 0, $num);
            } else {
                $sql = sprintf('select * from %s  where `delete` = %s order by id asc', $this->tableName, 0);
            }
        }
//        varDumpExit($sql);
        $list = [];
        $res  = $this->dbConn->query($sql);
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $list[] = $row;
            }
        }
        return $list;
    }

    public function update() {

    }

    public function get(int $questionId): array {
        $sql  = sprintf('select * from %s where id = %s', $this->tableName, $questionId);
        $list = [];
        $res  = $this->dbConn->query($sql);
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $list[] = $row;
            }
        }
        return $list;
    }
}
