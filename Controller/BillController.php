<?php

namespace Controller;

use Dto\Request;
use Service\BillService;
use Service\UserService;

class BillController extends Controller {
    private $billService;
    public function __construct() {
        $this->billService = new BillService();
    }

    public function listBill(Request $request): array {
        $billService = new BillService();
        return $billService->listBill($request);
    }

    public function balance(Request $request): array {
        $userService = new UserService();
        return $userService->balance($request);
    }
}
