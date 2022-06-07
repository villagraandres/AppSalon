<?php 
namespace Model;

class Servicio extends ActiveRecord{
    //DB
    protected static $tabla='servicios';
    protected static $columnasDB=['id','nombre','precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args=[])
    {
        $this->id=$args['id'] ?? null;
        $this->nombre=$args['nombre'] ?? '';
        $this->precio=$args['precio'] ?? '';
    }

    public function validar(){
        if(!$this->nombre){
           self::$alertas['error'][]='El Nombre del Servicio es obligatorio';
        }

        if(!$this->precio){
          self::$alertas['error'][]='El Precio del Servicio es obligatorio';
        }
        if(!$this->precio){
            self::$alertas['error'][]='El Precio del Servicio es obligatorio';
          }
          if(strlen($this->precio)>5){
            self::$alertas['error'][]='No debe ser mayor a 5 caracteres';
          }
       
        return self::$alertas;


    }
}