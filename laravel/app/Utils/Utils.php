<?php

namespace App\Utils;

use App\Constraints\StocksKeysConstraints as StockKeys;

class Utils {
    public static function arrayFlat(array $rawestArray): array {
        $res = [];
        $isCommonArray = array_values($rawestArray) === $rawestArray;
        foreach ($rawestArray as $key => $value) {
            $combo = is_array($value) ? $value : [$key => $value];
            if ($isCommonArray) {
                $res = array_merge($res, $combo);
            } else {
                $res += $combo;
            }
        }
        return $res;
    }

    public static function groupStocks(array $stocks): array {
        $obj = [];
        $sep = base64_encode(random_bytes(32));
        foreach ($stocks as $stock) {
            $key = $stock[StockKeys::NAME].$sep.$stock[StockKeys::TYPE];
            if (array_key_exists($key, $obj)) {
                $obj[$key][StockKeys::WEIGHT] += $stock[StockKeys::WEIGHT];
                $obj[$key][StockKeys::QUANTITY] += $stock[StockKeys::QUANTITY];
            } else {
                $obj[$key] = $stock;
            }
        }
        return array_values($obj);
    } 
}
