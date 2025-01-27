<?php

namespace App\Http\Services\Truck;

use App\Constraints\TruckKeysConstraints as Keys;
use App\Constraints\MaintenanceKeysConstraints as MaintenanceKeys;
use App\Models\{Truck, Maintenance};
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\Admin\AdminApi;
use App\Exceptions\AppError;
use Illuminate\Support\Facades\Log;
use DateTime;

class TruckService {
    public static function create(array $data) {
        $data[Keys::CREATED_BY] = auth()->user()->id;
        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $data[Keys::PLATE] = strtoupper($data[Keys::PLATE]);

        if (array_key_exists(Keys::PHOTO, $data) && !is_null($data[Keys::PHOTO])) {
            $data[Keys::PHOTO] = (new UploadApi())->upload($data[Keys::PHOTO])['secure_url'];
        }

        return response()->json(Truck::create($data), 201, [], JSON_UNESCAPED_SLASHES);
    }

    public static function edit(int $id, array $data) {
        $truck = Truck::find($id);
        
        if (array_key_exists(Keys::PLATE, $data)) {
            $data[Keys::PLATE] = strtoupper($data[Keys::PLATE]);
        }

        if (array_key_exists(Keys::PHOTO, $data) && !is_null($data[Keys::PHOTO])) {
            $res = (new UploadApi())->upload($data[Keys::PHOTO]);
            $data[Keys::PHOTO] = $res['secure_url'];
        } else if ($truck->photo) {
            $id = explode('/', $truck->photo);
            $id = end($id);
            $id = explode('.', $id)[0];
            $res = (new UploadApi())->destroy($id, ['resource_type' => 'image']);
            if ($res !== 'ok') {
                throw new AppError("Não foi possível apagar a imagem", 500);
            }
            $data[Keys::PHOTO] = null;
        }

        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $truck->update($data);
        return response()->json($truck, 200, [], JSON_UNESCAPED_SLASHES);
    }

    public static function list() {
        $trucks = json_decode(json_encode(Truck::with('maintenances')->get()), true);
        foreach($trucks as &$truck) {
            $truck['maintenances'] = count($truck['maintenances']);
        }
        # TODO - no list do front bloquear caminhões com manutenção
        unset($truck);

        return response()->json($trucks, 200, [], JSON_UNESCAPED_SLASHES);
    }

    public static function find(int $id) {
        return response()->json(Truck::find($id), 200, [], JSON_UNESCAPED_SLASHES);
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

        $truck->delete();
        return response(null, 204); 
    }
}
