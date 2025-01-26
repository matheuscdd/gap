<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Http\Requests\Truck\{CreateTruckRequest, EditTruckRequest};
use App\Http\Services\Truck\TruckService;

class TruckController extends Controller {
    public function create(CreateTruckRequest $request) {
        return TruckService::create($request->validated());
    }

    public function edit(EditTruckRequest $request) {
        return TruckService::edit($request->route('id'), $request->validated());
    }

    public function find(Request $request) {
        return TruckService::find($request->route('id'));
    }

    public function del(Request $request) {
        return TruckService::del($request->route('id'));
    }

    public function list(Request $request) {
        return TruckService::list();
    }
}
