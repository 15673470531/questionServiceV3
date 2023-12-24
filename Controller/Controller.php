<?php

namespace Controller;

class Controller {
    public function success($data = [], $ok = true, $msg = 'success'): array {
        return [$ok, $msg, $data];
    }

    public function fail($msg = 'fail',$ok = false, $data = []): array {
        return [$ok, $msg, $data];
    }
}
