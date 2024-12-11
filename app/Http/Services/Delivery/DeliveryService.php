<?php

namespace App\Http\Services\Delivery;

use App\Constraints\DeliveryKeysConstraints as Keys;
use App\Exceptions\AppError;
use App\Models\Delivery;
use App\Models\DeliveryStock;
use App\Models\Stock;
use Illuminate\Support\Facades\Log;
use App\Enums\Unloaded;
use Illuminate\Support\Facades\DB;

class DeliveryService {
    public static function createFull(array $data) {
        $data[Keys::CREATED_BY] = auth()->user()->id;
        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $data[Keys::DELIVERY_DATE] = date(Keys::DATE_FORMAT, strtotime($data[Keys::DELIVERY_DATE]));
        if ($data[Keys::UNLOADED] === Unloaded::CLIENT) {
            $data[Keys::UNLOADING_COST] = 0;
        }
        if (array_key_exists(Keys::PAYMENT_DATE, $data)) {
            $data[Keys::PAYMENT_DATE] = date(Keys::DATE_FORMAT, strtotime($data[Keys::PAYMENT_DATE]));
        }

        $delivery = Delivery::create($data);
        $stocks = self::insertStocks($delivery, $data[Keys::STOCKS]);
        return self::retrieve($delivery->id, $delivery, $stocks);
    }

    public static function createPartial(int $ref, array $data) {
        $parent = self::retrieve($ref);

        if ($parent[Keys::FINISHED]) {
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

        $valid = self::validatePartialStocksValues($parent[Keys::STOCKS], $savedPartialStocks, $data[Keys::STOCKS])['valid'];
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

        if ($data[Keys::UNLOADED] === Unloaded::CLIENT) {
            $data[Keys::UNLOADING_COST] = 0;
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
        $deliveryInst = Delivery::find($id);
        if ($deliveryInst->ref) {
            throw new AppError('Entregas parciais não podem ser retornadas nesse endpoint', 403);
        }
        
        $deliveryData = self::retrieve($id, $deliveryInst);
        $partials = self::retrievePartialsStocks($id);
        if (count($partials) && !$deliveryInst->finished) {
            $deliveryData['available'] = self::validatePartialStocksValues($deliveryData[Keys::STOCKS], $partials)['availableStocks'];
        }
        return $deliveryData;
    }

    public static function listFull() {
        $sql = "
            SELECT 
                dc.*,
                (
                    SELECT SUM(s.quantity) 
                    FROM stocks s 
                    JOIN deliveries_stocks ds ON ds.stock = s.id 
                    WHERE ds.delivery = dc.id
                ) AS total,
                (
                CASE
                	WHEN dc.finished = TRUE 
                	THEN (
	                    SELECT SUM(s.quantity) 
	                    FROM stocks s 
	                    JOIN deliveries_stocks ds ON ds.stock = s.id 
	                    WHERE ds.delivery = dc.id
                	) ELSE (
                        SELECT COALESCE(SUM(s.quantity), 0)
                        FROM stocks s 
                        JOIN deliveries_stocks ds ON ds.stock = s.id 
                        JOIN deliveries dp ON dp.ref = dc.id 
                        WHERE ds.delivery = dp.id AND dp.finished = true
                	)
                END) AS completed,           
                (
                    SELECT SUM(dp.id) IS NOT NULL 
                    FROM deliveries dp 
                    WHERE dp.ref = dc.id 
                ) AS has_partials
            FROM deliveries dc
            WHERE dc.ref IS NULL";
        $deliveries = DB::select($sql);
        foreach ($deliveries as &$delivery) {
            $delivery->travel_cost = (float) $delivery->travel_cost;
            $delivery->revenue = (float) $delivery->revenue;
            $delivery->unloading_cost = (float) $delivery->unloading_cost;
        }
        unset($delivery);

        return $deliveries;
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

    private static function validatePartialStocksValues(array $rawOriginalStocks, array $savedPartialStocks, array $unsavedPartialStocks = []) {
        $handleOriginalStocks = [...$rawOriginalStocks];
        
        foreach($handleOriginalStocks as &$ref) {
            foreach($savedPartialStocks as $el) {
                if ($el['type'] !== $ref['type'] || $el['name'] !== $ref['name']) {
                    continue;
                }
                $ref['weight'] -= $el['weight'];
                $ref['quantity'] -= $el['quantity'];
            }
        }
        unset($ref);

        foreach($handleOriginalStocks as &$ref) {
            foreach($unsavedPartialStocks as $el) {
                if ($el['type'] !== $ref['type'] || $el['name'] !== $ref['name']) {
                    continue;
                }
                $ref['weight'] -= $el['weight'];
                $ref['quantity'] -= $el['quantity'];
            }
        }
        unset($ref);

        foreach($handleOriginalStocks as $ref) {
            if ($ref['weight'] < 0 || $ref['quantity'] < 0) {
                return false;
            }
        }
        return [
            'valid' => true,
            'availableStocks' => $handleOriginalStocks,
        ];
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
        $partialStocks = DB::table('stocks')
            ->join('deliveries_stocks', 'deliveries_stocks.stock', '=', 'stocks.id')
            ->join('deliveries', 'deliveries.id', '=', 'deliveries_stocks.delivery')
            ->where('deliveries.ref', '=', $id)
            ->select('stocks.*')
            ->get()
            ->toArray();
        return json_decode(json_encode($partialStocks), true);
    }

    public static function treemap() {
        $stocks = DB::table('stocks')
            ->join('deliveries_stocks', 'deliveries_stocks.stock', '=', 'stocks.id')
            ->join('deliveries', 'deliveries.id', '=', 'deliveries_stocks.delivery')
            ->join('stock_type', 'stock_type.id', '=', 'stocks.type')
            ->whereNotNull('deliveries.ref')
            ->orWhere(function ($query) {
                $query->whereNull('deliveries.ref')
                      ->where('deliveries.finished', '=', false);
            })
            ->select(
                'deliveries.id as delivery_id', 
                'deliveries.ref as delivery_ref', 
                'stocks.*', 
                'stock_type.name as type_name',
            )
            ->get()
            ->toArray();

        $deliveries = [];
        foreach ($stocks as $stock) {
            if (is_null($stock->delivery_ref) && !array_key_exists($stock->delivery_id, $deliveries)) {
                $deliveries[$stock->delivery_id] = [
                    'ref' => [$stock],
                    'partials' => []
                ];
            } else if (is_null($stock->delivery_ref)) {
                $deliveries[$stock->delivery_id]['ref'][] = $stock;
            }
        }

        foreach ($stocks as $stock) {
            if (is_null($stock->delivery_ref) || !array_key_exists($stock->delivery_ref, $deliveries)) {
                continue;
            }

            $deliveries[$stock->delivery_ref]['partials'][] = $stock;
        }


        foreach ($deliveries as &$delivery) {
            $ref = json_decode(json_encode($delivery['ref']), true);
            $partials = json_decode(json_encode($delivery['partials']), true);
            $delivery['available'] = self::validatePartialStocksValues($ref, $partials)['availableStocks'];
        }
        unset($delivery);

        define('BASE', 'Estoque');
        $labels = [BASE];
        $ids = [BASE];
        $parents = [''];
        foreach ($deliveries as $id => $delivery) {
            $identifier = "Entrega $id";
            $labels[] = $identifier;
            $ids[] = $identifier;
            $parents[] = BASE;
            foreach($delivery['available'] as $stock) {
                [
                    'id' => $id,
                    'weight' => $weight,
                    'quantity' => $quantity,
                    'name' => $name,
                    'extra' => $extra,
                    'type_name' => $type
                ] = $stock;
                if ($quantity === 0) continue;
                $label = "[$quantity] $name - $type ($weight kg)";
                if (!is_null($extra)) {
                    $label .= " ($extra)";
                }
                $labels[] = $label;
                $ids[] = $id;
                $parents[] = $identifier;
            }
        }
        $data = [
            'type' => 'treemap',
            'labels' => $labels,
            'parents' => $parents,
            'ids' => $ids,
        ];

        return [$data];
    }
}