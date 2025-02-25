<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Http\Requests\Maintenance\{CreateMaintenanceRequest, EditMaintenanceRequest};
use App\Http\Services\Maintenance\MaintenanceService;

class MaintenanceController extends Controller {
    public function create(CreateMaintenanceRequest $request) {
        return MaintenanceService::create($request->validated());
    }

    public function edit(EditMaintenanceRequest $request) {
        return MaintenanceService::edit($request->route('id'), $request->validated());
    }

    public function find(Request $request) {
        return MaintenanceService::find($request->route('id'));
    }

    public function del(Request $request) {
        return MaintenanceService::del($request->route('id'));
    }

    public function list(Request $request) {
        return MaintenanceService::list();
    }

    public function chartsScatter(Request $request) {
        return MaintenanceService::chartsScatter($request);
    }
}
