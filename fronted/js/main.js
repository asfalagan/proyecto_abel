const cuentaButton = document.getElementById('cuentaButton');
const cuentaButtonText = document.getElementById('cuentaButtonText');
const catalogo = localStorage.getItem('catalogo');
const nuevoEvento = document.getElementById('nuevoEvento');
window.addEventListener('load', renderPage)

function renderPage(){
    //compruebo si se trata de un usuario anonimo o logeado buscando un JWT en el localstorage
    if(JWT_exists()){
        let jwt = getLocalJWT();
        let decodedJWT = JWT_decode(jwt);
        if(decodedJWT.userData.nickname != null){
            cuentaButtonText.textContent = decodedJWT.userData.nickname;
        }else{
            cuentaButtonText.textContent = 'Perfil';
        }
        cuentaButton.href = './perfil.html';
        console.log(jwt);
        //pinto los eventos añadiendo un listener que redirija a la pagina del evento con sus carreras

        //si es organizador añado un boton para crear eventos
        if(decodedJWT.userData.isAdmin){
            nuevoEvento.addEventListener('click', () => {
                window.location.href = './registroEvento.html';
            })
        }
    }else{
        //navegacion anonima
        alert('Debes iniciar sesión para acceder a todo el contenido de esta aplicación');
        cuentaButtonText.textContent = 'Login';
        cuentaButton.href = './login.html';
        //pinto los eventos NO añadiendo un listener que redirija a la pagina del evento con sus carreras
    }
    
}
async function renderEvents(){
    const url = 'http://localhost:3000/backend/api_racebook/eventos/';
    fetch(url,{
        method: 'GET',
        headers:{
            'Content-Type': 'application/json;utf-8',
        },
        mode:'cors',
    })
    .then(response => {
        if(response.status == 201){
            return response.json();
        }else{
            window.location.href = '.index.html';
        }
    })
    .then(data =>{
        for (let evento in data){
            console.log(evento);
        }
    })
}



