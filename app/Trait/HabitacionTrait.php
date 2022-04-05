<?php 
namespace App\Traits; 

use Illuminate\Support\Facades\Http;

trait UserTrait { 

    public function añadirHab($nombre){
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups";
        $response=Http::post($url,[
            'X-AIO-Key'=>'aio_DOTt444SWquVpsQuHQNbA9ayb9pm',
            'name'=>$nombre
        ]);
        return $response->object();
    }
    //total de habitaciones en la casa
    public function listarHabitaciones(){//devuelve un arreglo
        $url="http://io.adafruit.com/api/v2/nayelireyes/groups";
        $response=Http::get($url, 
        [
            'X-AIO-Key'=>'aio_DOTt444SWquVpsQuHQNbA9ayb9pm'
        ]);
        return $response->object();
    }
    //para eliminar una habitacion
    public function eliminarHabitacion($nombre){
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$nombre;
        $response=Http::delete($url, 
        [
            'X-AIO-Key'=>'aio_DOTt444SWquVpsQuHQNbA9ayb9pm'
        ]);
        return $response->object();
    }
    //para modificar una habitacion
    public function modificarHabitacion($nombre,$nuevoNombre){
        $url="https://io.adafruit.com/api/v2/nayelireyes/groups/".$nombre;
        $response=Http::put($url, 
        [
            'X-AIO-Key'=>'aio_DOTt444SWquVpsQuHQNbA9ayb9pm',
            'name'=>$nuevoNombre,
            'key'=>$nuevoNombre
        ]);
        return $response->object();
    }
} 