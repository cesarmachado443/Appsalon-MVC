
<h1 class="nombre-pagina">Actualizar Servicios</h1>
<p class="descripcion-pagina">Modifica los valores del formulario</p>
<?php
if(isset($_SESSION['admin'])){ ?>
<div class="barra-servicios">
    <a class="boton" href="/admin">Ver Citas</a>
    <a class="boton" href="/servicios">ver servicios</a>
    <a class="boton" href="/servicios/crear">Nuevo Servicio</a>
</div>    

<?php } ?>
<?php  include_once __DIR__ . '/../templates/alertas.php' ?>

<form  method="POST" class="formulario">

    <?php include_once __DIR__ . '/formulario.php' ?>
    
    <input type="submit" class="boton" value="Actualizar servicio">

    <a href="/servicios" class="boton-eliminar">cancelar</a>
     
</form>




<a href="/logout" class="boton">Cerrar sesión</a>
