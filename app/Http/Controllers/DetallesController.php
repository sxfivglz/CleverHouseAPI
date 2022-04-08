<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Detalle;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

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
            'dueno_fk'=>'required|string',
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Creamos el Detalleo en la BD
        $val = Detalle::create([
            'casa_fk' => $request->casa_fk,
            'dueno_fk'=>$request->dueno_fk
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
            'casa_fk' => 'required|string',
            'dueno_fk'=>'required|string',
            'invitado_fk'=>'required|string'
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Creamos el Detalleo en la BD
        $val = Detalle::create([
            'casa_fk' => $request->casa_fk,
            'dueno_fk'=>$request->dueno_fk,
            'invitado_fk'=>$request->invitado_fk
        ]);
        //Respuesta en caso de que todo vaya bien.
        return response()->json([
            'message' => 'Detalle registrada',
            'data' => $val
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
