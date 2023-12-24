<?php

namespace Service;

use Dto\BillDto;
use Dto\Request;
use Model\BillModel;
use Throwable;
use Utils\ApiException;
use Utils\Utils;

class BillService extends BaseService {
    private $billMd;

    public function __construct() {
        $this->billMd = new BillModel();
    }

    private static function getRealType($type) {
        if ($type == 1){
            return '+';
        }
        return '-';
    }

    public function recordBilling(BillDto $billDto): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            if ($billDto->getType() === null){
                throw new ApiException('未设置账单类型');
            }
            $recordBillRes = $this->billMd->create($billDto);
            if (empty($recordBillRes)) {
                log_important(__METHOD__, sprintf('记录流水失败,billDto: %s', _j($billDto)));
                throw new ApiException('记录账单流水失败');
            }
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

    public function listBill(Request $request): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            $userid = $request->getUserId();
            $billMd = new BillModel();
            $listBill = $billMd->findAllByUserid($userid);
            $data = $this->voListBill($listBill);
        } catch (ApiException $e) {
            $ok  = false;
            $msg = $e->getMessage();
        } catch (Throwable $e) {
            $ok       = false;
            $msg      = ApiException::getErrorDesc($e);
            $errorMsg = $e->getMessage();
            log_e(__METHOD__, sprintf('params:%s, ok:%s, msg:%s, data:%s, errorMsg:%s', _j(func_get_args()), $ok, $msg, _j($data), $errorMsg ?? ''));
        }
        return [$ok, $msg, $data];
    }

    private function voListBill(array $listBill): array {
        foreach ($listBill as &$item) {
            $item['tokens'] = sprintf('%s%s',self::getRealType($item['type']), $item['token']);
        }
        unset($item);
        return $listBill;
    }
}
