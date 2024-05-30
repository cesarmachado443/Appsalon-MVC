<h1 class="nombre-pagina">Create Account</h1>
<p class="descripcion-pagina">LLena el siguiente formulario para crear una cuenta</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<form class="formulario" method="POST" action="/crear-cuenta">

    <div class="campo">
        <label for="nombre">Name:</label>
        <input 
            type="text"
            id="nombre"
            name="nombre"
            placeholder="you name"
            value="<?php echo s($usuario->nombre) ?>"
        >
        
        
    </div>
    <div class="campo">
    <label for="apellido">Last name:</label>
        <input 
            type="text"
            id="apellido"
            name="apellido"
            placeholder="you name"
            value="<?php echo s($usuario->apellido) ?>"
        >
    </div>
    <div class="campo">
    <label for="telefono">Phone number:</label>
        <input 
            type="tel"
            id="telefono"
            name="telefono"
            placeholder="you name"
            value="<?php echo s($usuario->telefono) ?>"
        >
    </div>
    <div class="campo">
    <label for="email">Email:</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="you email"
            value="<?php echo s($usuario->email) ?>"
        >
    </div>
    <div class="campo">
    <label for="password">Password:</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="you password"
            value="<?php echo s($usuario->password) ?>"
        >
    </div>
    <input type="submit" value="Crear Cuenta" class="boton">


</form>

<div class="acciones">
    <a href="/">i have a Account</a>
    <a href="/olvide">¿Forgot your password?</a>
</div>