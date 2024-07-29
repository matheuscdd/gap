<?php

namespace App\Http\Services\Client;

use App\Models\Client;

class ClientService {
    public static function create(array $data) {
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;
        return Client::create($data);
    }

    public static function find(int $id) {
        return Client::find($id);
    }

    public static function edit(int $id, array $data) {
        $data['updated_by'] = auth()->user()->id;
        $client = Client::find($id);
        $client->update($data);
        return $client;
    }

    public static function list() {
        return Client::all();
    }
}
