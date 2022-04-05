<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dueno;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class DuenosController extends Controller
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
        //Listamos todos los duenoos
        return Dueno::get();
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
        $data = $request->only('nombre_dueno','usuario_fk','columna_1');
        $validator = Validator::make($data, [
            'nombre_dueno' => 'required|max:50|string',
            'usuario_fk'=>'required|string',
            'columna_1'=>'string',
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Creamos el duenoo en la BD
        $val = Dueno::create([
            'nombre_dueno' => $request->nombre_dueno,
            'usuario_fk'=>$request->usuario_fk,
            'columna_1'=>$request->columna_1,
        ]);
        //Respuesta en caso de que todo vaya bien.
        return response()->json([
            'message' => 'Dueño registrado',
            'data' => $val
        ], Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\dueno  $dueno
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Bucamos el duenoo
        $dueno = Dueno::find($id);
        //Si el duenoo no existe devolvemos error no encontrado
        if (!$dueno) {
            return response()->json([
                'message' => 'Dueño no encontrado'
            ], 404);
        }
        //Si hay duenoo lo devolvemos
        return $dueno;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\dueno  $dueno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validación de datos
        $data = $request->only('nombre_dueno','usuario_fk','columna_1');
        $validator = Validator::make($data, [
            'nombre_dueno' => 'required|max:50|string',
            'usuario_fk'=>'required|string',
            'columna_1'=>'string',
        ]);
        //Si falla la validación error.
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Buscamos el duenoo
        $dueno = Dueno::findOrfail($id);
        //Actualizamos el duenoo.
        $dueno->update([
            'nombre_dueno' => $request->nombre_dueno,
            'usuario_fk'=>$request->usuario_fk,
            'columna_1'=>$request->columna_1,
        ]);
        //Devolvemos los datos actualizados.
        return response()->json([
            'message' => 'Dueño actualizado',
            'data' => $dueno
        ], Response::HTTP_CREATED);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\dueno  $dueno
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Buscamos el duenoo
        $dueno = Dueno::findOrfail($id);
        //Eliminamos el duenoo
        $dueno->delete();
        //Devolvemos la respuesta
        return response()->json([
            'message' => 'Dueño eliminado'
        ], Response::HTTP_OK);
    }
}
