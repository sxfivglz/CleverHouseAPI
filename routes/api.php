<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdafruitController;
use App\Http\Controllers\DuenosController;
use App\Http\Controllers\InvitadosController;
use App\Http\Controllers\HabitacionesController;
use App\Http\Controllers\RegistrosController;
use App\Http\Controllers\DetalleHabitacionesController;
use App\Http\Controllers\DetallesController;
use App\Http\Controllers\DetalleSensoresController;
use App\Http\Controllers\SensoresController;
use App\Http\Controllers\CasasController;
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

//estos son pruebas
Route::get('/Luminosidad',[AdafruitController::class,'getLuminosidad']);
Route::get('/Distancia',[AdafruitController::class,'getDistancia']);
//sensores
Route::get('/UltimoRegistro/{feed}',[AdafruitController::class,'ultimo_data']);
Route::get('/TotalSensores/{hab}',[AdafruitController::class,'listarSensores']);
Route::post('/CambiarEstado/{feed}',[AdafruitController::class,'cambiarLed']);
Route::post('/CrearSensor/{hab}/{nombre}',[AdafruitController::class,'crearSensor']);
Route::put('/ModificarSensor/{hab}/{nombre}/{nuevoNombre}',[AdafruitController::class,'modificarSensor']);
Route::delete('/EliminarSensor/{hab}/{nombre}',[AdafruitController::class,'eliminarSensor']);
//habitaciones
Route::post('/SensorHab/{hab}/{nombreSensor}',[AdafruitController::class,'añadirSensor']);
Route::post('/CrearHab/{nombre}',[AdafruitController::class,'añadirHab']);
Route::post('/CambiarSensor/{hab}/{nombreSensor}',[AdafruitController::class,'cambiarSensor']);
Route::get('/TotalHab',[AdafruitController::class,'listarHabitaciones']);
Route::put('/ModificarHabitacion/{nombre_habitacion}/{nuevoNombre}',[AdafruitController::class,'modificarHabitacion']);
Route::delete('/EliminarHabitacion/{hab}/{nombre}',[AdafruitController::class,'eliminarHabitacion']);
Route::get('/keypad/{feed}',[AdafruitController::class,'dato_keypad']);

//Casas
//Route::post('/CrearCasa/{nombre}',[AdafruitController::class,'crearCasa']);
Route::post('/nomFeed/{nombre}',[AdafruitController::class,'feedId']);

Route::prefix('user/')->group(function () {
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('datosUsuario', [AuthController::class, 'datosUsuario']);
    Route::post('tokenArduino', [AuthController::class, 'traerToken']);
    Route::post('register', [AuthController::class, 'register']);
    Route::group(['middleware' => ['jwt.verify']], function() {
        //Todo lo que este dentro de este grupo requiere verificación de usuario.
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('get-user', [AuthController::class, 'getUser']);
    });
});
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::prefix('reg')->group(function () {
        Route::get('/listar', [RegistrosController::class, 'index']);//estos son datas
        Route::post('/insertarLectura', [RegistrosController::class, 'storeRead']);
        Route::post('/insertarDato', [RegistrosController::class, 'storeData']);
        Route::get('/buscar/{id}', [RegistrosController::class, 'show']);
    });
    Route::prefix('casas')->group(function () {
        Route::put('/modificar/{id}', [CasasController::class, 'update']);
        Route::delete('/borrar/{id}',[CasasController::class, 'destroy']);
        Route::get('/listar', [CasasController::class, 'index']);
        Route::post('/insertar', [CasasController::class, 'store']);
        Route::post('/casasUsuario', [CasasController::class, 'consultaCasa']);
        Route::get('/buscar/{id}', [CasasController::class, 'show']);
    });
    Route::prefix('hab')->group(function () {
        Route::put('/modificar/{id}', [HabitacionesController::class, 'update']);
        Route::delete('/borrar/{id}',[HabitacionesController::class, 'destroy']);
        Route::get('/listar', [HabitacionesController::class, 'index']);
        Route::post('/insertar', [HabitacionesController::class, 'store']);//estas son groups
        Route::get('/buscar/{id}', [HabitacionesController::class, 'show']);
    });
    Route::prefix('sen')->group(function () {
        Route::put('/modificar/{id}', [SensoresController::class, 'update']);
        Route::delete('/borrar/{id}',[SensoresController::class, 'destroy']);
        Route::get('/listar', [SensoresController::class, 'index']);
        Route::post('/insertar', [SensoresController::class, 'store']);//estos son feeds
        Route::get('/buscar/{id}', [SensoresController::class, 'show']);
    });
    Route::prefix('duenos')->group(function () {
        Route::put('/modificar/{id}', [DuenosController::class, 'update']);
        Route::delete('/borrar/{id}',[DuenosController::class, 'destroy']);
        Route::get('/listar', [DuenosController::class, 'index']);
        Route::post('/insertar', [DuenosController::class, 'store']);
        Route::post('/entrada', [DuenosController::class, 'comparacion']);
        Route::get('/buscar/{id}', [DuenosController::class, 'show']);
    });
    Route::prefix('inv')->group(function () {
        Route::put('/modificar/{id}', [InvitadosController::class, 'update']);
        Route::delete('/borrar/{id}',[InvitadosController::class, 'destroy']);
        Route::get('/listar', [InvitadosController::class, 'index']);
        Route::post('/insertar', [InvitadosController::class, 'store']);
        Route::get('/buscar/{id}', [InvitadosController::class, 'show']);
    });
    Route::prefix('det')->group(function () {
        Route::put('/modificar/{id}', [DetallesController::class, 'update']);
        Route::delete('/borrar/{id}',[DetallesController::class, 'destroy']);
        Route::get('/listar', [DetallesController::class, 'index']);
        Route::post('/insertar', [DetallesController::class, 'store']);
        Route::post('/insertarInvitado', [CasasController::class, 'storeInv']);//al insertar un invitado en una casa, es necesario mandar llamar esta ruta
        Route::get('/buscar/{id}', [DetallesController::class, 'show']);
    });
    Route::prefix('det_sen')->group(function () {
        Route::put('/modificar/{id}', [DetalleSensoresController::class, 'update']);
        Route::delete('/borrar/{id}',[DetalleSensoresController::class, 'destroy']);
        Route::get('/listar', [DetalleSensoresController::class, 'index']);
        Route::post('/insertar', [DetalleSensoresController::class, 'store']);
        Route::get('/buscar/{id}', [DetalleSensoresController::class, 'show']);
    });
    Route::prefix('det_hab')->group(function () {
        Route::put('/modificar/{id}', [DetalleHabitacionesController::class, 'update']);
        Route::delete('/borrar/{id}',[DetalleHabitacionesController::class, 'destroy']);
        Route::get('/listar', [DetalleHabitacionesController::class, 'index']);
        Route::post('/insertar', [DetalleHabitacionesController::class, 'store']);
        Route::get('/buscar/{id}', [DetalleHabitacionesController::class, 'show']);
    });
});
    //Route::get('/listar', [ProveedoresController::class, 'index']);
    //Route::post('/insertar', [ProveedoresController::class, 'store']);
    //Route::get('/buscar/{id}', [ProveedoresController::class, 'show']);
