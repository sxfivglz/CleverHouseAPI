<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AdafruitController;
use Illuminate\Support\Facades\Http;

class HabitacionesController extends Controller
{
    protected $user;
    public function __construct(Request $request)
    {
        $token = $request->header('Authorization');
        if($token != '')
            //En caso de que requiera autentifiación la ruta obtenemos el usuario y lo almacenamos en una variable, nosotros no lo utilizaremos.
            $this->user = JWTAuth::parseToken()->authenticate();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Listamos todos los Habitacionos
        return Habitacion::get();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validamos los datos
        $data = $request->only('nombre_habitacion');
        $validator = Validator::make($data, [
            'nombre_habitacion' => 'required|string',
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Creamos el Habitaciono en la BD
        $val = Habitacion::create([
            'nombre_habitacion' => $request->nombre_habitacion,
        ]);

        //Respuesta en caso de que todo vaya bien.
        return response()->json([
            'message' => 'Habitacion registrado',
            'data' => $val,
        ], Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Habitacion  $Habitacion
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Bucamos el Habitaciono
        $Habitacion = Habitacion::find($id);
        //Si el Habitaciono no existe devolvemos error no encontrado
        if (!$Habitacion) {
            return response()->json([
                'message' => 'Habitacion no encontrado'
            ], 404);
        }
        //Si hay Habitaciono lo devolvemos
        return $Habitacion;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Habitacion  $Habitacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validación de datos
        $data = $request->only('nombre_habitacion','columna_1','columna_2','columna_3','columna_4');
        $validator = Validator::make($data, [
            'nombre_habitacion' => 'required|string',
        ]);
        //Si falla la validación error.
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Buscamos el Habitaciono
        $Habitacion = Habitacion::findOrfail($id);
        //Actualizamos el Habitaciono.
        $Habitacion->update([
            'nombre_habitacion' => $request->nombre_habitacion,
        ]);
        //Devolvemos los datos actualizados.
        return response()->json([
            'message' => 'Habitacion actualizado',
            'data' => $Habitacion
        ], Response::HTTP_CREATED);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Habitacion  $Habitacion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Buscamos el Habitaciono
        $Habitacion = Habitacion::findOrfail($id);
        //Eliminamos el Habitaciono
        $Habitacion->delete();
        //Devolvemos la respuesta
        return response()->json([
            'message' => 'Habitacion eliminado'
        ], Response::HTTP_OK);
    }
}
