<?php

namespace App\Utils;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class S3 {
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

    public static function insertImage(string $basePath, string $file): string {
        $s3 = self::getS3Client();
        $type = str_contains($file, 'image/png') ? 'png' : 'jpeg';
        $path = $basePath . '/' . uniqid() . '.' . $type;
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

    public static function deleteImage(string $file) {
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

    public static function mountPathImage(string $file): string {
        return implode('/', [env('S3_HOST'), env('S3_BUCKET'), $file]);
    }
}