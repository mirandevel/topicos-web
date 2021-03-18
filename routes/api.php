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
Route::middleware('auth:sanctum')->post('/fcm', [AuthController::class, 'fcm']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('guest')->post('/forgot-password', [AuthController::class, 'forgotPassword']);



//VERIFICATION ROUTES
Route::post('send-mail', [AuthController::class, 'sendEmail'] )->name('email');


//SERVICE ROUTES
Route::get('servicios', [\App\Http\Controllers\ServiceController::class, 'obtenerServicios'] )->name('servicios');
Route::get('horarios', [\App\Http\Controllers\DatosMaestrosController::class, 'obtenerHorarios'] )->name('horarios');


//EMPLEADOR
Route::post('/empleador/registro', [\App\Http\Controllers\EmpleadorController::class, 'register']);



//TRABAJADOR
Route::get('trabajadores', [\App\Http\Controllers\TrabajadorController::class, 'obtenerTrabajadores'] )->name('trabajadores');
Route::get('trabajadores/todos', [\App\Http\Controllers\TrabajadorController::class, 'obtenerTodos'] )->name('obtenerTodos');
Route::get('trabajadores/detalle', [\App\Http\Controllers\TrabajadorController::class, 'detalleTrabajadores'] )->name('detalle');
Route::post('trabajadores/aceptar_rechazar', [\App\Http\Controllers\TrabajadorController::class, 'aceptarRechazarTrabajadores'] );
Route::post('trabajadores/servicio', [\App\Http\Controllers\TrabajadorController::class, 'servicoTrabajadores'] )->name('servicoTrabajadores');


//BUSQUEDA
Route::post('busqueda/servicios', [\App\Http\Controllers\BusquedaController::class, 'buscarServicios'] )->name('buscarservicios');
Route::get('busqueda', [\App\Http\Controllers\BusquedaController::class, 'seviciosLugares'] )->name('buscarservicios');
Route::post('trabajador/perfil', [\App\Http\Controllers\BusquedaController::class, 'detalleTrabajadores'] )->name('detalleTrabajadores');


Route::post('solicitud', [\App\Http\Controllers\SolicitudController::class, 'crearSolicitud'] )->name('crearSolicitud');
Route::middleware('auth:sanctum')->get('trabajador/solicitud', [\App\Http\Controllers\SolicitudController::class, 'obtenerSolicitudesTrabajador'] )->name('obtenerSolicitudesTrabajador');
Route::post('trabajador/aceptarrechazar', [\App\Http\Controllers\SolicitudController::class, 'aceptarRechazar'] )->name('aceptarRechazar');
Route::get('empleador/historial', [\App\Http\Controllers\SolicitudController::class, 'historial'] )->name('historial');


//dashboard
Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'dashboard'] );


