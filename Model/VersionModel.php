<?php

namespace Model;

class VersionModel extends Model {
    private string $tableName = 'q_version';

    public function findAllRecords(): array
    {
        $sql = sprintf('select * from %s order by id desc', $this->tableName);
        return $this->select($sql);
    }
}
