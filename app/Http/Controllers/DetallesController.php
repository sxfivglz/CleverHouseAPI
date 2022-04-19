<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Detalle;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DetallesController extends Controller
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
        //Listamos todos los Detalle
        return Detalle::get();
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
        $data = $request->only('casa_fk','dueno_fk');
        $validator = Validator::make($data, [
            'casa_fk' => 'required|string',
            'dueno_fk' => 'string',
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        $q="SELECT
        d.id AS IdDueno
      FROM duenos AS d 
      INNER JOIN users AS us
        ON d.usuario_fk = us.id
        where usuario_fk=(SELECT id FROM users WHERE email='".$request->email."');";
        $idUser = DB::select($q);
        $idU =$idUser[0];
        //Creamos el Detalle en la BD
        $val = Detalle::create([
            'casa_fk' => $request->casa_fk,
            'dueno_fk'=>$idU->IdDueno,
        ]);
        //Respuesta en caso de que todo vaya bien.
        return response()->json([
            'message' => 'Detalle registrada',
            'data' => $val
        ], Response::HTTP_OK);
    }
    
    public function storeInv(Request $request)
    {
        //Validamos los datos
        $data = $request->only('casa_fk','dueno_fk','invitado_fk');
        $validator = Validator::make($data, [
            'casa_fk' => 'string',
            'dueno_fk'=>'string',
            'invitado_fk'=>'string'
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        $q="SELECT
        d.id AS IdDueno
      FROM duenos AS d 
      INNER JOIN users AS us
        ON d.usuario_fk = us.id
        where usuario_fk=(SELECT id FROM users WHERE email='".$request->emailDueno."');";
        $idUser = DB::select($q);
        $idU =$idUser[0];

        $q2=" SELECT
        i.id AS IdInvitado
      FROM invitados AS i 
      INNER JOIN users AS us
        ON i.usuario_fk = us.id
        where usuario_fk=(SELECT id FROM users WHERE email='".$request->emailInvitado."');";
        $idUserI = DB::select($q2);
        $idUs =$idUserI[0];

        $q3="SELECT id FROM casas WHERE nombre_casa='".$request->nombre_casa."';";
        $idCasa = DB::select($q3);
        $idC =$idCasa[0];
        //Creamos el Detalle en la BD
        $qUpdate=" UPDATE detalles
        SET invitado_fk =".$idUs->IdInvitado."
        WHERE casa_fk=".$idC->id." AND dueno_fk=".$idU->IdDueno.";";
        $UPD = DB::select($qUpdate);
        $qSEL="SELECT * FROM detalles";
        $SELECT=DB::select($qSEL);
        //Respuesta en caso de que todo vaya bien.
        return response()->json([
            'message' => 'Detalle registrada',
            'data' => $SELECT
        ], Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Detalle  $Detalle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Bucamos el Detalleo
        $Detalle = Detalle::find($id);
        //Si el Detalleo no existe devolvemos error no encontrado
        if (!$Detalle) {
            return response()->json([
                'message' => 'Detalle no encontrada'
            ], 404);
        }
        //Si hay Detalleo lo devolvemos
        return $Detalle;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Detalle  $Detalle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validación de datos
        $data = $request->only('casa_fk','dueno_fk','invitado_fk');
        $validator = Validator::make($data, [
            'casa_fk' => 'required|string',
            'dueno_fk'=>'required|string',
            'invitado_fk'=>'required|string',
        ]);
        //Si falla la validación error.
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Buscamos el Detalleo
        $Detalle = Detalle::findOrfail($id);
        //Actualizamos el Detalleo.
        $Detalle->update([
            'casa_fk' => $request->casa_fk,
            'dueno_fk'=>$request->dueno_fk,
            'invitado_fk'=>$request->invitado_fk,
        ]);
        //Devolvemos los datos actualizados.
        return response()->json([
            'message' => 'Detalle actualizada',
            'data' => $Detalle
        ], Response::HTTP_CREATED);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Detalle  $Detalle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Buscamos el Detalleo
        $Detalle = Detalle::findOrfail($id);
        //Eliminamos el Detalleo
        $Detalle->delete();
        //Devolvemos la respuesta
        return response()->json([
            'message' => 'Detalle eliminada'
        ], Response::HTTP_OK);
    }
}
