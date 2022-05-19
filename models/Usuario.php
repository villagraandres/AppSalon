<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es Obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es Obligatorio';
        }
         if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
        } 



        return self::$alertas;
    }

  
    // Revisa si el usuario ya existe
    public function existeUsuario() {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][] = 'El Usuario ya esta registrado';
        }

        return $resultado;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = uniqid();
    }
    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][]='El email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][]='La contraseña es obligatoria';
        }
        return self::$alertas;
    }
    public function comprobarPasswordyVerificado($password){
        $resultado=password_verify($password,$this->password);
        if(!$this->confirmado){
            self::$alertas['error'][]="Esta cuenta no ha sido confirmada";
        }if(!$resultado){
            self::$alertas['error'][]="La contraseña es incorrecta";
        }if($resultado && $this->confirmado ){
            return true;
        }
        }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][]='El email es obligatorio';
        }
        return self::$alertas;
    }
    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][]='La contraseña es obligatoria';
        }
        if(strlen($this->password)<6){
            self::$alertas['error'][]='La contraseña debe tener al menos 6 caracteres';
        }
        return self::$alertas;
    }
        
    }
    
    
   