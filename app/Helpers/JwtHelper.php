<?php
namespace App\Http\Helpers;
use Firebase\JWT\JWT;

class JwtHelper {


    public function jwt(User $user)
    {
        $payload = [
            'iss' => "lumen-jwt",       // Issue of the token
            'sub' => $user->id,         // Subject of the token
            'iat' => time(),            // Time when JWT was issued.
            'exp' => time() + 60*60     // Expiration time  
        ];

        // As you can see we are passing 'JWT_SECRET' as the second parameter that will be used to decode the token in the future.

        return JWT::encode($payload, env('JWT_SECRET'));
    }

}