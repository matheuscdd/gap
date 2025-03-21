<?php

namespace App\Http\Services\Truck;

use App\Constraints\TruckKeysConstraints as Keys;
use App\Constraints\MaintenanceKeysConstraints as MaintenanceKeys;
use App\Models\{Truck, Maintenance};
use App\Exceptions\AppError;
use App\Utils\S3;
use Illuminate\Support\Facades\Log;
use DateTime;


class TruckService {
    public static function create(array $data) {
        $data[Keys::CREATED_BY] = auth()->user()->id;
        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $data[Keys::PLATE] = strtoupper($data[Keys::PLATE]);

        if (array_key_exists(Keys::PHOTO, $data) && !is_null($data[Keys::PHOTO])) {
            $data[Keys::PHOTO] = S3::insertImage(Keys::TABLE, $data[Keys::PHOTO]);
        }

        $truck = json_decode(json_encode(Truck::create($data)), true);
        if (!is_null($truck[Keys::PHOTO])) {
            $truck[Keys::PHOTO] = S3::mountPathImage($truck[Keys::PHOTO]);
        }

        return response()->json($truck, 201, [], JSON_UNESCAPED_SLASHES);
    }

    public static function edit(int $id, array $data) {
        $truck = Truck::find($id);
        
        if (array_key_exists(Keys::PLATE, $data)) {
            $data[Keys::PLATE] = strtoupper($data[Keys::PLATE]);
        }

        if (array_key_exists(Keys::PHOTO, $data) && $truck->photo) {
            S3::deleteImage($truck->photo);
        }

        if (array_key_exists(Keys::PHOTO, $data) && !is_null($data[Keys::PHOTO])) {
            $data[Keys::PHOTO] = S3::insertImage(Keys::TABLE, $data[Keys::PHOTO]);
        } 

        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $truck->update($data);
        $truck = json_decode(json_encode($truck), true);
        if (!is_null($truck[Keys::PHOTO])) {
            $truck[Keys::PHOTO] = S3::mountPathImage($truck[Keys::PHOTO]);
        }
        
        return response()->json($truck, 200, [], JSON_UNESCAPED_SLASHES);
    }

    public static function list() {
        $trucks = json_decode(json_encode(Truck::with('maintenances')->get()), true);
        foreach($trucks as &$truck) {
            $truck['maintenances'] = count($truck['maintenances']);
            if (!is_null($truck[Keys::PHOTO])) {
                $truck[Keys::PHOTO] = S3::mountPathImage($truck[Keys::PHOTO]);
            }
        }
        unset($truck);

        return response()->json($trucks, 200, [], JSON_UNESCAPED_SLASHES);
    }

    public static function find(int $id) {
        $truck = json_decode(json_encode(Truck::find($id)), true);
        if (!is_null($truck[Keys::PHOTO])) {
            $truck[Keys::PHOTO] = S3::mountPathImage($truck[Keys::PHOTO]);
        }
        return response()->json($truck, 200, [], JSON_UNESCAPED_SLASHES);
    }

    public static function del(int $id) {
        $truck = Truck::find($id);

        $maxMinTime = 30;
        $diff = intval(((new DateTime())->getTimestamp() - $truck->created_at->getTimestamp()) / 60);
        if ($diff > $maxMinTime) {
            throw new AppError("O tempo máximo para realizar a deleção após a criação é de $maxMinTime minutos", 423);
        }

        $hasMaintenances = boolval(Maintenance::where(MaintenanceKeys::TRUCK, '=', $id)->count());
        if ($hasMaintenances) {
            throw new AppError('Um caminhão com manutenções registradas não pode ser apagado', 409);
        }

        if (!is_null($truck->photo)) {
            S3::deleteImage($truck->photo);
        }

        $truck->delete();
        return response(null, 204); 
    }
}
