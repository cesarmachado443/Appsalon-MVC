let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id:'',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}
document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
})

function iniciarApp(){
    mostrarSeccion(); //Muestra y oculta las secciones
    tabs(); //Cambia la seccion cuando se presione los tabs
    botonesPaginador(); // agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    ConsultarApi();//consulta la api en el backen de php
    idCliente();
    nombreCliente();//Añade el nombre del cliente al objeto de cita
    seleccionarFecha();//Añade la fecha de la cita en el objeto
    seleccionarHora();//añande la hora de la cita en el objeto

    mostrarResumen();//muestra el resumen de la cita
}

function mostrarSeccion(){
    //Ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
    }
    //selecionar la sección con el paso
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar');

    // Quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }
    //resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach(boton => {
        boton.addEventListener('click', function(e){
            paso = parseInt( e.target.dataset.paso);

            mostrarSeccion();
            botonesPaginador();

        });
    });
}


function botonesPaginador(){
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');


    if(paso === 1){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');

    }else if(paso === 3){
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    }else{
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }
    mostrarSeccion();
}

function paginaSiguiente(){
     const paginaAnterior = document.querySelector('#anterior');
     paginaAnterior.addEventListener('click', function(){
        if(paso <= pasoInicial) return;
        paso--;

        botonesPaginador();
     })
}

function paginaAnterior(){
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function(){
       if(paso >= pasoFinal) return;
       paso++;

       botonesPaginador();
    })
}

async function ConsultarApi(){

    try {
        const url = `${location.origin}/api/servicios`;
        const resultado = await fetch(url);
        const servicios = await resultado.json();

        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios){
    servicios.forEach( servicio => {
        const {id, nombre,precio } = servicio;

        const nombreServicio = document.createElement('p');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('p');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = (`$${precio}`);

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        servicioDiv.onclick = function(){
                seleccinarServicio(servicio);
        };

        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

function seleccinarServicio(servicio){
    const { id } = servicio;
    const { servicios } = cita;
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);
    //conprobar si un servicio ya fue agregado
    if(servicios.some( agregado =>agregado.id === id )){
        //Eliminarlo
        cita.servicios = servicios.filter( agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    }else{
        //agregarlo
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }

    
    
    
    

}
function idCliente(){
    cita.id =  document.querySelector('#id').value;
    
}

function nombreCliente(){
    cita.nombre =  document.querySelector('#nombre').value;
    
}

function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');

    inputFecha.addEventListener('input',function(e){
        

        cita.fecha = inputFecha.value;

        const dia = new Date(e.target.value).getUTCDay();
        if([6,0].includes(dia)){
            e.target.value = '';
            mostrarAlerta('Fines de semana no permitidos', 'error','.formulario');
        }
    })

}
function seleccionarHora(){
    const inputHora = document.querySelector('#hora');
     
    inputHora.addEventListener('input', function(e){
        

        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];

        if(hora < 10 || hora > 18 ){
            e.target.value = '';
            mostrarAlerta('Hora no valida','error','.formulario');
            
        }else{
            cita.hora = e.target.value;
            // console.log(cita)
        }
    });
}

function mostrarAlerta(mensaje, tipo, elemento , desparece = true){

    //previe que se genere mas de una alerta
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia){
        alertaPrevia.remove();
     }

    //scripting para crear la alerta
    const alerta = document.createElement('div');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const Referencia = document.querySelector(elemento);
    Referencia.appendChild(alerta);

    //eliminar la alerta
    if(desparece){
            
        setTimeout(()=>{
            alerta.remove();
        },3000);
    }

}

function mostrarResumen(){
    
    const resumen = document.querySelector('.contenido-resumen');
    
    //Limpiar el contenido de resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }

    if( Object.values(cita).includes('') || cita.servicios.length === 0){
        
        mostrarAlerta('Hacen falta datos o servicios','error','.contenido-resumen ', false)
    return;
    }

    //formatear el div de resumen
    const{nombre, fecha, hora, servicios} = cita;

    //heading para servicios en resumen
    const headingServicios = document.createElement('h3');
    headingServicios.textContent = 'Resumen de servicios';
    resumen.appendChild(headingServicios);
    servicios.forEach(servicio =>{
        const {id, precio, nombre} =servicio;
        const contenedorServicio = document.createElement('div');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('p');
        textoServicio.textContent= nombre;

        const precioServicio = document.createElement('p');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);
        resumen.appendChild(contenedorServicio);
    })
    const headingCita = document.createElement('h3');
    headingCita.textContent = 'Resumen de cita';
    resumen.appendChild(headingCita);
    
    const nombreCliente = document.createElement('p');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    const fechaCita = document.createElement('p');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fecha}`;

    const horaCita = document.createElement('p');
    horaCita.innerHTML = `<span>Hora:</span> ${hora}`;

    //Boton para crear una cita
    const botonReservar = document.createElement('button');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);

}
async function reservarCita(){

    const { fecha, hora, servicios,id }  = cita;

    const idServicio = servicios.map(servicio => servicio.id);
   
    const datos = new FormData();
    
    datos.append('fecha',fecha);
    datos.append('hora',hora);
    datos.append('usuarioId',id);
    datos.append('servicios',idServicio);

    try {
        
            //petición hacia la api
            const url = `${location.origin}/api/citas`;

            const respuesta = await fetch(url,{
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();

            console.log(resultado.resultado);

            if(resultado.resultado){
                Swal.fire({
                    icon: "success",
                    title: "Cita creada",
                    text: "Tu cita fue creada exitosamente",
                    button: 'OK'
                }).then(()=>{
                    window.location.reload();
                })
            }
            // console.log(...datos);
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error...",
            text: "Error al guadar la cita!",
          });
    }
    

}