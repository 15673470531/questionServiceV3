<?php

namespace Controller;

use Dto\Request;
use Model\CodeModel;
use Model\TokenModel;
use Service\LoginService;
use Service\TokenService;
use Service\UserService;

class UserController extends Controller {
    private UserService $userService;
    public function __construct() {
        $this->userService = new UserService();
    }

    public function recharge(Request $request): array {
        $userid = $request->getUserId();
        $money = $request->getParam('money',0);
        return $this->userService->recharge($userid, $money);
    }

    public function isCanCommitQuestion(Request $request): array {
        $userid = $request->getUserId();

//        $userid = 12;
        $question = $request->getParam('question','');

        $randomId = $request->getParam('random_id',0);

        $newestQuestionId = $request->getParam('newest_question_id',0);
        return $this->userService->isCanCommitQuestion($userid,$question, $randomId, $newestQuestionId);
    }
}
