<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Registro;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class RegistrosController extends Controller
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
        //Listamos todos los Registro
        return Registro::get();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //necesita que se envie el nombre del feed
    public function storeRead(Request $request)
    {
        //Insertamos en adafruit, ne
        $objeto = new AdafruitController();
        $myVariable = $objeto->ultimo_data($request->feed);
        //Validamos los datos
        $data = $request->only('medicion','detalle_sensor_fk','columna_1');
        $validator = Validator::make($data, [
            'medicion' => 'string',
            'detalle_sensor_fk'=>'required|string',
            'columna_1'=>'string',
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Creamos el Registros en la BD
        $val = Registro::create([
            'medicion' => $myVariable->value,
            'detalle_sensor_fk'=>$request->detalle_sensor_fk,
            'columna_1'=>"medicion ".$request->feed,
        ]);
    
        //Respuesta en caso de que todo vaya bien.
        return response()->json([
            'message' => 'Dato registrado',
            'data' => $val,
            'ada_res'=>$myVariable
        ], Response::HTTP_OK);
    }
    //Encendido o apagado
    public function storeData(Request $request)
    {
        //Insertamos en adafruit
        $objeto = new AdafruitController();
        $myVariable = $objeto->cambiarLed($request->feed);
        $obj=$myVariable->object();
        $est=$obj->value;
        if($est=="0");
        //Validamos los datos
        $data = $request->only('medicion','detalle_sensor_fk','columna_1');
        $validator = Validator::make($data, [
            'medicion' => 'string',
            'detalle_sensor_fk'=>'required|string',
            'columna_1'=>'string',
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Creamos el Registros en la BD
        $val = Registro::create([
            'medicion' => $myVariable->value,
            'detalle_sensor_fk'=>$request->detalle_sensor_fk,
            'columna_1'=>"medicion ".$request->feed,
        ]);
        
        //Respuesta en caso de que todo vaya bien.
        return response()->json([
            'message' => 'Dato registrado',
            'data' => $val,
            'ada_res'=>$myVariable
        ], Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Registro  $Registro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Bucamos el Registroso
        $Registro = Registro::find($id);
        //Si el Registroso no existe devolvemos error no encontrado
        if (!$Registro) {
            return response()->json([
                'message' => 'Dato no encontrado'
            ], 404);
        }
        //Si hay Registros lo devolvemos
        return $Registro;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Registro  $Registro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Insertamos en adafruit
        $objeto = new AdafruitController();
        $myVariable = $objeto->ultimo_data($request->feed);
        //Validación de datos
        $data = $request->only('medicion','detalle_sensor_fk','columna_1');
        $validator = Validator::make($data, [
            'medicion' => 'required|string',
            'detalle_sensor_fk'=>'required|string',
            'columna_1'=>'string',
        ]);
        //Si falla la validación error.
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Buscamos el Registro
        $Registro = Registro::findOrfail($id);
        //Actualizamos el Registroso.
        $Registro->update([
            'medicion' => $request->medicion,
            'detalle_sensor_fk'=>$request->detalle_sensor_fk,
            'columna_1'=>$request->columna_1,
        ]);
        //Devolvemos los datos actualizados.
        return response()->json([
            'message' => 'Dato actualizado',
            'data' => $Registro,
            'ada_res'=>$myVariable
        ], Response::HTTP_CREATED);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Registro  $Registro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Buscamos el Registroso
        $Registro = Registro::findOrfail($id);
        //Eliminamos el Registroso
        $Registro->delete();
        //Devolvemos la respuesta
        return response()->json([
            'message' => 'Dato eliminado'
        ], Response::HTTP_OK);
    }
}
