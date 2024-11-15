<?php

namespace App\Http\Services\Delivery;

use App\Constraints\DeliveryKeysConstraints as Keys;
use App\Exceptions\AppError;
use App\Models\Delivery;
use App\Models\DeliveryStock;
use App\Models\Stock;
use Illuminate\Support\Facades\Log;

class DeliveryService {
    public static function createFull(array $data) {
        $data[Keys::CREATED_BY] = auth()->user()->id;
        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $data[Keys::DELIVERY_DATE] = date(Keys::DATE_FORMAT, strtotime($data[Keys::DELIVERY_DATE]));
        if (array_key_exists(Keys::PAYMENT_DATE, $data)) {
            $data[Keys::PAYMENT_DATE] = date(Keys::DATE_FORMAT, strtotime($data[Keys::PAYMENT_DATE]));
        }

        $delivery = Delivery::create($data);
        $stocks = self::insertStocks($delivery, $data[Keys::STOCKS]);
        return self::retrieve($delivery->id, $delivery, $stocks);
    }

    public static function createPartial(int $ref, array $data) {
        $parent = self::retrieve($ref);

        if ($parent->finished) {
            throw new AppError('Entregas finalizadas não podem ser receber parciais', 403);
        }

        $savedPartialStocks = self::retrievePartialsStocks($ref);
        $data[Keys::CLIENT] = $parent[Keys::CLIENT];
        $data[Keys::CREATED_BY] = auth()->user()->id;
        $data[Keys::UPDATED_BY] = auth()->user()->id;

        # TODO - agrupar os nomes com tipo no front

        $valid = self::validatePartialStocksExists($parent[Keys::STOCKS], $data[Keys::STOCKS]);
        if (!$valid) {
            throw new AppError('Estoque não encontrado', 404);
        }

        $valid = self::validatePartialStocks($parent[Keys::STOCKS], $data[Keys::STOCKS], $savedPartialStocks);
        if (!$valid) {
            throw new AppError('Entregas parciais não podem ter um estoque maior que a original', 409);
        }
        
        $delivery = Delivery::create($data);
        $delivery->ref = $ref;
        $delivery->save();
        $stocks = self::insertStocks($delivery, $data[Keys::STOCKS]);
        return self::retrieve($delivery->id, $delivery, $stocks);
    }

    public static function editFull(int $id, array $data) {
        $hasPartials = Delivery::where(Keys::REF, $id)->count();
        if ($hasPartials) {
            throw new AppError('Entregas que já possuem parciais não podem ser editadas', 403);
        }
        $delivery = Delivery::find($id);
        if ($delivery->ref) {
            throw new AppError('Entregas parciais não podem ser editadas', 403);
        }

        if ($delivery->finished) {
            throw new AppError('Entregas finalizadas não podem ser editadas', 403);
        }

        ['stocks' => $stocks, 'deliveryStocks' => $deliveryStocks] = self::getStocks($id);
        $stocksIds = [];
        $deliveryStocksIds = [];
        
        foreach ($stocks as $stock) {
            $stocksIds[] = $stock['id'];
        }

        foreach ($deliveryStocks as $budgetStock) {
            $deliveryStocksIds[] = $budgetStock->id;
        }

        DeliveryStock::whereIn('id', $deliveryStocksIds)->delete();
        Stock::whereIn('id', $stocksIds)->delete();

        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $delivery->update($data);
        $stocks = self::insertStocks($delivery, $data[Keys::STOCKS]);
        return self::retrieve($delivery->id, $delivery, $stocks);
    }

    public static function findFull(int $id) {
        $delivery = Delivery::find($id);
        if ($delivery->ref) {
            throw new AppError('Entregas parciais não podem ser retornadas nesse endpoint', 403);
        }
        return self::retrieve($id, $delivery);
    }

    public static function listFull() {
        $deliveriesRaw = Delivery::where(Keys::REF, null);
        $deliveriesHandle = [];
        foreach ($deliveriesRaw as $delivery) {
            $deliveriesHandle[] = self::retrieve($delivery->id, $delivery);
        }
        return $deliveriesHandle;
    }

    public static function listPartial(int $ref) {
        $rawPartials = Delivery::where(Keys::REF, $ref);
        if (!$rawPartials->count()) {
            throw new AppError('Não foram encontradas entregas parciais para essa entrega', 404);
        }
        $handlePartials = [];
        foreach ($rawPartials->get() as $delivery) {
            $handlePartials[] = self::retrieve($delivery->id, $delivery);
        }
        return $handlePartials;
    }

    public static function finishFull(int $id) {
        $delivery = Delivery::find($id);
        if ($delivery->ref) {
            throw new AppError('Entregas parciais não podem ser finalizadas nesse endpoint', 403);
        }
        $delivery->finished = true;
        $delivery->save();

        $partials = Delivery::where(Keys::REF, $id)->get();
        foreach($partials as $partial) {
            $partial->finished = true;
            $partial->save();
        }
        return response()->noContent();
    }

    public static function finishPartial(int $id) {
        $delivery = Delivery::find($id);
        if (!$delivery->ref) {
            throw new AppError('Entregas completas não podem ser finalizadas nesse endpoint', 403);
        }
        $delivery->finished = true;
        $delivery->save();

        return response()->noContent();
    }

    private static function validatePartialStocks(array $originalStocks, array $unsavedPartialStocks, array $savedPartialStocks = []) {
        foreach($originalStocks as &$ref) {
            foreach($savedPartialStocks as $el) {
                if ($el['type'] !== $ref['type'] || $el['name'] !== $ref['name']) {
                    continue;
                }
                $ref['weight'] -= $el['weight'];
                $ref['quantity'] -= $el['quantity'];
            }
        }
        unset($ref);

        foreach($originalStocks as &$ref) {
            foreach($unsavedPartialStocks as $el) {
                if ($el['type'] !== $ref['type'] || $el['name'] !== $ref['name']) {
                    continue;
                }
                $ref['weight'] -= $el['weight'];
                $ref['quantity'] -= $el['quantity'];
            }
        }
        unset($ref);

        foreach($originalStocks as $ref) {
            if ($ref['weight'] < 0 || $ref['quantity'] < 0) {
                return false;
            }
        }
        return true;
    }

    private static function validatePartialStocksExists(array $originalStocks, array $unsavedPartialStocks) {
        $refKeys = [];
        foreach($originalStocks as $ref) {
            $refKeys[] = $ref['type'] . $ref['name'];
        }
        foreach($unsavedPartialStocks as $el) {
            $exists = in_array($el['type'] . $el['name'], $refKeys);
            if (!$exists) {
                return false;
            }
        } 
        return true;
    }

    private static function insertStocks(Delivery $delivery, array $stocksRaw) {
        $stocksHandle = [];

        foreach ($stocksRaw as $el) {
            $stock = Stock::create($el);
            $stocksHandle[] = $stock;
        }
        
        foreach ($stocksHandle as $stock) {
            DeliveryStock::create([
                Keys::DELIVERY => $delivery->id,
                Keys::STOCK => $stock->id
            ]);
        }
        return $stocksHandle;
    }

    private static function getStocks(int $id) {
        $deliveryStocks = DeliveryStock::where(Keys::DELIVERY, $id)->get();
        $stocksIds = [];
        foreach ($deliveryStocks as $el) {
            $stocksIds[] = $el->stock;
        }

        return [
            'stocks' => Stock::whereIn('id', $stocksIds)->get()->toArray(),
            'deliveryStocks' => $deliveryStocks
        ];
    }

    private static function retrieve(int $id, Delivery $delivery = null, array $stocks = null) {
        $stocks = $stocks ?? self::getStocks($id)['stocks'];
        $delivery = $delivery ?? Delivery::find($id);
        $response = json_decode(json_encode($delivery), true);
        $response[Keys::STOCKS] = $stocks;
        return $response;
    }

    private static function retrievePartialsStocks(int $id) {
        $partialsIds = Delivery::where(Keys::REF, $id)->get()->pluck('id');
        $deliveryStocksIds = DeliveryStock::whereIn(Keys::DELIVERY, $partialsIds)->get()->pluck(Keys::STOCK);
        $partialStocks = Stock::whereIn('id', $deliveryStocksIds)->get()->toArray();
        return $partialStocks;    
    }
}
