<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Http\Requests\User\{CreateUserRequest, DelUserRequest, EditUserRequest, ListUserRequest};
use App\Http\Services\User\UserService;

class UserController extends Controller {
    public function create(CreateUserRequest $request) {
        return UserService::create($request->validated());
    }

    public function find(Request $request) {
        return UserService::find($request->route('id'));
    }

    public function edit(EditUserRequest $request) {
        return UserService::edit($request->route('id'), $request->validated());
    }

    public function del(DelUserRequest $request) {
        return UserService::del($request->route('id'));
    }

    public function list(ListUserRequest $request) {
        return UserService::list();
    }
}
