//variables para los campos del formulario

const email = document.getElementById('email');
const passwd = document.getElementById('password');
const loginAlert = document.getElementById('login-alert');
const iniciarSesion = document.getElementById('iniciar-sesion');

//evento para validar los datos del formulario
iniciarSesion.addEventListener('click', validarDatos);
//al presionar la tecla enter
const body = document.querySelector('body');
body.addEventListener('keydown', (event) => {
    if(event.key == 'Enter'){
        validarDatos();
    }
})
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
                return response.json();
                //window.location.href = './main.html';           
            }else if(response.status == 401){
                loginAlert.innerHTML='Correo electrónico o contraseña incorrectos';
            }
        })
        .then(data => {
            let jwt = data;
            if(data != undefined && data != null){
                localStorage.setItem('jwt', jwt);
                let decoded = JWT_decode(jwt);
                console.log(decoded);
                console.log(decoded.userData);
                if(!decoded.userData.completado){
                   
                    if(decoded.userData.isAdmin){
                        window.location.href = './completarOrganizador.html';
                    }else{
                        window.location.href = './completarUsuario.html';
                    }
                }else{
                    window.location.href = './main.html';
                }
            }else{
                loginAlert.innerHTML='Combinación de correo electrónico y contraseña incorrectos';
                console.log('El jwt tiene valor null o undefined');
            }
        })
    }else{
        loginAlert.innerHTML='Completa los campos para iniciar sesión';
    }
}