//compruebo si el usuario ha iniciado sesion
//si no ha iniciado sesion lo trato como anonimo

if(JWT_exists()){
    let jwt = JWT_decode(getLocalJWT());
    console.log('Obtenido JWT y decodificado: ');
    console.log(jwt);
    console.log('Sesion iniciada correctamente');
    if(jwt.user_data.user_nick != null && jwt.user_data.user_nick != undefined){
        let enlacePerfil = document.getElementsByClassName('header-span')[1];
        enlacePerfil.textContent = jwt.user_data.user_id;//aqui tengo que poner user_nick cuando lo tenga
    }
    //cambio el valor del atributo href del enlace loginbuttonheader para que apunte a ./perfil.html en vez de ./login.html
    let enlaceLogin = document.getElementById('loginbuttonheader');
    enlaceLogin.setAttribute('href', './perfil.html');
}else{
    console.log('Usuario anónimo');
}
