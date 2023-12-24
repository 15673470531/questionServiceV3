<?php

namespace Service;

use Model\HelpModel;

class HelpService extends BaseService {
    public function listHelp(){
        $helpMd = new HelpModel();
        $cond = [
        ];
        $list = $helpMd->selectByConditions($cond);
        return [true,'success', $list];
    }
}
