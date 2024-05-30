<h1 class="nombre-pagina">Recuperar cuenta</h1>
<p class="descripcion-pagina">LLena el siguiente formulario para recuperar cuenta</p>
<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<?php if(!$error): ?>
<form  class="formulario" method="POST">

    <div class="campo">
        <label for="password">Password</label>
        <input type="password"
                id="password"
                name="password"
                placeholder="Tu nuevo password">
    </div>
    <input type="submit" value="Guarda Nuevo password" class="boton">
</form>

<?php endif; ?>
<div class="acciones">
    <a href="/">i have a Account</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
</div>
