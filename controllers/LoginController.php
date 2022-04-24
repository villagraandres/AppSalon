<?php 
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){
     $alertas=[];
     $auth= new Usuario;
    

        if($_SERVER['REQUEST_METHOD']=== 'POST'){
           $auth= new Usuario($_POST); //Auth tiene los valores que el usuario escribio
          $alertas= $auth->validarLogin();
          if(empty($alertas)){
            //Comprobar que exista el usuario
            $usuario= Usuario::where('email',$auth->email); //usuario tiene los valores que trajimos de la DB con where
            if($usuario){
                //Verificar el password
               if($usuario->comprobarPasswordyVerificado($auth->password)){
                   //Autenticar el usuario
                   session_start();
                   $_SESSION['id']=$usuario->id;
                   $_SESSION['nombre']=$usuario->nombre. " ". $usuario->apellido;
                   $_SESSION['email']=$usuario->email;
                   $_SESSION['login']=true;

                   //Redireccionar
                   if($usuario->admin ==="1"){
                       $_SESSION['admin']=$usuario->admin ?? null;
                       header('Location: /admin');  
                   }else{
                       header('Location: /cita');  
                   }

                   debuguear($_SESSION);
               }
            }else{
                Usuario::setAlerta('error','El usuario no existe');
            }
          }
   
        }
        $alertas=Usuario::getAlertas();
        $router->render('auth/login',[
            'alertas'=>$alertas,
            'auth'=>$auth
        ]);
    }

    public static function logout(){
        echo "desde logout";
        
    }

    public static function olvide(Router $router){
        $router->render('auth/olvide-contraseña');
    }
    public static function recuperar(){
        echo "desde recuperar";
    }
    public static function crear(Router $router) {
        $usuario = new Usuario;

        // Alertas vacias
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alerta este vacio
            if(empty($alertas)) {
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el Password
                    $usuario->hashPassword(); 

                    // Generar un Token único
                    $usuario->crearToken();

                    // Enviar el Email
                    $email = new Email( $usuario->email,$usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar();
                    if($resultado){
                         header('Location: /mensaje'); 
                     
                    }
            
                    // debuguear($usuario);
                 
                }
            }
        }
        


        $router->render('auth/crear-cuenta',[
            'usuario'=>$usuario,
            'alertas'=>$alertas
        ]);

    }

    public static function mensaje(Router $router) {

        $router->render('auth/mensaje',[
        
        ]);


        
    }
    public static function confirmar(Router $router){

        $alertas=[];
        $token=s($_GET['token']);
       $usuario= Usuario::where('token',$token);
      if(empty($usuario)){
          //Mostrar mensaje de error
         Usuario::setAlerta('error','Token no valido');
      }else{
          //Modificar a confirmado
          $usuario->confirmado="1";
          $usuario->token=null;
          $usuario->guardar();
          Usuario::setAlerta('exito','Cuenta comproboda correctamente');
        
      }
        $alertas=Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',[
            'alertas'=>$alertas
        ]);
    }
}