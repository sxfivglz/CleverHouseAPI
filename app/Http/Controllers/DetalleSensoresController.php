<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DetalleSensor;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DetalleSensoresController extends Controller
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
        //Listamos todos los DetalleSensor
        return DetalleSensor::get();
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
        $data = $request->only('nombre_sensor','nombre_habitacion');
        $validator = Validator::make($data, [
            'nombre_sensor' => 'required|string',
            'nombre_habitacion'=>'required|string',
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        $q="SELECT
        dh.id 
        FROM detalle_habitaciones AS dh 
        INNER JOIN habitaciones AS h
          ON dh.habitacion_fk = h.id
          where h.nombre_habitacion='".$request->nombre_habitacion."';";
        $idHab = DB::select($q);
        $idH =$idHab[0];

        $q2="SELECT id FROM sensores WHERE nombre_sensor='".$request->nombre_sensor."';";
        $idSen = DB::select($q2);
        $idS =$idSen[0];
        //Creamos el DetalleSensor en la BD
        $val = DetalleSensor::create([
            'sensor_fk' => $idS->id,
            'detalle_habitacion_fk'=>$idH->id,
        ]);
        //Respuesta en caso de que todo vaya bien.
        return response()->json([
            'message' => 'DetalleSensor registrado',
            'data' => $val
        ], Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetalleSensor  $DetalleSensor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Bucamos el DetalleSensoro
        $DetalleSensor = DetalleSensor::find($id);
        //Si el DetalleSensoro no existe devolvemos error no encontrado
        if (!$DetalleSensor) {
            return response()->json([
                'message' => 'DetalleSensor no encontrado'
            ], 404);
        }
        //Si hay DetalleSensoro lo devolvemos
        return $DetalleSensor;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetalleSensor  $DetalleSensor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validación de datos
        $data = $request->only('sensor_fk','detalle_habitacion_fk');
        $validator = Validator::make($data, [
            'sensor_fk' => 'required|string',
            'detalle_habitacion_fk'=>'required|string',
        ]);
        //Si falla la validación error.
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Buscamos el DetalleSensoro
        $DetalleSensor = DetalleSensor::findOrfail($id);
        //Actualizamos el DetalleSensoro.
        $DetalleSensor->update([
            'sensor_fk' => $request->sensor_fk,
            'detalle_habitacion_fk'=>$request->detalle_habitacion_fk,
        ]);
        //Devolvemos los datos actualizados.
        return response()->json([
            'message' => 'DetalleSensor actualizado',
            'data' => $DetalleSensor
        ], Response::HTTP_CREATED);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetalleSensor  $DetalleSensor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Buscamos el DetalleSensoro
        $DetalleSensor = DetalleSensor::findOrfail($id);
        //Eliminamos el DetalleSensoro
        $DetalleSensor->delete();
        //Devolvemos la respuesta
        return response()->json([
            'message' => 'DetalleSensor eliminado'
        ], Response::HTTP_OK);
    }
}
