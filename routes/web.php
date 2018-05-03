<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/*$router->get('/', function () use ($router) {
    return $router->app->version();
});*/



$router->post('/auth/login','AuthController@authenticate');	



$router->group(['prefix' => 'api/v1'],function() use($router) {
		$router->get('users', 'AuthController@show');

		$router->get('all', 'UsersController@index');
		$router->get('show', 'UsersController@show');
		$router->post('user', 'UsersController@create');
		$router->put('user', 'UsersController@update');
		$router->delete('delete', 'UsersController@destroy');
		//$router->post('users', function(){
			//$users = \App\User::all();
			//return response()->json($users);
		//});
		//$router->get('/')
	}
);


/*$router->get('/key', function(){

	return str_random(32);
});*/

//$router->post('/test', 'AuthController@test');