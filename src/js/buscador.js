document.addEventListener('DOMContentLoaded',function(){
    iniciarApp();
});

function iniciarApp(){
    buscarPorfecha();
}

function buscarPorfecha(){
    const fecha = document.querySelector('#fecha');
    fecha.addEventListener('input',function(e){
        const fechaSelecionada= e.target.value;

        window.location = `?fecha=${fechaSelecionada}`;
    })

}