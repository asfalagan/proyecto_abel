let token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE3MDYxNzgzMjQsInVzZXJfaWQiOjYsInVzZXJfbmljayI6bnVsbCwiaXNfYWRtaW4iOjF9.MRkjWPvyciYfMDT8gk6vH1xaphvW8RaSJHokPLIwApg';
//funciones para manejar el JWT
window.onload = () => {
    if(JWT_exists()){
        console.log('JWT exists -> Usuario LOGUEADO');
    }else{
        console.log('JWT not exists -> Usuario ANÓNIMO');
    }
}
//funcion para comprobar si existe el token en el localstorage y es valido
function JWT_exists(){
    let token = getLocalJWT();
    if(token){
        return JWT_isValid(token);
    }else{
        return false;
    }
}
//funcion para obtener el token del localstorage
function getLocalJWT(){
    let token = localStorage.getItem('jwt');
    return token;
}
//funcion para guardar el token en el localstorage
function setLocalJWT(token){
    localStorage.setItem('jwt', token);
}
//funcion para comprobar si es valido -> si no esta caducado
function JWT_isValid(token){
    let decodedToken = JWT_decode(token);
    let exp = decodedToken.exp;
    let now = Date.now();
    if(now < exp){
        return true;
    }else{
        return false;
    }
}
//funcion para comprobar si caduca pronto
function JWT_isAboutToExpire(token){
    let decodedToken = JWT_decode(token);
    let exp = decodedToken.exp;
    let now = Date.now();
    let diff = exp - now;
    if(diff < 300){
        return true;
    }else{
        return false;
    }
}
//funcion para decodificar el token
function JWT_decode(token){
    let base64Url = token.split('.')[1];
    let base64 = base64Url.replace('-', '+').replace('_', '/');
    let decodedToken = JSON.parse(window.atob(base64));
    return decodedToken;
}


console.log(JWT_decode(token));