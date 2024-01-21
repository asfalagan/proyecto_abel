const { response, request } = require('express');
const { randomUUID } = require('crypto');
const {users} = require('../models/users');

// POST /api/user/register
// Registra un nuevo usuario en la base de datos
const post_register = async(req, res) => {
    let { name, username, mail, pass, city, phone, club, rol } = req.body;
    console.log(req.body);
    let error = false;

    // Falta un campo obligatorio
    if ( !username ) {
        error = true;
        res.setHeader('Content-Type', 'application/json');
        res.status(401).end( JSON.stringify( { "success": false, "msg": "El campo username es obligatorio" } ));
    } else if ( users.find( item => item.username == username) ) {
                error = true;
                res.setHeader('Content-Type', 'application/json')
                .status(401).end( JSON.stringify( { "success": false, "msg": "El nombre de usuario ya existe" } ));
    }
    // else if ( users.find( item => item.mail == mail) ) {
    //     error = true;
    //     res.setHeader('Content-Type', 'application/json')
    //        .status(400)
    //        .end( JSON.stringify( { "success": false, "msg": "El correo electrónico ya está registrado" } ));
    // };

    // El rol no es válido
    if (!rol) {
        rol = "user"
    } else {
        if ( rol!="user" && rol!="organizer" && rol!="admin") {
            error = true;
            res.setHeader('Content-Type', 'application/json')
           .status(400)
           .end( JSON.stringify( { "success": false, "msg": "El rol no es válido" } ));
        }
    }

    // Se ha creado el usuario correctamente
    if (!error) {
        const user = { id: randomUUID(), name, username, mail, pass, city, phone, club, rol };
        users.push(user);
        res.setHeader('Content-Type', 'application/json')
           .send(user);
    }
};

// // POST /api/user/check
// // Comprueba si un nombre de usuario ya está registrado
const post_check_username = async(req, res) => {
    let {username} = req.body;

    if ( users.find( item => item.username == username) ) {
        res.status(409).send();
    } else {
        res.status(200).send();
    }
};

// // GET /api/user/:username
// // Datos del usuario cuyo nombre de usuario se indica como parámetro
const get_user_by_username = async(req, res) => {
    const username = req.params.username;
    const user = users.find( item => item.username==username);
    if ( user ) {
        res
            .setHeader('Content-Type', 'application/json')    
            .status(200)
            .send(JSON.stringify( user ));
    } else {
        res.status(404).send();
    }
};

// // GET /api/user/id/:id
// // Datos del usuario cuyo nombre de usuario se indica como parámetro
const get_user_by_id = async(req, res) => {
    const id = req.params.id;
    const user = users.find( item => item.id==id);
    if ( user ) {
        res
            .setHeader('Content-Type', 'application/json')    
            .status(200)
            .send(JSON.stringify( user ));
    } else {
        res.status(404).send();
    }
};


// GET /api/user/all
const get_all_users = async(req, res) => {
    console.log(users);
    res
        .setHeader('Content-Type', 'application/json')
        .status(200)
        .send( JSON.stringify( {count: users.length, results: users} ) );
}

// DELETE /api/user/username/:username
const delete_user_by_username = async(req, res) => {
    const username = req.params.username;

    users = users.filter( item => item.username != username );

    res
        .setHeader('Content-Type', 'application/json')
        .status(200)
        .send();
}


module.exports = {
    post_register,
    post_check_username,
    get_user_by_username,
    get_user_by_id,
    get_all_users,
    delete_user_by_username
}