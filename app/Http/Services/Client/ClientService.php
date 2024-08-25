<?php

namespace App\Http\Services\Client;
use App\Constraints\ClientKeysConstraints as Keys;
use App\Models\Client;

class ClientService {
    public static function create(array $data) {
        $data[Keys::CREATED_BY] = auth()->user()->id;
        $data[Keys::UPDATED_BY] = auth()->user()->id;
        return Client::create($data);
    }

    public static function find(int $id) {
        return Client::find($id);
    }

    public static function edit(int $id, array $data) {
        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $client = Client::find($id);
        $client->update($data);
        return $client;
    }

    public static function list() {
        return Client::all();
    }
}
