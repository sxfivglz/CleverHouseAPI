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
Route::delete('/EliminarSensor/{nombre}',[AdafruitController::class,'eliminarSensor']);
//habitaciones
Route::post('/SensorHab/{hab}/{nombreSensor}',[AdafruitController::class,'añadirSensor']);
Route::post('/CrearHab/{nombre}',[AdafruitController::class,'añadirHab']);
Route::post('/CambiarSensor/{hab}/{nombreSensor}',[AdafruitController::class,'cambiarSensor']);
Route::get('/TotalHab',[AdafruitController::class,'listarHabitaciones']);
Route::put('/ModificarHabitacion/{nombre}/{nuevoNombre}',[AdafruitController::class,'modificarHabitacion']);
Route::delete('/EliminarHabitacion/{nombre}',[AdafruitController::class,'eliminarHabitacion']);


Route::prefix('user/')->group(function () {
    //Prefijo V1, todo lo que este dentro de este grupo se accedera escribiendo v1 en el navegador, es decir /api/v1/*
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('register', [AuthController::class, 'register']);
    Route::group(['middleware' => ['jwt.verify']], function() {
        //Todo lo que este dentro de este grupo requiere verificación de usuario.
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('get-user', [AuthController::class, 'getUser']);
    });
});
    Route::prefix('reg')->group(function () {
        Route::get('/listar', [RegistrosController::class, 'index']);
        Route::post('/insertarLectura', [RegistrosController::class, 'storeRead']);
        Route::post('/insertarDato', [RegistrosController::class, 'storeData']);
        Route::get('/buscar/{id}', [RegistrosController::class, 'show']);
    });
    Route::prefix('casas')->group(function () {
        Route::put('/modificar/{id}', [CasasController::class, 'update']);
        Route::delete('/borrar/{id}',[CasasController::class, 'destroy']);
        Route::get('/listar', [CasasController::class, 'index']);
        Route::post('/insertar', [CasasController::class, 'store']);
        Route::get('/buscar/{id}', [CasasController::class, 'show']);
    });
    Route::prefix('hab')->group(function () {
        Route::put('/modificar/{id}', [HabitacionesController::class, 'update']);
        Route::delete('/borrar/{id}',[HabitacionesController::class, 'destroy']);
        Route::get('/listar', [HabitacionesController::class, 'index']);
        Route::post('/insertar', [HabitacionesController::class, 'store']);
        Route::get('/buscar/{id}', [HabitacionesController::class, 'show']);
    });
    Route::prefix('sen')->group(function () {
        Route::put('/modificar/{id}', [SensoresController::class, 'update']);
        Route::delete('/borrar/{id}',[SensoresController::class, 'destroy']);
        Route::get('/listar', [SensoresController::class, 'index']);
        Route::post('/insertar', [SensoresController::class, 'store']);
        Route::get('/buscar/{id}', [SensoresController::class, 'show']);
    });
    Route::prefix('duenos')->group(function () {
        Route::put('/modificar/{id}', [DuenosController::class, 'update']);
        Route::delete('/borrar/{id}',[DuenosController::class, 'destroy']);
        Route::get('/listar', [DuenosController::class, 'index']);
        Route::post('/insertar', [DuenosController::class, 'store']);
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
    //Route::get('/listar', [ProveedoresController::class, 'index']);
    //Route::post('/insertar', [ProveedoresController::class, 'store']);
    //Route::get('/buscar/{id}', [ProveedoresController::class, 'show']);

Route::group(['middleware' => ['jwt.verify']], function() {

    Route::prefix('prov')->group(function () {
        Route::put('/modificar/{id}', [ProveedoresController::class, 'update']);
        Route::delete('/borrar/{id}',[ProveedoresController::class, 'destroy']);
        Route::get('/listar', [ProveedoresController::class, 'index']);
        Route::post('/insertar', [ProveedoresController::class, 'store']);
        Route::get('/buscar/{id}', [ProveedoresController::class, 'show']);
    });

    Route::prefix('cat')->group(function () {
        Route::put('/modificar/{id}', [CategoriasController::class, 'update']);
        Route::delete('/borrar/{id}',[CategoriasController::class, 'destroy']);
        Route::get('/listar', [CategoriasController::class, 'index']);
        Route::post('/insertar', [CategoriasController::class, 'store']);
        Route::get('/buscar/{id}', [CategoriasController::class, 'show']);
    });

    Route::prefix('cli')->group(function () {
        Route::put('/modificar/{id}', [ClientesController::class, 'update']);
        Route::delete('/borrar/{id}',[ClientesController::class, 'destroy']);
        Route::get('/listar', [ClientesController::class, 'index']);
        Route::post('/insertar', [ClientesController::class, 'store']);
        Route::get('/buscar/{id}', [ClientesController::class, 'show']);
    });
    Route::prefix('ven')->group(function () {
        Route::put('/modificar/{id}', [VentasController::class, 'update']);
        Route::delete('/borrar/{id}',[VentasController::class, 'destroy']);
        Route::get('/listar', [VentasController::class, 'index']);
        Route::post('/insertar', [VentasController::class, 'store']);
        Route::get('/buscar/{id}', [VentasController::class, 'show']);
        });
    
    Route::prefix('com')->group(function () {
        Route::put('/modificar/{id}', [ComprasController::class, 'update']);
        Route::delete('/borrar/{id}',[ComprasController::class, 'destroy']);
        Route::get('/listar', [ComprasController::class, 'index']);
        Route::post('/insertar', [ComprasController::class, 'store']);
        Route::get('/buscar/{id}', [ComprasController::class, 'show']);
    });

    Route::prefix('prod')->group(function () {
        Route::put('/modificar/{id}', [ProductosController::class, 'update']);
        Route::delete('/borrar/{id}',[ProductosController::class, 'destroy']);
        Route::get('/listar', [ProductosController::class, 'index']);
        Route::post('/insertar', [ProductosController::class, 'store']);
        Route::get('/buscar/{id}', [ProductosController::class, 'show']);
    });
    Route::prefix('dcom')->group(function () {
        Route::put('/modificar/{id}', [DetallesComprasController::class, 'update']);
        Route::delete('/borrar/{id}',[DetallesComprasController::class, 'destroy']);
        Route::get('/listar', [DetallesComprasController::class, 'index']);
        Route::post('/insertar', [DetallesComprasController::class, 'store']);
        Route::get('/buscar/{id}', [DetallesComprasController::class, 'show']);
    });
    Route::prefix('dven')->group(function () {
        Route::put('/modificar/{id}', [DetallesVentasController::class, 'update']);
        Route::delete('/borrar/{id}',[DetallesVentasController::class, 'destroy']);
        Route::get('/listar', [DetallesVentasController::class, 'index']);
        Route::post('/insertar', [DetallesVentasController::class, 'store']);
        Route::get('/buscar/{id}', [DetallesVentasController::class, 'show']);
    });
});


    //Route::get('/listar', [CategoriasController::class, 'index']);
    //Route::post('/insertar', [CategoriasController::class, 'store']);
    //Route::get('/buscar/{id}', [CategoriasController::class, 'show']);

/*Route::group(['middleware' => ['jwt.verify']], function() {
    Route::put('/modificar/{id}', [CategoriasController::class, 'update']);
    Route::delete('/borrar/{id}',[CategoriasController::class, 'destroy']);
    Route::get('/listar', [CategoriasController::class, 'index']);
    Route::post('/insertar', [CategoriasController::class, 'store']);
    Route::get('/buscar/{id}', [CategoriasController::class, 'show']);
    });


Route::prefix('cli')->group(function () {
    //Route::get('/listar', [ClientesController::class, 'index']);
    //Route::post('/insertar', [ClientesController::class, 'store']);
    //Route::get('/buscar/{id}', [ClientesController::class, 'show']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::put('/modificar/{id}', [ClientesController::class, 'update']);
    Route::delete('/borrar/{id}',[ClientesController::class, 'destroy']);
    Route::get('/listar', [ClientesController::class, 'index']);
    Route::post('/insertar', [ClientesController::class, 'store']);
    Route::get('/buscar/{id}', [ClientesController::class, 'show']);
    });
});

Route::prefix('ven')->group(function () {
    //Route::get('/listar', [VentasController::class, 'index']);
    //Route::post('/insertar', [VentasController::class, 'store']);
    //Route::get('/buscar/{id}', [VentasController::class, 'show']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::put('/modificar/{id}', [VentasController::class, 'update']);
    Route::delete('/borrar/{id}',[VentasController::class, 'destroy']);
    Route::get('/listar', [VentasController::class, 'index']);
    Route::post('/insertar', [VentasController::class, 'store']);
    Route::get('/buscar/{id}', [VentasController::class, 'show']);
    });
});

Route::prefix('com')->group(function () {
    //Route::get('/listar', [ComprasController::class, 'index']);
    //Route::post('/insertar', [ComprasController::class, 'store']);
    //Route::get('/buscar/{id}', [ComprasController::class, 'show']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::put('/modificar/{id}', [ComprasController::class, 'update']);
    Route::delete('/borrar/{id}',[ComprasController::class, 'destroy']);
    Route::get('/listar', [ComprasController::class, 'index']);
    Route::post('/insertar', [ComprasController::class, 'store']);
    Route::get('/buscar/{id}', [ComprasController::class, 'show']);
    });
});

Route::prefix('prod')->group(function () {
    //Route::get('/listar', [ProductosController::class, 'index']);
    //Route::post('/insertar', [ProductosController::class, 'store']);
    //Route::get('/buscar/{id}', [ProductosController::class, 'show']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::put('/modificar/{id}', [ProductosController::class, 'update']);
    Route::delete('/borrar/{id}',[ProductosController::class, 'destroy']);
    Route::get('/listar', [ProductosController::class, 'index']);
    Route::post('/insertar', [ProductosController::class, 'store']);
    Route::get('/buscar/{id}', [ProductosController::class, 'show']);
    });
});

Route::prefix('dcom')->group(function () {
    //Route::get('/listar', [DetallesComprasController::class, 'index']);
    //Route::post('/insertar', [DetallesComprasController::class, 'store']);
    //Route::get('/buscar/{id}', [DetallesComprasController::class, 'show']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::put('/modificar/{id}', [DetallesComprasController::class, 'update']);
    Route::delete('/borrar/{id}',[DetallesComprasController::class, 'destroy']);
    Route::get('/listar', [DetallesComprasController::class, 'index']);
    Route::post('/insertar', [DetallesComprasController::class, 'store']);
    Route::get('/buscar/{id}', [DetallesComprasController::class, 'show']);
    });
});

Route::prefix('dven')->group(function () {
    //Route::get('/listar', [DetallesVentasController::class, 'index']);
    //Route::post('/insertar', [DetallesVentasController::class, 'store']);
    //Route::get('/buscar/{id}', [DetallesVentasController::class, 'show']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::put('/modificar/{id}', [DetallesVentasController::class, 'update']);
    Route::delete('/borrar/{id}',[DetallesVentasController::class, 'destroy']);
    Route::get('/listar', [DetallesVentasController::class, 'index']);
    Route::post('/insertar', [DetallesVentasController::class, 'store']);
    Route::get('/buscar/{id}', [DetallesVentasController::class, 'show']);
    });
});*/
