<?php

namespace App\Http\Controllers;

use App\Http\Requests\Delivery\{
    CreateDeliveryFullRequest, CreateDeliveryPartialRequest, EditDeliveryFullRequest};
use App\Http\Requests\Request;
use App\Http\Services\Delivery\DeliveryService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller {
    public function createFull(CreateDeliveryFullRequest $request) {
        return DeliveryService::createFull($request->validated());
    }

    public function createPartial(CreateDeliveryPartialRequest $request) {
        return DeliveryService::createPartial($request->route('id'), $request->validated());
    }

    public function findFull(Request $request) {
        return DeliveryService::findFull($request->route('id'));
    }

    public function listFull(Request $request) {
        return DeliveryService::listFull();
    }

    public function listPartial(Request $request) {
        return DeliveryService::listPartial($request->route('id'));
    }

    public function editFull(EditDeliveryFullRequest $request) {
        return DeliveryService::editFull($request->route('id'), $request->validated());
    }

    public function finishFull(Request $request) {
        return DeliveryService::finishFull($request->route('id'));
    }

    public function finishPartial(Request $request) {
        return DeliveryService::finishPartial($request->route('id'));
    }

    public function treemap(Request $request) {
        return DeliveryService::treemap();
    }

    public function calendar(Request $request) {
        return DeliveryService::calendar();
    }
}
