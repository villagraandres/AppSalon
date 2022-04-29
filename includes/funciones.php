<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function mostrarNotificacion($codigo){
    $mensaje='';
    switch($codigo){
        case 1:
            $mensaje='Contraseña reestablecida correctamente';    
            break;
        case 2:
            $mensaje='Actualizado correctamente';    
            break;  
        case 3:
            $mensaje='Eliminado correctamente';    
            break; 

            default:
            $mensaje=false;
            break;
    }

    return $mensaje;
}