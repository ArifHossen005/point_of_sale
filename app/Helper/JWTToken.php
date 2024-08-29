<?php


namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Exception;

class JWTToken
{

    public static  function CreateToken(string $userEmail,$userID): string
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token',
            'iat' => time(),
            'exp' => time() + 60 * 60, // Token expires in 1 hour
            'userEmail' => $userEmail,
            'userID' => $userID
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public static  function CreateTokenForSetPassword(string $userEmail): string
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token',
            'iat' => time(),
            'exp' => time() + 60 * 20, // Token expires in 1 hour
            'userEmail' => $userEmail,
            'userID' => '0'

        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public static function VerifyToken($token):string|object
    {
        try {
            if($token==null)
            {
                return 'unauthorized';
            }

            $key = env('JWT_KEY');
            $decode = JWT::decode($token,new Key($key,'HS256'));
            return   $decode;
        }
        catch(Exception $e)

        {
            return   'unauthorized';

        }



    }





}
