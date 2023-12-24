<?php

namespace Service;

use Model\VersionModel;
use Throwable;
use Utils\ApiException;

class VersionService {
    private $versionMd;
    public function __construct()
    {
        $this->versionMd = new VersionModel();
    }

    public function getUpdateRecords(): array
    {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            $records = $this->versionMd->findAllRecords();
            foreach ($records as &$record) {
                $record['created'] = $this->voRecordCreated($record);
            }
            $data['list'] = $records;
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
        }
        log_d(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        return [$ok, $msg, $data];

    }

    private function voRecordCreated($record)
    {
        switch ($record['type']){
            case 1:
                $record['created'] = date(FORMAT_DATE, strtotime($record['created']));
                break;
            case 2:
                $record['created'] = '即将上线';
                break;
        }
        return $record['created'];
    }
}
