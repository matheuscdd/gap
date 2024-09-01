<?php

namespace App\Http\Controllers;

use App\Http\Requests\Budget\{
    CreateBudgetRequest, EditBudgetRequest};
use App\Http\Requests\Request;
use App\Http\Services\Budget\BudgetService;

class BudgetController extends Controller {
    public function create(CreateBudgetRequest $request) {
        return BudgetService::create($request->validated());
    }

    public function find(Request $request) {
        return BudgetService::find($requeskt->route('id'));
    }

    public function list(Request $request) {
        return BudgetService::list();
    }

    public function edit(EditBudgetRequest $request) {
        return BudgetService::edit($request->route('id'), $request->validated());
    }

}
