<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\{CreateUserRequest, EditUserRequest, GetUserRequest, UserRequest};
use App\Services\User\{GetUserService, CreateUserService, DelUserService, EditUserService};

class UserController extends Controller {
    public function create(CreateUserRequest $request) {
        $createUserService = new CreateUserService();
        return $createUserService->execute($request->validated());
    }

    public function get(UserRequest $request) {
        $getUserService = new GetUserService();
        return $getUserService->execute($request->route('id'));
    }

    public function edit(EditUserRequest $request) {
        $editUserService = new EditUserService();
        return $editUserService->execute($request->route('id'), $request->validated());
    }

    public function del(UserRequest $request) {
        $delUserRequest= new DelUserService();
        return $delUserRequest->execute($request->route('id'));
    }
}
