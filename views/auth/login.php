<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesíon con tus datos</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<form method="POST" class="formulario" action="/">
    <div class="campo">
            <label for="email">Email</label>
            <input 
                type="email"
                id= "email"
                placeholder="you email"
                name="email"
                value="<?php echo s( $auth->email) ?>"
            >
    </div>
    <div class="campo">
            <label for="password">Password</label>
            <input  
                type="password"
                name="password" 
                id="password"
                placeholder="You password">
    </div>

    <input type="submit" value="Iniciar Sesión" class="boton">

</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>

    <a href="/olvide">¿Olvidastes tu password</a>
</div>
<script type="module" src="https://unpkg.com/@splinetool/viewer@1.4.0/build/spline-viewer.js"></script>