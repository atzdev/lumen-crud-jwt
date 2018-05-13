<?php

namespace App\Http\Controllers;
use Firebase\JWT\JWT;
use App\Http\Helpers\JwtHelper as JWTHelper;
use Validator;
use App\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;


class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {        
        $this->middleware('jwt.auth', ['except' => ['create']]);
    }

    public function index()
    {
        return response()->json(User::all());
    }

    public function show(Request $request)
    {
        return response()->json($request->auth);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        return response()->json([
            'token' => $this->jwt($user)
        ], 200);
    }

    public function update(Request $request)
    {
        //dd($request->all());
        $user = User::find($request->auth->id);


        $user->name = !empty($request->name)? $request->name : $user->name;

        $user->save();

        return response()->json($user);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        $user->delete();

        return response()->json([
            'data' => 'User id: '.$id.' deleted.'
        ]);
    }

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
