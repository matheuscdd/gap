<?php

namespace App\Http\Controllers;

use App\Http\Requests\Delivery\{
    CreateDeliveryRequest, EditDeliveryRequest};
use App\Http\Requests\Request;
use App\Http\Services\Delivery\DeliveryService;

class DeliveryController extends Controller {
    public function createFull(CreateDeliveryRequest $request) {
        return DeliveryService::createFull($request->validated());
    }

    public function findFull(Request $request) {
        return DeliveryService::findFull($request->route('id'));
    }

    public function listFull(Request $request) {
        return DeliveryService::listFull();
    }

    public function editFull(EditDeliveryRequest $request) {
        return DeliveryService::editFull($request->route('id'), $request->validated());
    }

}
