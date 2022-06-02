<?php

namespace Controllers;

use Model\Servicio;
use Model\Cita;
use Model\CitaServicio;

class APIController{
    public static function index(){
        $servicios=Servicio::all();

        echo json_encode($servicios);
    }
    public static function guardar(){
    //Almacena cita y devuelve Id
       $cita= new Cita($_POST);
      $resultado= $cita->guardar(); 
      $id=$resultado['id'];

        //Almacena la cita y el servicio
        $idServicios= explode(",", $_POST['servicios']);
        foreach($idServicios as $idServicio){
            $args=[
            'citaId'=>$id,
            'servicioId'=>$idServicio
                
            ];
            $citaServicio= new CitaServicio($args);

            $citaServicio->guardar();
        }
        //Retonarmos una respuesta
     

     echo json_encode(['resultado' => $resultado]); 
    }
}