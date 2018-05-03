<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
class JwtMiddleware
{
	public function handle($request, Closure $next, $guard = null)
	{
		if(!$request->hasHeader('Authorization')) {
			return response()->json([
				'error' => 'Authorization Header not found'
			], 401);
		}

		$token = $request->bearerToken();

		//dd($token);

		if(!$token) {
			// Unauthorized response if token not there
			return response()->json([
				'error' => 'Token not provided.'
			], 401);
		}

		try {
			$credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
		} catch(ExpiredException $e) {
			return resonse()->json([
				'error' => 'Provided token is expired.'
			], 400);
		}

		$user = User::find($credentials->sub);
		//dd($user->name);
		// Let's put the user in the request class so that you can grab it from there
		$request->auth = $user;

		return $next($request);
	}
}