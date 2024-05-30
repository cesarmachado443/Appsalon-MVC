let paso=1;const pasoInicial=1,pasoFinal=3,cita={id:"",nombre:"",fecha:"",hora:"",servicios:[]};function iniciarApp(){mostrarSeccion(),tabs(),botonesPaginador(),paginaSiguiente(),paginaAnterior(),ConsultarApi(),idCliente(),nombreCliente(),seleccionarFecha(),seleccionarHora(),mostrarResumen()}function mostrarSeccion(){const e=document.querySelector(".mostrar");e&&e.classList.remove("mostrar");document.querySelector(`#paso-${paso}`).classList.add("mostrar");const t=document.querySelector(".actual");t&&t.classList.remove("actual");document.querySelector(`[data-paso="${paso}"]`).classList.add("actual")}function tabs(){document.querySelectorAll(".tabs button").forEach((e=>{e.addEventListener("click",(function(e){paso=parseInt(e.target.dataset.paso),mostrarSeccion(),botonesPaginador()}))}))}function botonesPaginador(){const e=document.querySelector("#anterior"),t=document.querySelector("#siguiente");1===paso?(e.classList.add("ocultar"),t.classList.remove("ocultar")):3===paso?(e.classList.remove("ocultar"),t.classList.add("ocultar"),mostrarResumen()):(e.classList.remove("ocultar"),t.classList.remove("ocultar")),mostrarSeccion()}function paginaSiguiente(){document.querySelector("#anterior").addEventListener("click",(function(){paso<=pasoInicial||(paso--,botonesPaginador())}))}function paginaAnterior(){document.querySelector("#siguiente").addEventListener("click",(function(){paso>=pasoFinal||(paso++,botonesPaginador())}))}async function ConsultarApi(){try{const e=`${location.origin}/api/servicios`,t=await fetch(e);mostrarServicios(await t.json())}catch(e){console.log(e)}}function mostrarServicios(e){e.forEach((e=>{const{id:t,nombre:o,precio:a}=e,n=document.createElement("p");n.classList.add("nombre-servicio"),n.textContent=o;const c=document.createElement("p");c.classList.add("precio-servicio"),c.textContent=`$${a}`;const i=document.createElement("DIV");i.classList.add("servicio"),i.dataset.idServicio=t,i.appendChild(n),i.appendChild(c),i.onclick=function(){seleccinarServicio(e)},document.querySelector("#servicios").appendChild(i)}))}function seleccinarServicio(e){const{id:t}=e,{servicios:o}=cita,a=document.querySelector(`[data-id-servicio="${t}"]`);o.some((e=>e.id===t))?(cita.servicios=o.filter((e=>e.id!==t)),a.classList.remove("seleccionado")):(cita.servicios=[...o,e],a.classList.add("seleccionado"))}function idCliente(){cita.id=document.querySelector("#id").value}function nombreCliente(){cita.nombre=document.querySelector("#nombre").value}function seleccionarFecha(){const e=document.querySelector("#fecha");e.addEventListener("input",(function(t){cita.fecha=e.value;const o=new Date(t.target.value).getUTCDay();[6,0].includes(o)&&(t.target.value="",mostrarAlerta("Fines de semana no permitidos","error",".formulario"))}))}function seleccionarHora(){document.querySelector("#hora").addEventListener("input",(function(e){const t=e.target.value.split(":")[0];t<10||t>18?(e.target.value="",mostrarAlerta("Hora no valida","error",".formulario")):cita.hora=e.target.value}))}function mostrarAlerta(e,t,o,a=!0){const n=document.querySelector(".alerta");n&&n.remove();const c=document.createElement("div");c.textContent=e,c.classList.add("alerta"),c.classList.add(t);document.querySelector(o).appendChild(c),a&&setTimeout((()=>{c.remove()}),3e3)}function mostrarResumen(){const e=document.querySelector(".contenido-resumen");for(;e.firstChild;)e.removeChild(e.firstChild);if(Object.values(cita).includes("")||0===cita.servicios.length)return void mostrarAlerta("Hacen falta datos o servicios","error",".contenido-resumen ",!1);const{nombre:t,fecha:o,hora:a,servicios:n}=cita,c=document.createElement("h3");c.textContent="Resumen de servicios",e.appendChild(c),n.forEach((t=>{const{id:o,precio:a,nombre:n}=t,c=document.createElement("div");c.classList.add("contenedor-servicio");const i=document.createElement("p");i.textContent=n;const r=document.createElement("p");r.innerHTML=`<span>Precio:</span> $${a}`,c.appendChild(i),c.appendChild(r),e.appendChild(c)}));const i=document.createElement("h3");i.textContent="Resumen de cita",e.appendChild(i);const r=document.createElement("p");r.innerHTML=`<span>Nombre:</span> ${t}`;const s=document.createElement("p");s.innerHTML=`<span>Fecha:</span> ${o}`;const d=document.createElement("p");d.innerHTML=`<span>Hora:</span> ${a}`;const l=document.createElement("button");l.classList.add("boton"),l.textContent="Reservar cita",l.onclick=reservarCita,e.appendChild(r),e.appendChild(s),e.appendChild(d),e.appendChild(l)}async function reservarCita(){const{fecha:e,hora:t,servicios:o,id:a}=cita,n=o.map((e=>e.id)),c=new FormData;c.append("fecha",e),c.append("hora",t),c.append("usuarioId",a),c.append("servicios",n);try{const e=`${location.origin}/api/citas`,t=await fetch(e,{method:"POST",body:c}),o=await t.json();console.log(o.resultado),o.resultado&&Swal.fire({icon:"success",title:"Cita creada",text:"Tu cita fue creada exitosamente",button:"OK"}).then((()=>{window.location.reload()}))}catch(e){Swal.fire({icon:"error",title:"Error...",text:"Error al guadar la cita!"})}}document.addEventListener("DOMContentLoaded",(function(){iniciarApp()}));