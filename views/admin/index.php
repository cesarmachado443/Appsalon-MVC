<h1 class="nombre-pagina">Panel de control</h1>
<p class="descripcion-pagina"> Hola <span> <?php echo $nombre ?? ''; ?></span></p>

<?php
if(isset($_SESSION['admin'])){ ?>
<div class="barra-servicios">
    <a class="boton" href="/admin">Ver Citas</a>
    <a class="boton" href="/servicios">ver servicios</a>
    <a class="boton" href="/servicios/crear">Nuevo Servicio</a>
</div>    

<?php } ?>
<h2>Buscar citas</h2>
<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha:</label>
            <input type="date"
                id="fecha"
                name="fecha"
                value="<?php echo $fecha ?>"
                >
        </div>
    </form>
</div>
<?php
    if(count($citas) === 0){
        echo "<h2>No hay citas</h2>";
    }
?>

<div id="cita-admin">
<ul class="citas">
    <?php 
        $idCita = 0;
        foreach($citas as $key => $cita){
            if($idCita !== $cita->id){
                $total = 0;
                $idCita = $cita->id;      
    ?>
    <li>
        <p>ID: <span><?php echo $cita->id ?></span></p>
        <p>Hora: <span><?php echo $cita->hora ?></span></p>
        <p>Cliente: <span><?php echo $cita->cliente ?></span></p>
        <p>Email: <span><?php echo $cita->email ?></span></p>
                <h3>servicios</h3>
        <?php } //fin del if ?>
        <p class="servicio"> <?php echo $cita->servicio . " ". $cita->precio ?></p>
                
    <?php
        $actual = $cita->id;
        $proximo = $citas[$key + 1]->id ?? 0;
        $total+= floatval($cita->precio);

        if(esUltimo($actual,$proximo)){?>
            <p>Valor total: <span><?php echo $total; ?></span></p>

            <form action="/api/eliminar" method="POST">
                <input type="hidden" name="id" value="<?php echo $cita->id;?>">
                <input type="submit" class="boton-eliminar" value="Eliminar">
            </form>

            <?php }
    }; ?>

</ul>

</div>

<a href="/logout" class="boton">Cerrar sesión</a>


<?php
 $script = "<script src='build/js/buscador.js'></script>"
?>