<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Http\Requests\Driver\{CreateDriverRequest, EditDriverRequest};
use App\Http\Services\Driver\DriverService;

class DriverController extends Controller {
    public function create(CreateDriverRequest $request) {
        return DriverService::create($request->validated());
    }

    public function edit(EditDriverRequest $request) {
        return DriverService::edit($request->route('id'), $request->validated());
    }

    public function find(Request $request) {
        return DriverService::find($request->route('id'));
    }

    public function del(Request $request) {
        return DriverService::del($request->route('id'));
    }

    public function list(Request $request) {
        return DriverService::list();
    }
}
