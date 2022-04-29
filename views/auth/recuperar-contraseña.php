<h1 class="nombre-pagina">Recuperar Contraseña</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña</p>
<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if($error) return?>
<form action="" class="formulario" method="POST">
    <div class="campo">
        <label for="contraseña">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Tu nueva contraseña">
    </div>
    <input type="submit" class="boton" value="Restablecer contraseña">
</form>

<div class="acciones">
<a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
<a href="/crear-cuenta">¿Aún no tienes cuenta? Crea una!</a>
</div>
