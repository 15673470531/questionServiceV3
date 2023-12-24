<?php

namespace Service;

use Throwable;
use Utils\ApiException;

class InviteService extends BaseService {
    public function makeInviteUrl(int $userid): array {
        $ok   = true;
        $msg  = ApiException::getSuccessDesc();
        $data = [];
        try {
            $url = sprintf('http://82.156.139.209/questionService/View/wxLogin2.php?invite=%s',$userid);
            $data['invite_url'] = $url;
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
}
