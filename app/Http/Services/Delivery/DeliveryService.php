<?php

namespace App\Http\Services\Delivery;

use App\Constraints\DeliveryKeysConstraints as Keys;
use App\Models\Delivery;
use App\Models\DeliveryStock;
use App\Models\Stock;
use Illuminate\Support\Facades\Log;

class DeliveryService {
    public static function create(array $data) {
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

    public static function edit(int $id, array $data) {
        ['stocks' => $stocks, 'deliveryStocks' => $deliveryStocks] = self::getStocks($id);
        $stocksIds = [];
        $deliveryStocksIds = [];
        
        foreach ($stocks as $stock) {
            $stocksIds[] = $stock->id;
        }

        foreach ($deliveryStocks as $budgetStock) {
            $deliveryStocksIds[] = $budgetStock->id;
        }

        DeliveryStock::whereIn('id', $deliveryStocksIds)->delete();
        Stock::whereIn('id', $stocksIds)->delete();

        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $delivery = Delivery::find($id);
        $delivery->update($data);
        $stocks = self::insertStocks($delivery, $data[Keys::STOCKS]);
        return self::retrieve($delivery->id, $delivery, $stocks);
    }

    public static function find(int $id) {
        return self::retrieve($id);
    }

    public static function list() {
        $deliveriesRaw = Delivery::all();
        $deliveriesHandle = [];
        foreach ($deliveriesRaw as $delivery) {
            $deliveriesHandle[] = self::retrieve($delivery->id, $delivery);
        }
        return $deliveriesHandle;
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
            'stocks' => Stock::whereIn('id', $stocksIds)->get(),
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
}
