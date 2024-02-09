
function JWT_decode(tokenLocal) {
    // Obtener la parte de la carga útil del token
    let payload = tokenLocal.split('.')[1];
    if(payload == undefined){
        return null;
    }
    // Decodificar la cadena Base64url
    let r = atob(payload.replace('-', '+').replace('_', '/'));

    return JSON.parse(r);
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

//comprobar que el token es valido
function JWT_isValid(tokenLocal){
    let decodedtokenLocal = JWT_decode(tokenLocal);
    if(!decodedtokenLocal){
        return false;
    }
    let exp = decodedtokenLocal.exp;
    let now = Date.now();
    if(now < exp){
        return true;
    }else{
        return false;
    }
}