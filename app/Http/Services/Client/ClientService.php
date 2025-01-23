<?php

namespace App\Http\Services\Client;
use App\Constraints\ClientKeysConstraints as Keys;
use App\Exceptions\AppError;
use App\Models\{Client, Budget, Delivery};
use DateTime;

class ClientService {
    public static function create(array $data) {
        $data[Keys::CREATED_BY] = auth()->user()->id;
        $data[Keys::UPDATED_BY] = auth()->user()->id;
        return response(Client::create($data), 201);
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

    public static function del(int $id) {
        $client = Client::find($id);

        $maxMinTime = 30;
        $diff = intval(((new DateTime())->getTimestamp() - $client->created_at->getTimestamp()) / 60);
        if ($diff > $maxMinTime) {
            throw new AppError("O tempo máximo para realizar a deleção após a criação é de $maxMinTime minutos", 423);
        }

        $deliveries = Delivery::where('client', $id)->get();
        $budgets = Budget::where('client', $id)->get();
        if (count($deliveries) || count($budgets)) {
            throw new AppError("Esse cliente já está em uso no sistema", 409);
        }
                    
        $client->delete();
   
        return response(null, 204);
    }
}
