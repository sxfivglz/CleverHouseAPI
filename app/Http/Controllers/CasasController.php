<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Casa;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CasasController extends Controller
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
        //Listamos todos los Casaos
        return Casa::get();
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
        $data = $request->only('nombre_casa','direccion');
        $validator = Validator::make($data, [
            'nombre_casa' => 'required|max:50|string',
            'direccion'=>'required|string',
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Creamos el Casa en la BD
        $val = Casa::create([
            'nombre_casa' => $request->nombre_casa,
            'direccion'=>$request->direccion,
            
        ]);
        //Respuesta en caso de que todo vaya bien.
        return response()->json([
            'message' => 'Casa registrada',
            'data' => $val
        ], Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Casa  $Casa
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Bucamos el Casa
        $Casa = Casa::find($id);
        //Si el Casa no existe devolvemos error no encontrado
        if (!$Casa) {
            return response()->json([
                'message' => 'Casa no encontrada'
            ], 404);
        }
        //Si hay Casa lo devolvemos
        return $Casa;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Casa  $Casa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //Validación de datos
        $data = $request->only('nombre_casa','nuevo_nombre_casa','direccion');
        $validator = Validator::make($data, [
            'nombre_casa' => 'required|max:50|string',
            'nuevo_nombre_casa' => 'required|max:50|string',
            'direccion'=>'required|string',
        ]);
        //Si falla la validación error.
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Buscamos el Casa
        $q2="SELECT id FROM casas WHERE nombre_casa='".$request->nombre_casa."';";
        $idCasa = DB::select($q2);
        $idC =$idCasa[0];
        $Casa = Casa::findOrfail($idC->id);
        //Actualizamos el Casa.
        $Casa->update([
            'nombre_casa' => $request->nuevo_nombre_casa,
            'direccion'=>$request->direccion,
        ]);
        //Devolvemos los datos actualizados.
        return response()->json([
            'message' => 'Casa actualizada',
            'data' => $Casa
        ], Response::HTTP_CREATED);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Casa  $Casa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //Buscamos el Casa
        $q2="SELECT id FROM casas WHERE nombre_casa='".$request->nombre_casa."';";
        $idCasa = DB::select($q2);
        $idC =$idCasa[0];
        $Casa = Casa::findOrfail($idC->id);
        //Eliminamos el Casa
        $Casa->delete();
        //Devolvemos la respuesta
        return response()->json([
            'message' => 'Casa eliminada'
        ], Response::HTTP_OK);
    }
    //regresa un arreglo
    public function consultaCasaDuenos(Request $request)
    {
        $array=$array = $request->toArray();
        $q=
        "SELECT
        c.nombre_casa
      FROM detalles AS d 
      INNER JOIN casas AS c
        ON d.casa_fk = c.id
      INNER JOIN duenos AS du
        ON d.dueno_fk = du.id
      INNER JOIN users AS us
      ON du.id = us.id
        where du.usuario_fk=(SELECT id FROM users WHERE email='" . $array[0]['email'] . "');";
        $clave_base = DB::select($q);
        $clave=count($clave_base);
        if($clave!=0){
            return $clave_base;
        }else{
            return null;
        }
    }
    //regresa un arreglo
    public function consultaCasaInvitado(Request $request)
    {
        $array=$request->toArray();
        $q="SELECT
        c.nombre_casa
      FROM detalles AS d 
      INNER JOIN casas AS c
        ON d.casa_fk = c.id
      INNER JOIN invitados AS i
        ON d.invitado_fk = i.id
      INNER JOIN users AS us
      ON i.id = us.id
        where i.usuario_fk=(SELECT id FROM users WHERE email='".$array[0]['email']."');";
        $clave_base = DB::select($q);
        $clave=count($clave_base);
        if($clave!==0){
            return $clave_base;
        }else{
            return null;
        }
    }
}
