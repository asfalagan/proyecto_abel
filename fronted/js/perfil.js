const nombre = document.getElementById('nombre');
const nickName = document.getElementById('nickname');
const email = document.getElementById('email');
const passwd = document.getElementById('password');
const passwd2 = document.getElementById('password2');
const imagen = document.getElementById('imagen');
const loginAlert = document.getElementById('login-alert');

const botonModificar = document.getElementById('modificar-datos');
botonModificar.addEventListener('click', modificarDatos);
//las imagenes las mandamos en un form a parte para gestionarlo desde el servidor sin hacver nada js 
function modificarDatos(e){
    let passwdValue = passwd.value;
    let passwd2Value = passwd2.value; 
    data = {}; 
    if(passwd2Value != '' || passwdValue != ''){
        if(validarPasswd(passwdValue, passwd2Value)){
            data.passwd=passwdValue;
        }
    }
    if (nombre.value.trim()) data.nombre=nombre.value.trim();
    if (nickName.value.trim()) data.nickName=nickName.value.trim();
    if (email.value.trim()) data.email=email.value.trim();
    //compruebo que data tenga al menos una clave para enviar
    if(Object.keys(data).length != 0){
        console.log(data);
        fetch('http://localhost:3000/backend/api_racebook/user/', {
            method: 'PUT',
            headers:{
                'Content-Type': 'application/json;utf-8',
            },
            body: JSON.stringify(data),
            mode: 'cors',
        })
        //completa las opciones de codigo de respuesta y pruebalo
        .then(response => {
            console.log(response);
            if(response.status == 200){
                return response.json();
            }else if(response.status == 401){
                loginAlert.innerHTML='Correo electrónico o contraseña incorrectos';
            }
        })
        .then(data => {
            console.log(data);
        })
            
    }
}


function validarPasswd(passwdValue, passwd2Value){
  
    var regPasswd = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?#&])([A-Za-z\d$@$!%*?&#]|[^ ]){8,16}$/;
    //me falta un campo por rellenar
    if(passwdValue == '' || passwd2Value == ''){
        loginAlert.innerHTML = 'Rellena los campos de contraseña';
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
        console.log(passwd2Value);
        return true;
    }
    return false;
}
