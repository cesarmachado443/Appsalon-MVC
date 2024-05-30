<h1 class="nombre-pagina">I Forgot password</h1>
<p class="descripcion-pagina">Restablece tu passsword escribiendo tu email a continuacion</p>
<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<form class="formulario" action="/olvide" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            placeholder="you email"
            name="email"
        >
    </div>
    <input type="submit" class="boton" value="Enviar instruciones">
</form>

<div class="acciones">
    <a href="/">i have a Account</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
</div>
