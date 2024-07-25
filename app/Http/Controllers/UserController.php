<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Http\Requests\User\{CreateUserRequest, EditUserRequest, GetUserRequest, UserRequest};
use App\Http\Services\User\UserService;

class UserController extends Controller {
    public function create(CreateUserRequest $request) {
        $userService = new UserService();
        return $userService->create($request->validated());
    }

    public function get(Request $request) {
        $userService = new UserService();
        return $userService->get($request->route('id'));
    }

    public function edit(EditUserRequest $request) {
        $userService = new UserService();
        return $userService->edit($request->route('id'), $request->validated());
    }

    public function del(Request $request) {
        $userService = new UserService();
        return $userService->del($request->route('id'));
    }
}
