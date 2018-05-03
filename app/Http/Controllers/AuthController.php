<?php
namespace App\Http\Controllers;
use Validator;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
	private $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function test()
	{
		return 'test ok';
	}

	public function jwt(User $user)
	{
		$payload = [
			'iss' => "lumen-jwt",		// Issue of the token
			'sub' => $user->id,			// Subject of the token
			'iat' => time(), 			// Time when JWT was issued.
			'exp' => time() + 60*60 	// Expiration time  
		];

		// As you can see we are passing 'JWT_SECRET' as the second parameter that will be used to decode the token in the future.

		return JWT::encode($payload, env('JWT_SECRET'));
	}

	public function authenticate(User $user)
	{
		//dd($this->request->input('email'));

		$this->validate($this->request, [
			'email' => 'required|email',
			'password' => 'required'
		]);

		$user = User::where('email', $this->request->input('email'))->first();

		if(!$user) 
		{
			return response()->json([
				'error' => 'Email does not exist.'
			], 400);
		}

		// Verify the password and generate the token
		if(Hash::check($this->request->input('password'), $user->password)) 
			{
				return response()->json([
					'token' => $this->jwt($user)
				], 200);
			}

			// Bad Request response
			return response()->json([
				'error' => 'Email or Password is wrong.'
			], 400);
	}


	public function show(Request $request) 
	{	
		return response()->json($request->auth);
	}

}