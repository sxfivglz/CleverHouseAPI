<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Models\Registro;


class AdafruitController extends Controller
{
    //FEEDS: 
    //luminosidad 
    //distancia
    //temperatura
    //luz
    //ledChart digital
    //ledBtn es encendido o apagado

    //hacer un request del feed que se va a modificar

    //trae el ultimo registro de un sensor
    public function ultimo_data($feed){
        //http client documentacion laravel
        $url="https://io.adafruit.com/api/v2/nayelireyes/feeds/".$feed."/data/last/";
        $response=Http::get($url,[
            'X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA',
        ]);
        $obj=$response->object();

        return $obj;
    }

    //para el on/off
    public function cambiarLed($feed){
        //mandar llamar parra tomar ultimo dato, y en base a eso negar el valor para insertarlo
        $query=self::ultimo_data($feed);
        $estado=null;
        if($query->value == "1"){
            $estado="0";
        }else if($query->value=="0"){
            $estado="1";
        }
        $response=Http::post('https://io.adafruit.com/api/v2/nayelireyes/feeds/'.$feed.'/data', 
        [
            'X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA',
            'value'=>$estado,
        ]);

        return $response;
    }
    
    public function getDistancia(){
        //http client documentacion laravel
        $url="https://io.adafruit.com/api/v2/nayelireyes/feeds/distancia/data/last/";
        $response=Http::get($url,
        ['X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA']);
        $obj=$response->object();
        return $obj->value;
    }
    //prueba
    public function getLuminosidad(){
        //http client documentacion laravel
        $url="https://io.adafruit.com/api/v2/nayelireyes/feeds/luminosidad/data/last/";
        $response=Http::get($url,
        ['X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA']);
        $obj=$response->object();
        return $obj->value;
    }
    //crea un Sensor
    public function crearSensor($hab,$nombre){//returna objeto
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$hab."/feeds";
        $response=Http::post($url, 
        [
            'X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA',
            'name'=>$nombre
        ]);
        return $response->object();
    }
    //para modificar una habitacion
    public function modificarHabitacion($nombre,$nuevoNombre){
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$nombre;
        $response=Http::put($url, 
        [
            'X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA',
            'name'=>$nuevoNombre,
            'key'=>$nuevoNombre
        ]);
        return $response->object();
    }
    //para eliminar una habitacion
    public function eliminarHabitacion($nombre){
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$nombre;
        $response=Http::delete($url, 
        [
            'X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA'
        ]);
        return $response->object();
    }
    //para eliminar una habitacion
    public function eliminarSensor($nombre){
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$nombre;
        $response=Http::delete($url, 
        [
            'X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA'
        ]);
        return $response->object();
    }
    //modifica el nombre del sensor, hay que modificar tambien la key, de lo cotrario las consultas no se podran hacer
    public function modificarSensor($hab,$nombre,$nuevoNombre){
        $url="https://io.adafruit.com/api/v2/nayelireyes/feeds/".$hab.".".$nombre;
        $response=Http::put($url, 
        [
            'X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA',
            'name'=>$nuevoNombre,
            'key'=>$nuevoNombre,
        ]);
        return $response->object();
    }
    //total de sensores en la casa
    public function listarHabitaciones(){//devuelve un arreglo
        $url="http://io.adafruit.com/api/v2/nayelireyes/groups";
        $response=Http::get($url, 
        [
            'X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA'
        ]);
        return $response->object();
    }
    //total de sensores en una habitacion
    public function listarSensores($hab){//devuelve un arreglo
        $url="http://io.adafruit.com/api/v2/nayelireyes/groups/".$hab."/feeds";
        $response=Http::get($url, 
        [
            'X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA'
        ]);
        return $response->object();
    }
    //añade sensor a una habitacion
    public function añadirSensor($hab,$nombreSensor){//retorna objeto
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$hab."/add";
        $response=Http::post($url,[
            'X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA',
            'feed_key'=>$nombreSensor
        ]);
        return $response->object();
    }
    //para quitar sensor de una habitacion, regresan a default
    public function cambiarSensor($hab,$nombreSensor){//retorna objeto
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$hab."/remove";
        $response=Http::post($url,[
            'X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA',
            'feed_key'=>$nombreSensor
        ]);
        return $response->object();
    }

    public function añadirHab($nombre){
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups";
        $response=Http::post($url,[
            'X-AIO-Key'=>'aio_bpxh47xyPzAUqcU4YSgvEL0vJPCA',
            'name'=>$nombre
        ]);
        return $response->object();
    }
}