<?php

use App\Http\Controllers\api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/*
 |-------------------------------------------------------------------------------------------------
 | Rutas protegidas
 |-------------------------------------------------------------------------------------------------
 */
Route::middleware('jwt.api')->group(function () {
	
	Route::controller(AuthController::class)->group(function () {
		Route::get('me', 'me');
		Route::post('logout', 'logout');
		Route::post('refresh', 'refresh');
	});
	
});


/*
 |-------------------------------------------------------------------------------------------------
 | Rutas publicas
 |-------------------------------------------------------------------------------------------------
 */
Route::controller(AuthController::class)->group(function () {
	Route::post('register', 'register');
	Route::post('login', 'login');
});