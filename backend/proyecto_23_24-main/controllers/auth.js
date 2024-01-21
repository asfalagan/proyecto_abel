const { response, request } = require('express');
const jwt = require('jsonwebtoken');
const {users} = require('../models/users');

const JWT_SECRET = "P@ssw0rd"

const post_login = async(req, res) => {
    let { username, pass } = req.body;

    let user = users.find( item => item.username==username && item.pass==pass);
    if (user) {
        const token = jwt.sign({
            username: user.username,
            rol: user.rol,
            exp: Math.floor(Date.now() / 1000) + (60 * 60), // 1 hora
        }, JWT_SECRET);
        res
            .status(200)
            .setHeader('Content-Type', 'application/json')
            .send(JSON.stringify( {
                username,
                token
            } ));
    } else {
        res.status(401).send();
    }
    };

module.exports = {
    post_login
}