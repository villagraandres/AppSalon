<?php 
namespace Controllers;

use Classes\email;
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
       
        session_start();
        $_SESSION=[];
       header('Location: /');
        
    }

    public static function olvide(Router $router){

        $alertas=[];
        if($_SERVER['REQUEST_METHOD']==='POST'){
          $auth= new Usuario($_POST);
          $alertas= $auth->validarEmail();
            if(empty($alertas)){

                $usuario=Usuario::where('email',$auth->email);

                if($usuario && $usuario->confirmado==="1"){
                   //Generar token
                   $usuario->crearToken();
                   $usuario->guardar();
                    //Enviar el email
                 $email= new Email($usuario->email,$usuario->nombre,$usuario->token);
                 $email->enviarInstrucciones();
                    //Alerta de exito
                Usuario::setAlerta('exito','Hemos enviado las instrucciones a tu email');
      
                    

                }else{
                   Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                  
                }
            }
        }
        $alertas= Usuario::getAlertas();
        $router->render('auth/olvide-contraseña',[
            'alertas'=>$alertas
        ]);
    }
    public static function recuperar(Router $router){
     $alertas=[];
     $error=false;
    $token=s($_GET['token']);
    //Buscar usuario
    $usuario= Usuario::where('token',$token);

        if(empty($usuario)){
            Usuario::setAlerta('error','Token No Valido');
            $error=true;
        }
        if($_SERVER['REQUEST_METHOD']==='POST'){
            //Leer y guardar la contraseña
            
            $password= new Usuario($_POST);
            $alertas= $password->validarPassword();


            if($password->password===$_POST['password2']){
                if(empty($alertas)){
                    $usuario->password=null;
                    $usuario->password=$password->password;
                    $usuario->hashPassword();    
                    $usuario->token=null;
                 $resultado= $usuario->guardar();
                 if($resultado){
                     header('Location: /?resultado=1');
                 }
            }
        }else{
                Usuario::setAlerta('error','Las contraseñas no son iguales');
            }
          
            

               
            }
        

       
        $alertas= Usuario::getAlertas();
        $router->render('auth/recuperar-contraseña',[
            'alertas'=>$alertas,
            'error'=>$error
        ]);
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
                   /*  $email = new Email( $usuario->email,$usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion(); */

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
       
           //Autenticar el usuario
            //Autenticar el usuario
            if(!$_SESSION['nombre']){
                session_start();
            }
            
            $_SESSION['id']=$usuario->id;
            $_SESSION['nombre']=$usuario->nombre. " ". $usuario->apellido;
            $_SESSION['email']=$usuario->email;
            $_SESSION['login']=true;
         

        
      }
        $alertas=Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',[
            'alertas'=>$alertas
        ]);
    }
}