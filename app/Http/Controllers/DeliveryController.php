<?php

namespace App\Http\Controllers;

use App\Http\Requests\Delivery\{
    CreateDeliveryRequest, EditDeliveryRequest};
use App\Http\Requests\Request;
use App\Http\Services\Delivery\DeliveryService;

class DeliveryController extends Controller {
    public function create(CreateDeliveryRequest $request) {
        return DeliveryService::create($request->validated());
    }

    public function find(Request $request) {
        return DeliveryService::find($request->route('id'));
    }

    public function list(Request $request) {
        return DeliveryService::list();
    }

    public function edit(EditDeliveryRequest $request) {
        return DeliveryService::edit($request->route('id'), $request->validated());
    }

}
