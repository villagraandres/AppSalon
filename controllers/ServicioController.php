<?php
namespace Controllers;

use Model\Servicio;
use Model\Usuario;
use MVC\Router;

class ServicioController{
    public static function index(Router $router){
        if(!$_SESSION['nombre']){
            session_start();
        }
        esAdmin();
        $resultado=$_GET['resultado'] ?? null;
        
        if($resultado==='1'){
           Usuario::setAlerta('exito','Servicio Creado correctamente');
       }else if($resultado==='2'){
        Usuario::setAlerta('exito','Servicio Actualizado correctamente');
       }else if($resultado==='3'){
        Usuario::setAlerta('error','Servicio Eliminado correctamente');
       }
       $alertas=Usuario::getAlertas();

       $servicios=Servicio::all();



       $router->render('admin/servicios',[
           'nombre'=>$_SESSION['nombre'],
           'alertas'=>$alertas,
           'servicios'=>$servicios
       ]);   
    }




    public static function crear(Router $router){
        if(!$_SESSION['nombre']){
            session_start();
        }
        esAdmin();
        $servicio=new Servicio;
        $alertas=[];


        if($_SERVER['REQUEST_METHOD']==='POST'){
            $servicio->sincronizar($_POST);
            $alertas=$servicio->validar();
            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios?resultado=1');
            }
        }



        $router->render('admin/crear',[
            'nombre'=>$_SESSION['nombre'],
            'servicio'=>$servicio,
            'alertas'=>$alertas
        ]); 
    }
    public static function actualizar(Router $router){
     if(!$_SESSION['nombre']){
            session_start();
    }
    esAdmin();
    if(!is_numeric($_GET['id'])) return;
    $alertas=[];
     $servicio=Servicio::find($_GET['id']);
     

    if($_SERVER['REQUEST_METHOD']==='POST'){

        $servicio->sincronizar($_POST);
        $alertas= $servicio->validar();

        if(empty($alertas)){
            $servicio->guardar();
            header('Location: /servicios?resultado=2');
        }
    }
    $router->render('admin/actualizar',[
         'nombre'=>$_SESSION['nombre'],
         'servicio'=>$servicio,
         'alertas'=>$alertas
           
     ]); 
    }

    public static function eliminar(){
        esAdmin();
        if($_SERVER['REQUEST_METHOD']==='POST'){

        $id=$_POST['id'];
        $servicio=Servicio::find($id);
        $servicio->eliminar();
        header('Location: /servicios?resultado=3');

    }
}
}