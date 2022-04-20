<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class SensoresController extends Controller
{
    //
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
        //Listamos todos los Sensor
        return Sensor::get();
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
        $myVariable = $objeto->crearSensor($request->habitacion,$request->nombre_sensor);
        //Validamos los datos
        $data = $request->only('nombre_sensor');
        $validator = Validator::make($data, [
            'nombre_sensor' => 'required|max:50|string',
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Creamos el Sensoro en la BD
        $val = Sensor::create([
            'nombre_sensor' => $request->nombre_sensor,
        ]);
        //Respuesta en caso de que todo vaya bien.
        return response()->json([
            'message' => 'Sensor registrado',
            'data' => $val,
            'ada'=>$myVariable
        ], Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sensor  $Sensor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Bucamos el Sensoro
        $Sensor = Sensor::find($id);
        //Si el Sensoro no existe devolvemos error no encontrado
        if (!$Sensor) {
            return response()->json([
                'message' => 'Sensor no encontrado'
            ], 404);
        }
        //Si hay Sensoro lo devolvemos
        return $Sensor;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sensor  $Sensor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //Insertamos en adafruit
        $objeto = new AdafruitController();
        $myVariable = $objeto->modificarSensor($request->habitacion,$request->nombre,$request->nombre_sensor);
        //Validación de datos
        $data = $request->only('nombre_sensor');
        $validator = Validator::make($data, [
            'nombre_sensor' => 'required|max:50|string',
        ]);
        //Si falla la validación error.
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Buscamos el Sensor
        $q2="SELECT id FROM sensores WHERE nombre_sensor='".$request->nombre."';";
        $idSen = DB::select($q2);
        $idS =$idSen[0];
        $Sensor = Sensor::findOrfail($idS->id);
        //Actualizamos el Sensoro.
        $Sensor->update([
            'nombre_sensor' => $request->nombre_sensor,
        ]);
        //Devolvemos los datos actualizados.
        return response()->json([
            'message' => 'Sensor actualizado',
            'data' => $Sensor,
            'ada'=>$myVariable
        ], Response::HTTP_CREATED);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sensor  $Sensor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //Eliminamos en adafruit
        $objeto = new AdafruitController();
        $myVariable = $objeto->eliminarSensor($request->habitacion,$request->nombre);
        //Buscamos el Sensor
        $q2="SELECT id FROM sensores WHERE nombre_sensor='".$request->nombre."';";
        $idSen = DB::select($q2);
        $idS =$idSen[0];
        $Sensor = Sensor::findOrfail($idS->id);
        //Eliminamos el Sensor
        $Sensor->delete();
        //Devolvemos la respuesta
        return response()->json([
            'message' => 'Sensor eliminado',
            'ada'=>$myVariable
        ], Response::HTTP_OK);
    }
}
