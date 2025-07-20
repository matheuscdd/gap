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
use App\Utils\Utils;
use DateTime;
use App\Constraints\ValidatorConstraints as Schema;

class DeliveryService {
    public static function createFull(array $data) {
        $data[Keys::CREATED_BY] = auth()->user()->id;
        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $data[Keys::DELIVERY_DATE] = date(Schema::DATE_SCHEMA, strtotime($data[Keys::DELIVERY_DATE]));
        if ($data[Keys::UNLOADED] === Unloaded::CLIENT) {
            $data[Keys::UNLOADING_COST] = 0;
        }
        if (array_key_exists(Keys::PAYMENT_DATE, $data)) {
            $data[Keys::PAYMENT_DATE] = date(Schema::DATE_SCHEMA, strtotime($data[Keys::PAYMENT_DATE]));
        }

        $data[Keys::STOCKS] = Utils::groupStocks($data[Keys::STOCKS]);
        $delivery = Delivery::create($data);
        $stocks = self::insertStocks($delivery, $data[Keys::STOCKS]);
        return response(self::retrieve($delivery->id, $delivery, $stocks), 201);
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
        $data[Keys::STOCKS] = Utils::groupStocks($data[Keys::STOCKS]);

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
        return response(self::retrieve($delivery->id, $delivery, $stocks), 201);
    }

    public static function editFull(int $id, array $data) {
        $hasPartials = Delivery::where(Keys::REF, '=', $id)->count();
        if ($hasPartials) {
            throw new AppError('Entregas que já possuem parciais não podem ser editadas', 403);
        }
        $delivery = Delivery::find($id);
        if ($delivery->ref) {
            throw new AppError('Entregas parciais não podem ser editadas', 403);
        }

        if ($delivery->received) {
            throw new AppError('Entregas que já foram recebidas não podem ser editadas', 403);
        }

        if ($delivery->finished) {
            throw new AppError('Entregas finalizadas não podem ser editadas', 403);
        }

        if ($data[Keys::UNLOADED] === Unloaded::CLIENT) {
            $data[Keys::UNLOADING_COST] = 0;
        }

        $data[Keys::STOCKS] = Utils::groupStocks($data[Keys::STOCKS]);
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
        if (!$deliveryInst->finished) {
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
        $rawPartials = Delivery::where(Keys::REF, '=', $ref);
        if (!$rawPartials->count()) {
            throw new AppError('Não foram encontradas entregas parciais para essa entrega', 404);
        }
        $handlePartials = [];
        foreach ($rawPartials->get() as $delivery) {
            $handlePartials[] = self::retrieve($delivery->id, $delivery);
        }
        return $handlePartials;
    }
    
    public static function receiveFull(int $id) {
        $delivery = Delivery::find($id);
        if ($delivery->received) {
            throw new AppError('Essa entrega já foi recebida', 409);
        }

        if ($delivery->finished) {
            throw new AppError('Entregas finalizadas não podem ser recebidas, pois já foram entregues', 403);
        }

        if ($delivery->ref) {
            throw new AppError('Entregas parciais não podem ser finalizadas nesse endpoint', 403);
        }
        $delivery->received = true;
        $delivery->updated_by = auth()->user()->id;
        $delivery->save();

        $partials = Delivery::where(Keys::REF, '=', $id)->get();
        foreach($partials as $partial) {
            $partial->received = true;
            $partial->updated_by = auth()->user()->id;
            $partial->save();
        }
        return response()->noContent();
    }

    public static function finishFull(int $id) {
        $delivery = Delivery::find($id);
        if (!$delivery->received) {
            throw new AppError('Entregas que não foram recebidas não poder sem entregas', 403);
        }

        if ($delivery->finished) {
            throw new AppError('Essa entrega já foi finalizada', 409);
        }

        if ($delivery->ref) {
            throw new AppError('Entregas parciais não podem ser finalizadas nesse endpoint', 403);
        }
        $delivery->finished = true;
        $delivery->updated_by = auth()->user()->id;
        $delivery->save();

        $partials = Delivery::where(Keys::REF, '=', $id)->get();
        foreach($partials as $partial) {
            $partial->finished = true;
            $partial->updated_by = auth()->user()->id;
            $partial->save();
        }
        return response()->noContent();
    }

    public static function finishPartial(int $id) {
        $delivery = Delivery::find($id);
        if (!$delivery->ref) {
            throw new AppError('Entregas completas não podem ser finalizadas nesse endpoint', 403);
        }

        if ($delivery->finished) {
            throw new AppError('Essa entrega já foi finalizada', 409);
        }


        $delivery->finished = true;
        $delivery->updated_by = auth()->user()->id;
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
        $deliveryStocks = DeliveryStock::where(Keys::DELIVERY, '=', $id)->get();
        $stocksIds = [];
        foreach ($deliveryStocks as $el) {
            $stocksIds[] = $el->stock;
        }

        return [
            'stocks' => Stock::whereIn('id', $stocksIds)->orderBy('id', 'asc')->get()->toArray(),
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

    public static function chartsTreemap($request) {
        $stocks = DB::table('stocks')
            ->join('deliveries_stocks', 'deliveries_stocks.stock', '=', 'stocks.id')
            ->join('deliveries', 'deliveries.id', '=', 'deliveries_stocks.delivery')
            ->join('stock_type', 'stock_type.id', '=', 'stocks.type')
            ->where('deliveries.received', '=', true)
            ->whereNotNull('deliveries.ref')
            ->orWhere(function ($query) {
                $query->whereNull('deliveries.ref')
                      ->where('deliveries.finished', '=', false)
                      ->where('deliveries.received', '=', true);
            })
            ->select(
                'deliveries.id as delivery_id', 
                'deliveries.ref as delivery_ref', 
                'deliveries.finished as delivery_finished',
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
            $delivery['partials'] = json_decode(json_encode($delivery['partials']), true);
            $delivery['available'] = self::validatePartialStocksValues($ref, $delivery['partials'])['availableStocks'];
            $partialUnfinished = array_filter($delivery['partials'], fn($el) => !$el['delivery_finished']);
            $delivery['stocks'] = Utils::groupStocks(array_merge($delivery['available'], $partialUnfinished));
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
            foreach($delivery['stocks'] as $stock) {
                [
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
                $ids[] = base64_encode(random_bytes(32));
                $parents[] = $identifier;
            }
        }
        $data = [
            'type' => 'treemap',
            'labels' => $labels,
            'parents' => $parents,
            'ids' => $ids,
            'marker' => [
                'cornerradius' => 3,
            ],
        ];

        return [$data];
    }

    public static function chartsScatter($request) {
        if(!$request->has('start_date') || !$request->has('end_date')) {
            throw new AppError('[start_date] e [end_date] são requeridos', 400);
        }

        $startDate = date(Schema::DATE_SCHEMA, strtotime($request->query('start_date')));
        $endDate = date(Schema::DATE_SCHEMA, strtotime($request->query('end_date')));
        $deliveries = Delivery
            ::where(Keys::FINISHED, '=', true)
            ->where(Keys::REF, null)
            ->where(Keys::DELIVERY_DATE, '>=', $startDate)
            ->where(Keys::DELIVERY_DATE, '<=', $endDate)
            ->orderBy(Keys::DELIVERY_DATE, 'asc')
            ->get();

        $hovertemplate = '(%{x}, R$ %{y}, %{text})';
        $type = 'lines+markers';

        if (!count($deliveries)) return [];

        $revenues = [
            'name' => 'Faturamento',
            'x' => [],
            'y' => [],
            'text' => [],
            'hovertemplate' => $hovertemplate,
            'type' => $type,
            'line' => [
                'color' => '#35E56C'
            ]
        ];
        $travelCosts = [
            'name' => 'Custos de Viagem',
            'x' => [],
            'y' => [],
            'text' => [],
            'hovertemplate' => $hovertemplate,
            'type' => $type,
            'line' => [
                'color' => '#EC001E'
            ]
        ];
        $unloadingCosts = [
            'name' => 'Custos de Descarga',
            'x' => [],
            'y' => [],
            'text' => [],
            'hovertemplate' => $hovertemplate,
            'type' => $type,
            'line' => [
                'color' => '#EE784D'
            ]
        ];

        $keys = [];

        
        foreach($deliveries as $el) {
            $deliveryDate = date('d/m/Y', strtotime($el->delivery_date));

            if (!array_key_exists($deliveryDate, $keys)) {
                $keys[$deliveryDate] = [];
            }
            $keys[$deliveryDate][] = $el;
        }

        foreach($keys as $deliveryDate => $arr) {
            $text = 'Entregas ';
            $y = [
                'revenue' => 0,
                'travel_cost' => 0,
                'unloading_cost' => 0,
            ];
            
            foreach($arr as $el) {
                $text .= $el->id . ',';
                $y['revenue'] += $el->revenue;
                $y['travel_cost'] += $el->travel_cost;
                $y['unloading_cost'] += $el->unloading_cost;
            }

            $text = substr($text, 0, -1);
            $revenues['y'][] = $y['revenue'];
            $revenues['x'][] = $deliveryDate;
            $revenues['text'][] = $text;
            $travelCosts['x'][] = $deliveryDate;
            $travelCosts['y'][] = $y['travel_cost'];
            $travelCosts['text'][] = $text;
            $unloadingCosts['x'][] = $deliveryDate;
            $unloadingCosts['y'][] = $y['unloading_cost'];
            $unloadingCosts['text'][] = $text;
        }

        return response()->json([$revenues, $travelCosts, $unloadingCosts], 200, [], JSON_UNESCAPED_SLASHES);
    }

    public static function calendar($request) {
        $year = date('Y');
        $filename = "/tmp/holidays_$year.json";
        $holidays = [];

        if (file_exists($filename)) {
            $holidays = json_decode(file_get_contents($filename), true);
        } else {
            $url = "https://brasilapi.com.br/api/feriados/v1/$year";
            $holidays = json_decode(file_get_contents($url), true);
            $holidays[] = [
                'date' => "$year-12-08",
                'name' => 'Imaculada Conceição',
                'type' => 'municipal'
            ];
            $holidays[] = [
                'date' => "$year-06-13",
                'name' => 'Santo Antonio',
                'type' => 'municipal'
            ];
            $holidays[] = [
                'date' => "$year-07-09",
                'name' => 'Revolução de 1932',
                'type' => 'state'
            ];

            $opts = [
                'national' => "Nacional",
                'state' => "Estadual",
                'municipal' => "Municipal",
            ];

            foreach($holidays as &$el) {
                $el['description'] = 'Feriado ' . $opts[$el['type']];
            }
            unset($el);

            $file = fopen($filename, 'w');
            fwrite($file, json_encode($holidays));
            fclose($file);
        }

        $rawDeliveries = Delivery::all();
        $handleDeliveries = [];

        foreach ($rawDeliveries as $el) {
            $id = $el->id;
            $parcial = $el->ref ? 'Parcial' : 'Completa';
            $unloaded = $el->unloaded === 'client' ? 'pelo cliente' : 'pela transportadora';
            $status = $el->finished ? 'finalizada' : 'aberta';
            
            $handleDeliveries[] = [
                'date' => $el->delivery_date,
                'name' => "Entrega $parcial $id",
                'type' => 'delivery',
                'description' => $el->delivery_address . ' - ' . $el->driver . ' - ' . "Descarga $unloaded ($status)",
                'redirectId' => $el->ref ?? $el->id,
                'isPartial' => is_null($el->ref),
                'finished' => $el->finished,
            ];
        }

        return [
            'holidays' => $holidays,
            'deliveries' => $handleDeliveries,
        ];
    }

    public static function delFull(int $id) {
        $delivery = Delivery::find($id);

        if (!is_null($delivery->ref)) {
            throw new AppError('Entregas parciais não podem ser deletadas nesse endpoint', 403);
        }

        if ($delivery->received) {
            throw new AppError('Entregas já recebidas não podem ser removidas', 403);
        }

        if ($delivery->finished) {
            throw new AppError('Entregas finalizadas não podem ser removidas', 403);
        }
        
        $maxMinTime = 30;
        $diff = intval(((new DateTime())->getTimestamp() - $delivery->created_at->getTimestamp()) / 60);
        if ($diff > $maxMinTime) {
            throw new AppError("O tempo máximo para realizar a deleção após a criação é de $maxMinTime minutos", 423);
        }

        $partials = Delivery::where(Keys::REF, '=', $id)->get();
        if (count($partials)) {
            throw new AppError('Entregas com parciais associadas não podem ser deletadas', 409);
        }

        $delivery->delete();
        return response(null, 204);
    }

    public static function delPartial(int $id) {
        $delivery = Delivery::find($id);

        if (is_null($delivery->ref)) {
            throw new AppError('Entregas completas não podem ser deletadas nesse endpoint', 403);
        }

        if ($delivery->finished) {
            throw new AppError('Entregas finalizadas não podem ser editadas', 403);
        }
        
        $delivery->delete();
        return response(null, 204);
    }
}