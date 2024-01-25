//variables para los campos del formulario

const email = document.getElementById('email');
const passwd = document.getElementById('password');
const loginAlert = document.getElementById('login-alert');
const iniciarSesion = document.getElementById('iniciar-sesion');

//evento para validar los datos del formulario
iniciarSesion.addEventListener('click', validarDatos);


async function validarDatos(e){
    const url = 'http://localhost:3000/backend/api_racebook/login/';
    let emailValue = email.value;
    let passwdValue = passwd.value;
    //compruebo que esten seteados los campos
    if((emailValue != '') && (passwdValue != '')){
        //consulto en el servidor si existe el usuario
        const data = {
            email: emailValue,
            passwd: passwdValue
        }
        fetch(url, {
            method: 'POST',
            headers:{
                'Content-Type': 'application/json;utf-8',
            },
            body: JSON.stringify(data),
            mode: 'cors',
        })
        .then(response => {
            console.log(response);
            if(response.status == 201){
                window.location.href = './main.html';
                
            }else if(response.status == 401){
                loginAlert.innerHTML='Correo electrónico o contraseña incorrectos';
            }
        })
    }else{
        loginAlert.innerHTML='Completa los campos para iniciar sesión';
    }
}