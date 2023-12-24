<?php

namespace Controller\OfficialAccount;

use Controller\Controller;
use Service\WxService;

class WxController extends Controller {
    private $wxService;
    public function __construct() {
        $this->wxService = new WxService();
    }

    public function getAccessToken(): array {
        return $this->wxService->getAccessToken();
    }

    public function customMenu(){
        return $this->wxService->customMenu();
    }
}
