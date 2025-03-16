<?php

namespace App\Http\Services\Truck;

use App\Constraints\TruckKeysConstraints as Keys;
use App\Constraints\MaintenanceKeysConstraints as MaintenanceKeys;
use App\Models\{Truck, Maintenance};
use App\Exceptions\AppError;
use Illuminate\Support\Facades\Log;
use DateTime;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class TruckService {
    public static function create(array $data) {
        $data[Keys::CREATED_BY] = auth()->user()->id;
        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $data[Keys::PLATE] = strtoupper($data[Keys::PLATE]);

        if (array_key_exists(Keys::PHOTO, $data) && !is_null($data[Keys::PHOTO])) {
            $data[Keys::PHOTO] = self::insertImage($data[Keys::PHOTO]);
        }

        $truck = json_decode(json_encode(Truck::create($data)), true);
        if (!is_null($truck[Keys::PHOTO])) {
            $truck[Keys::PHOTO] = self::mountPathImage($truck[Keys::PHOTO]);
        }

        return response()->json($truck, 201, [], JSON_UNESCAPED_SLASHES);
    }

    public static function edit(int $id, array $data) {
        $truck = Truck::find($id);
        
        if (array_key_exists(Keys::PLATE, $data)) {
            $data[Keys::PLATE] = strtoupper($data[Keys::PLATE]);
        }

        if (array_key_exists(Keys::PHOTO, $data) && $truck->photo) {
            self::deleteImage($truck->photo);
        }

        if (array_key_exists(Keys::PHOTO, $data) && !is_null($data[Keys::PHOTO])) {
            $data[Keys::PHOTO] = self::insertImage($data[Keys::PHOTO]);
        } 

        $data[Keys::UPDATED_BY] = auth()->user()->id;
        $truck->update($data);
        $truck = json_decode(json_encode($truck), true);
        if (!is_null($truck[Keys::PHOTO])) {
            $truck[Keys::PHOTO] = self::mountPathImage($truck[Keys::PHOTO]);
        }
        
        return response()->json($truck, 200, [], JSON_UNESCAPED_SLASHES);
    }

    public static function list() {
        $trucks = json_decode(json_encode(Truck::with('maintenances')->get()), true);
        foreach($trucks as &$truck) {
            $truck['maintenances'] = count($truck['maintenances']);
            if (!is_null($truck[Keys::PHOTO])) {
                $truck[Keys::PHOTO] = self::mountPathImage($truck[Keys::PHOTO]);
            }
        }
        unset($truck);

        return response()->json($trucks, 200, [], JSON_UNESCAPED_SLASHES);
    }

    public static function find(int $id) {
        $truck = json_decode(json_encode(Truck::find($id)), true);
        if ($truck[Keys::PHOTO]) {
            $truck[Keys::PHOTO] = self::mountPathImage($truck[Keys::PHOTO]);
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
            self::deleteImage($truck->photo);
        }

        $truck->delete();
        return response(null, 204); 
    }

    private static function getS3Client(): S3Client {
        return new S3Client([
            'version' => 'latest',
            'region'  => env('MINIO_REGION'),
            'endpoint' => 'http://s3:9000',
            'use_path_style_endpoint' => true, 
            'credentials' => [
                'key'    => env('MINIO_ROOT_USER'),
                'secret' => env('MINIO_ROOT_PASSWORD'),
            ],
        ]);
    }

    private static function mountPathImage(string $file): string {
        return implode('/', [env('S3_HOST'), env('S3_BUCKET'), $file]);
    }

    private static function insertImage(string $file): string {
        $s3 = self::getS3Client();
        $type = str_contains($file, 'image/png') ? 'png' : 'jpeg';
        $path = 'trucks/' . uniqid() . '.' . $type;
        $bin = base64_decode(explode(',', $file)[1]);

        try {
            $s3->putObject([
                'Bucket' => env('S3_BUCKET'),
                'Key'    => $path,
                'Body'   => $bin,
                'ACL'    => 'public-read',
            ]);
        } catch (S3Exception $err) {
            Log::error($err->getAwsErrorMessage());
            throw new AppError('Erro ao inserir imagem', 500);
        }

        return $path;
    }

    private static function deleteImage(string $file) {
        $s3 = self::getS3Client();
        try {
            $s3->deleteObject([
                'Bucket' => env('S3_BUCKET'),
                'Key'    => $file,
            ]);
        } catch (S3Exception $err) {
            Log::error($err);
            throw new AppError('Não foi possível apagar a imagem', 500);
        } 
    }
}
