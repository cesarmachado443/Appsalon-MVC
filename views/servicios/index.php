
<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administracion de servicios</p>
<?php
if(isset($_SESSION['admin'])){ ?>
<div class="barra-servicios">
    <a class="boton" href="/admin">Ver Citas</a>
    <a class="boton" href="/servicios">ver servicios</a>
    <a class="boton" href="/servicios/crear">Nuevo Servicio</a>
</div>    

<?php } ?>

<ul class="servicios">
    <?php foreach($servicios as $servicio): ?>
        <li>
            <p>Nombre: <span><?php echo $servicio->nombre; ?></span> </p>
            <p>Nombre: <span>$<?php echo $servicio->precio; ?></span> </p>

            <div class="acciones">
                <a class="boton" href="/servicios/actualizar?id=<?php echo $servicio->id ?>">Actualizar</a>

                <form action="/servicios/eliminar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $servicio->id ?>">
                    <input type="submit" value="Borrar" class="boton-eliminar">
                </form>
            </div>
        </li>

    <?php endforeach; ?>
</ul>

<a href="/logout" class="boton">Cerrar sesión</a>
