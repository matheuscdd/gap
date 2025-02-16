<?php

namespace App\Http\Services\Maintenance;

use App\Constraints\MaintenanceKeysConstraints as Keys;
use App\Models\Maintenance;
use App\Exceptions\AppError;
use Illuminate\Support\Facades\Log;
use App\Constraints\ValidatorConstraints as Schema;


class MaintenanceService {
    private static function validateKm(array $data, int $id = 0) {
        $lastMaintenance = Maintenance::where('truck', '=', $data[Keys::TRUCK])
            ->where('id', '!=', $id)
            ->orderBy(Keys::DATE, 'desc')
            ->first();

        if (!boolval($lastMaintenance)) return;
        else if (
            $data[Keys::DATE] > $lastMaintenance->date && 
            $data[Keys::KM] < $lastMaintenance->km 
        ) {
            throw new AppError('A data não pode ser superior a da manutenção anterior e a quilometragem ser menor', 409);
        } else if (
            $data[Keys::DATE] < $lastMaintenance->date && 
            $data[Keys::KM] > $lastMaintenance->km 
        ) {
            throw new AppError('A data não pode ser inferior a da manutenção anterior e a quilometragem ser maior', 409);
        }
    }


    public static function create(array $data) {
        $data[Keys::CREATED_BY] = auth()->user()->id;
        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $data[Keys::DATE] = date(Schema::DATE_SCHEMA, strtotime($data[Keys::DATE]));
        self::validateKm($data);
        return response(Maintenance::create($data), 201);
    }

    public static function edit(int $id, array $data) {
        $maintenanceInst = Maintenance::find($id);
        $maintenanceArr = json_decode(json_encode($maintenanceInst), true);

        if (array_key_exists(Keys::DATE, $data)) {
            $data[Keys::DATE] = date(Schema::DATE_SCHEMA, strtotime($data[Keys::DATE]));
        }

        foreach(Keys::ALL as $key) {
            $maintenanceArr[$key] = $data[$key];
        }

        self::validateKm($maintenanceArr, $id);
        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $maintenanceInst->update($data);
        return response($maintenanceInst);
    }

    public static function list() {
        return Maintenance::all();
    }

    public static function find(int $id) {
        return Maintenance::find($id);
    }

    public static function del(int $id) {
        (Maintenance::find($id))->delete();
        return response(null, 204); 
    }

    public static function chartsScatter($request) {
        if(!$request->has('start_date') || !$request->has('end_date')) {
            throw new AppError('[start_date] e [end_date] são requeridos', 400);
        }
        
        $startDate = date(Schema::DATE_SCHEMA, strtotime($request->query('start_date')));
        $endDate = date(Schema::DATE_SCHEMA, strtotime($request->query('end_date')));
        $query = Maintenance::
            where(Keys::DATE, '>=', $startDate)
            ->where(Keys::DATE, '<=', $endDate);

        if ($request->has('truck')) {
            $query = $query->where(Keys::TRUCK, $request->query('truck'));
        }
            
        $maintenances = $query
            ->orderBy(Keys::DATE, 'asc')
            ->get();

        if (!count($maintenances)) return [];

        $costs = [
            'name' => 'Manutenções',
            'x' => [],
            'y' => [],
            'text' => [],
            'hovertemplate' => '(%{x}, R$ %{y}, %{text})',
            'type' => 'lines+markers',
            'line' => [
                'color' => '#EC001E'
            ]
        ];
       
        $keys = [];

        foreach($maintenances as $el) {
            $date = date('d/m/Y', strtotime($el->date));

            if (!array_key_exists($date, $keys)) {
                $keys[$date] = [];
            }
            $keys[$date][] = $el;
        }

        foreach($keys as $date => $arr) {
            $text = 'Manutenções ';
            $y = 0;
            
            foreach($arr as $el) {
                $text .= $el->id . ',';
                $y += $el->cost;
            }

            $text = substr($text, 0, -1);
            $costs['x'][] = $date;
            $costs['y'][] = $y;
            $costs['text'][] = $text;
        }

        return response()->json([$costs], 200, [], JSON_UNESCAPED_SLASHES);
    }
}
