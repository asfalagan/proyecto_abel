//let tokenLocal = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE3MDYxNzgzMjQsInVzZXJfaWQiOjYsInVzZXJfbmljayI6bnVsbCwiaXNfYWRtaW4iOjF9.MRkjWPvyciYfMDT8gk6vH1xaphvW8RaSJHokPLIwApg';
//funciones para manejar el JWT
// window.onload = () => {
//     if(JWT_exists()){
//         console.log('JWT exists -> Usuario LOGUEADO');
//     }else{
//         console.log('JWT not exists -> Usuario ANÓNIMO');
//     }
// }
//funcion para comprobar si existe el tokenLocal en el localstorage y es valido
//ERROR ESTA FUNCION DA FALSE SIEMPRE
function JWT_exists(){
    let tokenLocal = getLocalJWT();
    if(tokenLocal != null && tokenLocal != undefined){
        return JWT_isValid(tokenLocal);
    }else{
        return false;
    }
}
//funcion para obtener el tokenLocal del localstorage
function getLocalJWT(){
    let tokenLocal = localStorage.getItem('jwt');
    return tokenLocal;
}
//funcion para guardar el tokenLocal en el localstorage
function setLocalJWT(tokenLocal){
    localStorage.setItem('jwt', tokenLocal);
}
//funcion para comprobar si es valido -> si no esta caducado
function JWT_isValid(tokenLocal){
    let decodedtokenLocal = JWT_decode(tokenLocal);
    let exp = decodedtokenLocal.exp;
    console.log(exp);
    let now = Date.now();
    console.log(now);
    if(now < exp){
        return true;
    }else{
        return false;
    }
}
//funcion para comprobar si caduca pronto
function JWT_isAboutToExpire(tokenLocal){
    let decodedtokenLocal = JWT_decode(tokenLocal);
    let exp = decodedtokenLocal.exp;
    let now = Date.now();
    let diff = exp - now;
    if(diff < 300){
        return true;
    }else{
        return false;
    }
}
//funcion para decodificar el tokenLocal
function JWT_decode(tokenLocal){
    let base64Url = tokenLocal.split('.')[1];
    //let base64 = base64Url.replace('-', '+').replace('_', '/');
    let decodedtokenLocal = JSON.parse(window.atob(base64Url));
    return decodedtokenLocal;
}


//console.log(JWT_decode(tokenLocal));