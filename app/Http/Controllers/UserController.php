<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Services\CreateUserService;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function create(CreateUserRequest $request) {
        $createUserService = new CreateUserService();
        return $createUserService->execute($request->all());
    }
}
