<?php

namespace Controllers;

use Model\Servicio;
use Model\Cita;
use Model\citaServicio;

class APIController{
    public static function index(){
        $servicios=Servicio::all();

        echo json_encode($servicios, JSON_UNESCAPED_UNICODE);
    }
    public static function guardar(){
    //Almacena cita y devuelve Id
       $cita= new Cita($_POST);
      $resultado= $cita->guardar(); 
      $id=$resultado['id'];

        //Almacena la cita y el servicio
        //Convertimos el string de servicios en un arreglo
        $idServicios= explode(",", $_POST['servicios']);
        foreach($idServicios as $idServicio){
            $args=[
            'citaId'=>$id,
            'servicioId'=>$idServicio
                
            ];
            $citaServicio= new citaServicio($args);

            $citaServicio->guardar();
        }
        //Retonarmos una respuesta
     

        echo json_encode(['resultado' => $resultado]); 
        }

   public static function eliminar(){
       if($_SERVER['REQUEST_METHOD']==='POST'){
           $id=$_POST['id'];
           $cita=Cita::find($id);
           
           
          $resultado=$cita->eliminar();
            header('Location:'. $_SERVER['HTTP_REFERER']) ;
           echo json_encode(['resultado'=>$_POST]);
       }
   }
}
