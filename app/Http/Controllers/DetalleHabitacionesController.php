<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DetalleHabitacion;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DetalleHabitacionesController extends Controller
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
        //Listamos todos los DetalleHabitacion
        return DetalleHabitacion::get();
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
        $data = $request->only('habitacion_fk','detalle_fk');
        $validator = Validator::make($data, [
            'habitacion_fk' => 'required|string',
            'detalle_fk'=>'required|string',
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Creamos el DetalleHabitaciono en la BD
        $val = DetalleHabitacion::create([
            'habitacion_fk' => $request->habitacion_fk,
            'detalle_fk'=>$request->detalle_fk,
        ]);
        //Respuesta en caso de que todo vaya bien.
        return response()->json([
            'message' => 'DetalleHabitacion registrada',
            'data' => $val
        ], Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetalleHabitacion  $DetalleHabitacion
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Bucamos el DetalleHabitaciono
        $DetalleHabitacion = DetalleHabitacion::find($id);
        //Si el DetalleHabitaciono no existe devolvemos error no encontrado
        if (!$DetalleHabitacion) {
            return response()->json([
                'message' => 'DetalleHabitacion no encontrada'
            ], 404);
        }
        //Si hay DetalleHabitaciono lo devolvemos
        return $DetalleHabitacion;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetalleHabitacion  $DetalleHabitacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validación de datos
        $data = $request->only('habitacion_fk','detalle_fk');
        $validator = Validator::make($data, [
            'habitacion_fk' => 'required|max:50|string',
            'detalle_fk'=>'required|string',
        ]);
        //Si falla la validación error.
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Buscamos el DetalleHabitaciono
        $DetalleHabitacion = DetalleHabitacion::findOrfail($id);
        //Actualizamos el DetalleHabitaciono.
        $DetalleHabitacion->update([
            'habitacion_fk' => $request->habitacion_fk,
            'detalle_fk'=>$request->detalle_fk,
            
        ]);
        //Devolvemos los datos actualizados.
        return response()->json([
            'message' => 'DetalleHabitacion actualizada',
            'data' => $DetalleHabitacion
        ], Response::HTTP_CREATED);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetalleHabitacion  $DetalleHabitacion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Buscamos el DetalleHabitaciono
        $DetalleHabitacion = DetalleHabitacion::findOrfail($id);
        //Eliminamos el DetalleHabitaciono
        $DetalleHabitacion->delete();
        //Devolvemos la respuesta
        return response()->json([
            'message' => 'DetalleHabitacion eliminada'
        ], Response::HTTP_OK);
    }
}
