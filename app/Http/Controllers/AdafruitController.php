<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

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
        $nomFeed=self::feedId($feed);
        //http client documentacion laravel
        $url="https://io.adafruit.com/api/v2/nayelireyes/feeds/".$nomFeed."/data/last/";
        $response=Http::get($url,[
            'X-AIO-Key'=>env('ADAFRUIT_KEY'),
        ]);
        $obj=$response->object();
        if($nomFeed=="temperatura"){
            $valor=$obj->value;
            if($valor>=35){
                $ven=self::Abrir('ventana');
                return 'ventana abierta debido a la temperatura';
            }
        }
        return $obj;
    }
    //para la comparacion
    public function dato_keypad($feed){
        $nomFeed=self::feedId($feed);
        //http client documentacion laravel
        $url="https://io.adafruit.com/api/v2/nayelireyes/feeds/".$nomFeed."/data/last/";
        $response=Http::get($url,[
            'X-AIO-Key'=>env('ADAFRUIT_KEY'),
        ]);
        $obj=$response->object();
        $val=$obj->value;
        return $val;
    }

    //para el on/off
    public function cambiarLed($feed){
        $nomFeed=self::feedId($feed);
        //mandar llamar parra tomar ultimo dato, y en base a eso negar el valor para insertarlo
        $query=self::ultimo_data($nomFeed);
        $estado=null;
        if($query->value == "1"){
            $estado="0";
        }else if($query->value=="0"){
            $estado="1";
        }
        $response=Http::post('https://io.adafruit.com/api/v2/nayelireyes/feeds/'.$nomFeed.'/data', 
        [
            'X-AIO-Key'=>env('ADAFRUIT_KEY'),
            'value'=>$estado,
        ]);
        return $response;
    }
    public function Cerrar($feed){
        $nomFeed=self::feedId($feed);
        //mandar llamar parra tomar ultimo dato, y en base a eso negar el valor para insertarlo
        $response=Http::post('https://io.adafruit.com/api/v2/nayelireyes/feeds/'.$nomFeed.'/data', 
        [
            'X-AIO-Key'=>env('ADAFRUIT_KEY'),
            'value'=>0,
        ]);
        return $response;
    }
    public function Abrir($feed){
        $nomFeed=self::feedId($feed);
        //mandar llamar parra tomar ultimo dato, y en base a eso negar el valor para insertarlo
        $response=Http::post('https://io.adafruit.com/api/v2/nayelireyes/feeds/'.$nomFeed.'/data', 
        [
            'X-AIO-Key'=>env('ADAFRUIT_KEY'),
            'value'=>1,
        ]);
        return $response;
    }
    public function getTemperatura($nombre){
        //http client documentacion laravel
        $nomFeed=self::feedId($nombre);
        $url="https://io.adafruit.com/api/v2/nayelireyes/feeds/".$nomFeed."/data/last/";
        $response=Http::get($url,
        ['X-AIO-Key'=>env('ADAFRUIT_KEY')]);
        $obj=$response->object();
        return $obj->value;
    }
    //prueba
    public function getLuminosidad(){
        //http client documentacion laravel
        $url="https://io.adafruit.com/api/v2/nayelireyes/feeds/luminosidad/data/last/";
        $response=Http::get($url,
        ['X-AIO-Key'=>env('ADAFRUIT_KEY')]);
        $obj=$response->object();
        return $obj->value;
    }
    //crea un Sensor
    public function crearSensor($hab,$nombre){//returna objeto
        $nomFeed=self::feedId($nombre);
        $nomHab=self::feedId($hab);
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$nomHab."/feeds";
        $response=Http::post($url, 
        [
            'X-AIO-Key'=>env('ADAFRUIT_KEY'),
            'name'=>$nomFeed
        ]);
        return $response->object();
    }
    //para modificar una habitacion
    public function modificarHabitacion($nombre,$nuevoNombre){
        $nomFeed=self::feedId($nombre);
        $nomMod=self::feedId($nuevoNombre);
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$nomFeed;
        $response=Http::put($url, 
        [
            'X-AIO-Key'=>env('ADAFRUIT_KEY'),
            'name'=>$nomMod,
            'key'=>$nomMod
        ]);
        return $response->object();
    }
    //para eliminar una habitacion
    public function eliminarHabitacion($nombre){
        $nomFeed=self::feedId($nombre);
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$nomFeed;
        $response=Http::delete($url, 
        [
            'X-AIO-Key'=>env('ADAFRUIT_KEY')
        ]);
        return $response->object();
    }
    //para eliminar una habitacion
    public function eliminarSensor($hab,$nombre){
        $nomHab=self::feedId($hab);
        $nomFeed=self::feedId($nombre);
        $url="https://io.adafruit.com/api/v2/nayelireyes/feeds/".$nomHab.".".$nomFeed;
        $response=Http::delete($url, 
        [
            'X-AIO-Key'=>env('ADAFRUIT_KEY'),
        ]);
        return $response->object();
    }
    //modifica el nombre del sensor, hay que modificar tambien la key, de lo cotrario las consultas no se podran hacer
    public function modificarSensor($hab,$nombre,$nuevoNombre){
        $nomHab=self::feedId($hab);
        $nomFeed=self::feedId($nombre);
        $nomMod=self::feedId($nuevoNombre);
        $url="https://io.adafruit.com/api/v2/nayelireyes/feeds/".$nomHab.".".$nomFeed;
        $response=Http::put($url, 
        [
            'X-AIO-Key'=>env('ADAFRUIT_KEY'),
            'name'=>$nomMod,
            'key'=>$nomMod,
        ]);
        return $response->object();
    }
    //total de sensores en la casa
    public function listarHabitaciones(){//devuelve un arreglo
        $url="http://io.adafruit.com/api/v2/nayelireyes/groups";
        $response=Http::get($url, 
        [
            'X-AIO-Key'=>env('ADAFRUIT_KEY')
        ]);
        return $response->object();
    }
    //total de sensores en una habitacion
    public function listarSensores($hab){//devuelve un arreglo
        $nomHab=self::feedId($hab);
        $url="http://io.adafruit.com/api/v2/nayelireyes/groups/".$nomHab."/feeds";
        $response=Http::get($url, 
        [
            'X-AIO-Key'=>env('ADAFRUIT_KEY')
        ]);
        return $response->object();
    }
    //añade sensor a una habitacion
    public function añadirSensor($hab,$nombreSensor){//retorna objeto
        $nomHab=self::feedId($hab);
        $nomFeed=self::feedId($nombreSensor);
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$nomHab."/add";
        $response=Http::post($url,[
            'X-AIO-Key'=>env('ADAFRUIT_KEY'),
            'feed_key'=>$nomFeed
        ]);
        return $response->object();
    }
    //para quitar sensor de una habitacion, regresan a default
    public function cambiarSensor($hab,$nombreSensor){//retorna objeto
        $nomHab=self::feedId($hab);
        $nomFeed=self::feedId($nombreSensor);
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$nomHab."/remove";
        $response=Http::post($url,[
            'X-AIO-Key'=>env('ADAFRUIT_KEY'),
            'feed_key'=>$nomFeed
        ]);
        return $response->object();
    }

    public function añadirHab($nombre){
        $nomFeed=self::feedId($nombre);
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups";
        $response=Http::post($url,[
            'X-AIO-Key'=>env('ADAFRUIT_KEY'),
            'name'=>$nomFeed
        ]);
        return $response->object();
    }
    public function feedId($nombre){
        $feed = strtolower($nombre);
        $searchString = " ";
        $replaceString = "";
        $originalString = $feed; 
        $feedKey = str_replace($searchString, $replaceString, $originalString);
        
        $searchString = "ñ";
        $replaceString = "n";
        $originalString = $feedKey;
        $feedKey1 = str_replace($searchString, $replaceString, $originalString);

        $searchString = "ó";
        $replaceString = "o";
        $originalString = $feedKey1;
        $feedKey2 = str_replace($searchString, $replaceString, $originalString);

        $searchString = "á";
        $replaceString = "a";
        $originalString = $feedKey2;
        $feedKey3 = str_replace($searchString, $replaceString, $originalString);
        
        return $feedKey3;
    }
    public function historico($nombre){
        $nomFeed=self::feedId($nombre);
        //$url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$nomFeed."/feeds/";
        $url="https://io.adafruit.com/api/v2/nayelireyes/feeds/".$nomFeed."/data";
        $response=Http::get($url,[
            'X-AIO-Key'=>env('ADAFRUIT_KEY')
        ]);
        return $response->object();
    }
}