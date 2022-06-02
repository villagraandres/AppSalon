<?php
namespace Controllers;
use MVC\Router;

class AdminController{
    public static function index(Router $router){
        if(!$_SESSION['nombre']){
            session_start();
        }
        //Consultar la DB
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
/*         $consulta .= " WHERE fecha =  '${fecha}' ";
 */      
        $router->render('admin/index',[
            'nombre'=>$_SESSION['nombre']
        ]);
    }
}