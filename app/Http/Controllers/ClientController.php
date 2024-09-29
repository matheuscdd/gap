<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\{ClientRequest, CreateClientRequest, EditClientRequest};
use App\Http\Services\Client\ClientService;

class ClientController extends Controller {
    public function create(CreateClientRequest $request) {
        return ClientService::create($request->validated());
    }

    public function edit(EditClientRequest $request) {
        return ClientService::edit($request->route('id'), $request->validated());
    }

    public function find(ClientRequest $request) {
        return ClientService::find($request->route('id'));
    }

    public function list(ClientRequest $request) {
        return ClientService::list();
    }
}
