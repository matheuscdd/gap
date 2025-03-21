<?php

namespace App\Http\Services\Driver;
use App\Constraints\DriverKeysConstraints as Keys;
use App\Exceptions\AppError;
use App\Models\Driver;
use App\Utils\S3;
use DateTime;

class DriverService {
    public static function create(array $data) {
        $data[Keys::CREATED_BY] = auth()->user()->id;
        $data[Keys::UPDATED_BY] = auth()->user()->id;

        if (array_key_exists(Keys::PHOTO, $data) && !is_null($data[Keys::PHOTO])) {
            $data[Keys::PHOTO] = S3::insertImage(Keys::TABLE, $data[Keys::PHOTO]);
        }

        $driver = json_decode(json_encode(Driver::create($data)), true);
        if (!is_null($driver[Keys::PHOTO])) {
            $driver[Keys::PHOTO] = S3::mountPathImage($driver[Keys::PHOTO]);
        }


        return response()->json($driver, 201, [], JSON_UNESCAPED_SLASHES);
    }

    public static function find(int $id) {
        $driver = json_decode(json_encode(Driver::find($id)), true);
        if (!is_null($driver[Keys::PHOTO])) {
            $driver[Keys::PHOTO] = S3::mountPathImage($driver[Keys::PHOTO]);
        }
        return response()->json($driver, 200, [], JSON_UNESCAPED_SLASHES);
    }

    public static function edit(int $id, array $data) {
        $driver = Driver::find($id);

        if (array_key_exists(Keys::PHOTO, $data) && $driver->photo) {
            S3::deleteImage($driver->photo);
        }

        if (array_key_exists(Keys::PHOTO, $data) && !is_null($data[Keys::PHOTO])) {
            $data[Keys::PHOTO] = S3::insertImage(Keys::TABLE, $data[Keys::PHOTO]);
        } 

        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $driver->update($data);
        $driver = json_decode(json_encode($driver), true);
        if (!is_null($driver[Keys::PHOTO])) {
            $driver[Keys::PHOTO] = S3::mountPathImage($driver[Keys::PHOTO]);
        }
        
        return response()->json($driver, 200, [], JSON_UNESCAPED_SLASHES);
    }

    public static function list() {
        $drivers = json_decode(json_encode(Driver::with('deliveries')->get()), true);
        foreach($drivers as &$driver) {
            $driver['deliveries'] = count($driver['deliveries']);
            if (!is_null($driver[Keys::PHOTO])) {
                $driver[Keys::PHOTO] = S3::mountPathImage($driver[Keys::PHOTO]);
            }
        }
        unset($driver);

        return response()->json($drivers, 200, [], JSON_UNESCAPED_SLASHES);
    }
    public static function del(int $id) {
        $driver = Driver::find($id);

        $maxMinTime = 30;
        $diff = intval(((new DateTime())->getTimestamp() - $driver->created_at->getTimestamp()) / 60);
        if ($diff > $maxMinTime) {
            throw new AppError("O tempo máximo para realizar a deleção após a criação é de $maxMinTime minutos", 423);
        }

        if (count($driver->deliveries)) {
            throw new AppError('Esse motorista já está em uso no sistema', 409);
        }

        if (!is_null($driver->photo)) {
            S3::deleteImage($driver->photo);
        }
                    
        $driver->delete();
        return response(null, 204);
    }
}
