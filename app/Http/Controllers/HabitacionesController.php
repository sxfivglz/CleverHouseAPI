<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AdafruitController;
use Illuminate\Support\Facades\DB;

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
        //Insertamos en adafruit
        $objeto = new AdafruitController();
        $myVariable = $objeto->añadirHab($request->nombre_habitacion);
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
            'ada'=>$myVariable
        ], Response::HTTP_OK);
    }
    public function storeArray(Request $request)
    {
        $array = $request->toArray();
        //$decoded=json_decode($array,true);
        $i=0;
        foreach($array as $arr){
        //Creamos el Habitacion en la BD
            $val = Habitacion::create([
            'nombre_habitacion' => $arr['nombre_habitacion'],
        ]);
        //Insertamos en adafruit
        $objeto = new AdafruitController();
        $myVariable = $objeto->añadirHab($arr['nombre_habitacion']);
        }
        //Respuesta en caso de que todo vaya bien.
        return response()->jsonArray([
           /* 'message' => 'Habitaciones registradas',*/
         /**/   'datadetins' => $array,
           /* 'ada'=>$myVariable*/
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

     //necesita el nombre de la habitacion en la que se encuentra de android, ademas del nuevo nombre
     public function update(Request $request)
     {
         //Insertamos en adafruit
         $objeto = new AdafruitController();
         $myVariable = $objeto->modificarHabitacion($request->nombre_anterior,$request->nuevo_nombre);
         //Validación de datos
         $data = $request->only('nombre_anterior','nuevo_nombre');
         $validator = Validator::make($data, [
             'nombre_anterior' => 'required|string',
             'nuevo_nombre'=>'required|string',
         ]);
         //Si falla la validación error.
         if ($validator->fails()) {
             return response()->json(['error' => $validator->messages()], 400);
         }
         //Buscamos el Habitacion
         $q="SELECT id FROM habitaciones WHERE nombre_habitacion='".$request->nombre_anterior."';";
         $idCasa = DB::select($q);
         $idC =$idCasa[0];
         $Habitacion = Habitacion::findOrfail($idC->id);
         //Actualizamos el Habitacion.
         $Habitacion->update([
             'nombre_habitacion' => $request->nuevo_nombre,
         ]);
         //Devolvemos los datos actualizados.
         return response()->json([
             'message' => 'Habitacion actualizado',
             'data' => $Habitacion,
             'ada'=>$myVariable
         ], Response::HTTP_CREATED);
     }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Habitacion  $Habitacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //Eliminamos en adafruit
        $objeto = new AdafruitController();
        $myVariable = $objeto->eliminarHabitacion($request->nombre_habitacion);
        //Buscamos el Habitacion
        $q="SELECT id FROM habitaciones WHERE nombre_habitacion='".$request->nombre_habitacion."';";
        $idCasa = DB::select($q);
        $idC =$idCasa[0];
        $Habitacion = Habitacion::findOrfail($idC->id);
        //Eliminamos el Habitacion
        $Habitacion->delete();
        //Devolvemos la respuesta
        return response()->json([
            'message' => 'Habitacion eliminado',
            'ada' => $myVariable,
        ], Response::HTTP_OK);
    }
}
