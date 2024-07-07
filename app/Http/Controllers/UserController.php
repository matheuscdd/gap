<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\{CreateUserRequest, EditUserRequest, GetUserRequest};
use App\Services\User\{GetUserService, CreateUserService, EditUserService};

class UserController extends Controller {
    public function create(CreateUserRequest $request) {
        $createUserService = new CreateUserService();
        return $createUserService->execute($request->all());
    }

    public function get(GetUserRequest $request) {
        $getUserService = new GetUserService();
        return $getUserService->execute($request->route('id'));
    }

    public function edit(EditUserRequest $request) {
        $editUserService = new EditUserService();
        return $editUserService->execute($request->route('id'), $request->validated());
    }
}
