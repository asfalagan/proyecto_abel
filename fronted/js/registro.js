//variables para los campos del formulario

const email = document.getElementById('email');
const passwd = document.getElementById('password');
const passwd2 = document.getElementById('password2');
const loginAlert = document.getElementById('login-alert');
const registrar = document.getElementById('registrar');

//evento para validar los datos del formulario
registrar.addEventListener('click', validarDatos);
console.log(email.value);

//funcion para validar los datos del formulario y decidir si se envia o no
function validarDatos(e){
    let emailValue = email.value;
    let passwdValue = passwd.value;
    let passwd2Value = passwd2.value;   
    var regPasswd = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,16}$/;
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
            password: passwdValue
        }
        console.log(data);
        signUp(data);
    }
}


const url = 'http://localhost:3000/api/user/register';

//funcion para registrar el usuario a traves de la api
async function signUp(data){
    //mientras usemos la api de victor.. adaptamos el formato de los datos a lo que espera la api
    
        let dataProvisional = {
            name : "Abel",
            username: "Abelin77",
            mail: "data@email.com",
            password: "paso",
        };
        fetch(url, {
            method: 'POST',
            headers:{
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dataProvisional),
        })
        .then(response => {
            console.log(response);
            if(response.status == 200){
                loginAlert.innerHTML = 'Usuario registrado correctamente';
                window.location.href = './login.html';
            }else if(response.status == 400){
                loginAlert.innerHTML = 'Usuario ya registrado';
            }else{
                loginAlert.innerHTML = 'Otro error';
            }
        })
        .then(data => {
            console.log(data); 
        })
        .catch(error => {
            console.log(error);
        });
}

