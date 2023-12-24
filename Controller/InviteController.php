<?php

namespace Controller;

use Dto\Request;
use Service\InviteService;

class InviteController extends Controller {
    private $inviteService;
    public function __construct() {
        $this->inviteService = new InviteService();
    }

    public function makeInviteUrl(Request $request): array {
        return $this->inviteService->makeInviteUrl(intval($request->getUserId()));
    }
}
