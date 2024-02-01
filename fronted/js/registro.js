//variables para los campos del formulario

const email = document.getElementById('email');
const passwd = document.getElementById('password');
const passwd2 = document.getElementById('password2');
const loginAlert = document.getElementById('login-alert');
const registrar = document.getElementById('iniciar-sesion');
const isAdmin = document.getElementById('isAdmin');

//evento para validar los datos del formulario
registrar.addEventListener('click', validarDatos);
console.log(email.value);

//funcion para validar los datos del formulario y decidir si se envia o no
function validarDatos(e){
    let emailValue = email.value;
    let passwdValue = passwd.value;
    let passwd2Value = passwd2.value;
    let isAdminValue = isAdmin.checked; 
 
    var regPasswd = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?#&])([A-Za-z\d$@$!%*?&#]|[^ ]){8,16}$/;
    var regEmail = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
    //me falta un campo por rellenar
    if(emailValue == '' || passwdValue == '' || passwd2Value == ''){
        loginAlert.innerHTML = 'Rellena todos los campos';
    }
    //el email no es valido
    else if(!regEmail.test(emailValue)){
        loginAlert.innerHTML = 'El email no es válido';
    }
    //la contraseña es demasiado debil
    else if(!regPasswd.test(passwdValue)){
        loginAlert.innerHTML = 'La contraseña es demasiado débil';
    }
    //las contraseñas no coinciden
    else if(passwdValue != passwd2Value){
        loginAlert.innerHTML = 'Las contraseñas no coinciden';
    }
    //todo correcto y se envia el formulario
    else{
        loginAlert.innerHTML = '';
        const data = {
            email: emailValue,
            password: passwdValue,
            isAdmin: isAdminValue,
        }
        console.log(data);
        signUp(data);
    }
}


const url = 'http://localhost:3000/backend/api_racebook/signup/';

//funcion para registrar el usuario a traves de la api
async function signUp(data){
    //mientras usemos la api de victor.. adaptamos el formato de los datos a lo que espera la api
    
        // let dataProvisional = {
        //     name : "Abel",
        //     username: "Abelin77",
        //     mail: "data@email.com",
        //     password: "paso",
        // };
        fetch(url, {
            method: 'POST',
            headers:{
                'Content-Type': 'application/json;utf-8'
            },
            body: JSON.stringify(data),
            mode: 'cors',
        })
        .then(response => {
            console.log(response);
            if(response.status == 201){
                loginAlert.innerHTML = 'Usuario registrado correctamente';
                return response.json();

            }else if(response.status == 400){
                loginAlert.innerHTML = 'Usuario ya registrado';
            }else{
                loginAlert.innerHTML = 'Error desconocido :(';
            }
        })
        .then(data => {
            console.log(data);
            //window.location.href = './login.html';
            let jwt = data;
            if(data != undefined && data != null){
                localStorage.setItem('jwt', jwt);
                let decodedJWT = JWT_decode(jwt);
                if(decodedJWT.userData.isAdmin){
                    //si es organizador le llevo al formulario de completar organizador
                    window.location.href = './completarOrganizador.html';
                }else{
                    //si es usuario le llevo a la pagina de completar usuario 
                    window.location.href = './completarUsuario.html';
                }
            }else{
                loginAlert.innerHTML='Error Al Registrar Usuario intentalo de nuevo'
                console.log('El jwt tiene valor null o undefined');
            }
        })
        .catch(error => {
            console.log(error);
        });
}

