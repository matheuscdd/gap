<?php

namespace App\Http\Services\Budget;

use App\Constraints\BudgetKeysConstraints as Keys;
use App\Models\Budget;
use App\Models\BudgetStock;
use App\Models\Stock;
use DateTime;
use Illuminate\Support\Facades\Log;

class BudgetService {
    public static function create(array $data) {
        $data[Keys::CREATED_BY] = auth()->user()->id;
        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $data[Keys::PAYMENT_DATE] = date(Keys::DATE_FORMAT, strtotime($data[Keys::PAYMENT_DATE]));
        $data[Keys::DELIVERY_DATE] = date(Keys::DATE_FORMAT, strtotime($data[Keys::DELIVERY_DATE]));

        $budget = Budget::create($data);
        $stocks = self::insertStocks($budget, $data[Keys::STOCKS]);
        return self::retrieve($budget->id, $budget, $stocks);
    }

    public static function edit(int $id, array $data) {
        ['stocks' => $stocks, 'budgetStocks' => $budgetStocks] = self::getStocks($id);
        $stocksIds = [];
        $budgetStocksIds = [];
        
        foreach ($stocks as $stock) {
            $stocksIds[] = $stock->id;
        }

        foreach ($budgetStocks as $budgetStock) {
            $budgetStocksIds[] = $budgetStock->id;
        }

        BudgetStock::whereIn('id', $budgetStocksIds)->delete();
        Stock::whereIn('id', $stocksIds)->delete();

        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $budget = Budget::find($id);
        $budget->update($data);
        $stocks = self::insertStocks($budget, $data[Keys::STOCKS]);
        return self::retrieve($budget->id, $budget, $stocks);
    }

    public static function find(int $id) {
        return self::retrieve($id);
    }

    public static function list() {
        $budgetsRaw = Budget::all();
        $budgetsHandle = [];
        foreach ($budgetsRaw as $budget) {
            $budgetsHandle[] = self::retrieve($budget->id, $budget);
        }
        return $budgetsHandle;
    }

    private static function insertStocks(Budget $budget, array $stocksRaw) {
        $stocksHandle = [];

        foreach ($stocksRaw as $el) {
            $stock = Stock::create($el);
            $stocksHandle[] = $stock;
        }

        foreach ($stocksHandle as $stock) {
            BudgetStock::create([
                Keys::BUDGET => $budget->id,
                Keys::STOCK => $stock->id
            ]);
        }
        return $stocksHandle;
    }

    private static function getStocks(int $id) {
        $budgetStocks = BudgetStock::where(Keys::BUDGET, $id)->get();
        $stocksIds = [];
        foreach ($budgetStocks as $el) {
            $stocksIds[] = $el->stock;
        }
        return [
            'stocks' => Stock::whereIn('id', $stocksIds)->get(),
            'budgetStocks' => $budgetStocks
        ];
    }

    private static function retrieve(int $id, Budget $budget = null, array $stocks = null) {
        $stocks = $stocks ?? self::getStocks($id)['stocks'];
        $budget = $budget ?? Budget::find($id);
        $response = json_decode(json_encode($budget), true);
        $response[Keys::STOCKS] = $stocks;
        return $response;
    }
}
