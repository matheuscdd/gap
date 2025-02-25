<?php

namespace App\Http\Services\StockType;

use App\Models\StockType;

class StockTypeService {
    public static function create(array $data) {
        return StockType::create($data);
    }

    public static function edit(int $id, array $data) {
        $stockType = StockType::find($id);
        $stockType->update($data);
        return response($stockType, 201);
    }

    public static function list() {
        return StockType::all();
    }
}
