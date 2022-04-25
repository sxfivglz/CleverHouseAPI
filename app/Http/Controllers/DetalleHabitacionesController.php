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
        $data = $request->only('nombre_habitacion','nombre_casa');
        $validator = Validator::make($data, [
            'nombre_habitacion' => 'required|string',
            'nombre_casa'=>'required|string',
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        $q="SELECT id from habitaciones where nombre_habitacion='".$request->nombre_habitacion."';";
        $idHab = DB::select($q);
        $idH =$idHab[0];
        $q1="SELECT
            d.id
        FROM detalles AS d 
        INNER JOIN casas AS c
        ON d.casa_fk = c.id
        WHERE c.nombre_casa='".$request->nombre_casa."';";
        $idDet = DB::select($q1);
        $idD =$idDet[0];
        //Creamos el DetalleHabitaciono en la BD
        $val = DetalleHabitacion::create([
            'habitacion_fk' => $idH->id,
            'detalle_fk'=>$idD->id,
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
    public function habitacionesCasa(Request $request)
    {
        $array=$array = $request->toArray();
        $q="SELECT
        h.id,h.nombre_habitacion
      FROM detalle_habitaciones AS dh
      INNER JOIN habitaciones AS h
      ON dh.habitacion_fk = h.id
      INNER JOIN detalles AS d
      ON dh.detalle_fk = d.id
      INNER JOIN casas AS c
      ON d.casa_fk = c.id
      where c.nombre_casa='".$array[0]['nombre_casa']."';";
        $clave_base = DB::select($q);

        return $clave_base;
    }

}
