let token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE3MDYxNzgzMjQsInVzZXJfaWQiOjYsInVzZXJfbmljayI6bnVsbCwiaXNfYWRtaW4iOjF9.MRkjWPvyciYfMDT8gk6vH1xaphvW8RaSJHokPLIwApg';
//funciones para manejar el JWT

//funcion para obtener el token del localstorage
function getLocalJWT(){}
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

//funcion para comprobar si esta a punto de caducar
//defino en la api funcion para renovar token o en cada peticion devuelvo un token?

//funcion para decodificar el token
function JWT_decode(token){
    let base64Url = token.split('.')[1];
    let base64 = base64Url.replace('-', '+').replace('_', '/');
    let decodedToken = JSON.parse(window.atob(base64));
    return decodedToken;
}

console.log(JWT_decode(token));