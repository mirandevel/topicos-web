<?php

use App\Http\Controllers\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//LOGIN ROUTES
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('guest')->post('/forgot-password', [AuthController::class, 'forgotPassword']);



//VERIFICATION ROUTES
Route::post('send-mail', [AuthController::class, 'sendEmail'] )->name('email');

//SERVICE ROUTES
Route::get('servicios', [\App\Http\Controllers\ServiceController::class, 'obtenerServicios'] )->name('servicios');
Route::get('horarios', [\App\Http\Controllers\DatosMaestrosController::class, 'obtenerHorarios'] )->name('horarios');
