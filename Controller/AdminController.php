<?php

namespace Controller;

use Dto\Request;
use Service\UserService;

class AdminController extends Controller {
    public function listUsers(Request $request): array {
        $userService = new UserService();
        return $userService->listUsers();
    }
}
