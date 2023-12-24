<?php

namespace Controller;

use Constants\RechargeTokenConstant;
use Dto\Request;
use Service\UserService;

class BalanceController extends Controller {
    private $userService;
    public function __construct() {
        $this->userService = new UserService();
    }

    public function recharge(Request $request): array {
        $opUserid = $request->getUserId();
        $money = $request->getParam('amount',0);
        $userid = $request->getParam('user_id',0);
        return $this->userService->recharge($opUserid,$userid, $money);
    }

    public function rechargeRules(Request $request): array {
        $moneyTokenMap = RechargeTokenConstant::getMoneyTokenMap();

        $rules = [];
        foreach ($moneyTokenMap as $money=>$item) {
            $temp['money'] = $money . '元';
            $temp['data'] = $item;
            $rules[] = $temp;
        }
        return [true,'success', $rules];
    }
}
