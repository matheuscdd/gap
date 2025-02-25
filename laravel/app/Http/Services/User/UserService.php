<?php

namespace App\Http\Services\User;

use App\Models\User;
use App\Constraints\UserKeysConstraints as Keys;
use App\Exceptions\AppError;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService {
    public static function create(array $data) {
        $data[Keys::EMAIL] = strtolower($data[Keys::EMAIL]);
        return response(User::create($data), 201);
    }

    public static function find(int $id) {
        return User::find($id);
    }

    public static function edit(int $id, array $data) {
        if (array_key_exists(Keys::EMAIL, $data)) {
            $data[Keys::EMAIL] = strtolower($data[Keys::EMAIL]);
        }

        $user = User::find($id);
        $user->update($data);
        return $user;
    }

    public static function del(int $id) {
        if (auth()->user()->id === $id) {
            throw new AppError("O usuário não pode excluir a si mesmo", 403);
        }
        $user = User::find($id);
        $user->active = false;
        $user->save();
        return response(null, 204);
    }

    public static function list() {
        return User::where(Keys::ACTIVE, '=', true)->get();
    }

    public static function lostPassword($request) {
        try {
            $user = User::where(Keys::EMAIL, '=', $request[Keys::EMAIL])->first();
            $name = $user->name;
            $rawKey = auth()->tokenById($user->id);
            $host = request()->getSchemeAndHttpHost();
            if (str_contains($host, 'localhost')) {
                define('FRONT_PORT', 8080);
                $host .= ':' . FRONT_PORT;
            }
            $encryptedKey = self::encryptContent($rawKey);
            $endpoint = "$host/app/users/password/reset/$encryptedKey";
            
            $content = "Olá, $name, foi solicitada uma recuperação de senha para este e-mail, clique no link abaixo para definir a nova senha: $endpoint";
            Mail::raw($content, function ($message) use ($user) {
                $message->to($user->email)
                        ->subject(env('VUE_APP_CARRIER_PARCIAL_NAME') . ' - Recuperação de Senha');
            });
        } catch(Exception $err) {
            Log::error($err);
        } finally {
            return ['msg' => 'Caso o usuário exista e esteja cadastrado, um email foi enviado com as instruções de acesso'];
        }
    }

    public static function resetPassword($request) {
        try {
            $token = self::decryptContent($request['key']);
            $user = JWTAuth::setToken($token)->authenticate();
            $user->update([Keys::PASSWORD => $request[Keys::PASSWORD]]);
            JWTAuth::invalidate($token);
            return $user;
        } catch(Exception $err) {
            Log::error($err);
            throw new AppError('Key inválida', 422);
        }
    }

    private static function encryptContent($content) {
        $secret = env('PRIVATE_KEY_256');
        $hmac = hash_hmac('sha256', $content, $secret, true);
        $ivlen = openssl_cipher_iv_length($cipher='AES-256-CBC');
        $iv = random_bytes($ivlen);
        $encryptedContent = openssl_encrypt($content, $cipher, $secret, OPENSSL_RAW_DATA, $iv);
        return urlencode(base64_encode($iv.$hmac.$encryptedContent));
    } 

    private static function decryptContent($rawestData) {
        $secret = env('PRIVATE_KEY_256');
        $isEncoded = str_contains($rawestData, '%');
        $rawData = base64_decode($isEncoded ? urldecode($rawestData) : $rawestData);
        $ivlen = openssl_cipher_iv_length($cipher='AES-256-CBC');
        $iv = substr($rawData, 0, $ivlen);
        $hmac = substr($rawData, $ivlen, $sha2len=32);
        $encryptedContent = substr($rawData, $ivlen+$sha2len);
        $decryptedContent = openssl_decrypt($encryptedContent, $cipher, $secret, OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $decryptedContent, $secret, true);
        if (!hash_equals($hmac, $calcmac)) return null;
        return $decryptedContent;
    }

}
