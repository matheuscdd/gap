<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Http\Requests\StockType\StockTypeRequest;
use App\Http\Services\StockType\StockTypeService;

class StockTypeController extends Controller {
    public function create(StockTypeRequest $request) {
        return StockTypeService::create($request->validated());
    }

    public function edit(StockTypeRequest $request) {
        return StockTypeService::edit($request->route('id'), $request->validated());
    }

    public function list(Request $request) {
        return StockTypeService::list();
    }
}
