<?php

namespace Controller;

use Dto\Request;
use Model\HelpModel;
use Service\HelpService;

class HelpController extends Controller {
    private $helpMd;
    public function __construct() {
        $this->helpMd = new HelpModel();
    }

    public function create(Request $request): array {
        $userid = $request->getUserId();
        $content = $request->getParam('content','');

        $res = $this->helpMd->create($userid, $content);
        if ($res){
            return $this->success();
        }else{
            return $this->fail('创建失败');
        }
    }

    public function listHelp(Request $request){
        $helpService = new HelpService();
        return $helpService->listHelp();
    }
}
