const Server = require('./models/server');

const server = new Server();

server.listen();

// const express = require('express');
// const bodyParser = require('body-parser');
// const jwt = require('jsonwebtoken');

// const app = express()
// const PORT = 3000
// const JWT_SECRET="P@ssw0rd"

// app.use(bodyParser.json());
// app.use(express.static('public'));

// const users = [
//     {
//         "user": "Pepe Fernández",
//         "username": "pepe",
//         "mail": "pepe@mail.com",
//         "pass": "paso",
//         "city": "León",
//         "phone": "987333555",
//         "club": "CM Teleno",
//         "rol": "user"
//     },
//     {
//         "user": "Antonio Álvarez",
//         "username": "antonio",
//         "mail": "antonio@mail.com",
//         "pass": "paso",
//         "city": "León",
//         "phone": "987111222",
//         "club": "CM Teleno",
//         "rol": "organizer"
//     }
// ];

// // POST /api/register
// // Registra un nuevo usuario en la base de datos
// app.post( '/api/register', (req, res) => {
//     let { name, username, mail, pass, city, phone, club, rol } = req.body;

//     // Falta un campo obligatorio
//     if ( !name || !username || !mail || !pass ) {
//         res.setHeader('Content-Type', 'application/json');
//         res.status(400).end( JSON.stringify( { "success": false, "msg": "Faltan campos obligatorios" } ));
//     };

//     // El nombre de usuario ya existe
//     if ( users.find( item => item.username == username) ) {
//         res.setHeader('Content-Type', 'application/json')
//            .status(400).end( JSON.stringify( { "success": false, "msg": "El nombre de usuario ya existe" } ));
//     };

//     // El correo electrónico ya está registrado
//     if ( users.find( item => item.mail == mail) ) {
//         res.setHeader('Content-Type', 'application/json')
//            .status(400)
//            .end( JSON.stringify( { "success": false, "msg": "El correo electrónico ya está registrado" } ));
//     };

//     // El rol no es válido
//     if (!rol) {
//         rol = "user"
//     } else {
//         if ( rol!="user" || rol!="organizer" || rol!="admin") {
//             res.setHeader('Content-Type', 'application/json')
//            .status(400)
//            .end( JSON.stringify( { "success": false, "msg": "El rol no es válido" } ));
//         }
//     }

//     // Se ha creado el usuario correctamente
//     users.push(req.body);
//     res.setHeader('Content-Type', 'application/json')
//        .send(req.body);
// });

// // POST /api/username/check
// // Comprueba si un nombre de usuario ya está registrado
// app.post( '/api/username/check', (req, res) => {
//     let {username} = req.body;

//     if ( users.find( item => item.username == username) ) {
//         res.status(409).send();
//     } else {
//         res.status(200).send();
//     }
// });

// // GET /api/user/:username
// // Datos del usuario cuyo nombre de usuario se indica como parámetro
// app.get( '/api/user/:username', (req, res) => {
//     const username = req.params.username;
//     const user = users.find( item => item.username==username);
//     if ( user ) {
//         res
//             .setHeader('Content-Type', 'application/json')    
//             .status(200)
//             .send(JSON.stringify( {
//                             user: user.name,
//                             username: user.username,
//                             mail: user.mail,
//                             city: user.city,
//                             phone: user.phone,
//                             club: user.club,
//                             rol: user.rol
//                         } ));
//     } else {
//         res.status(404).send();
//     }
// } );

// app.delete( 'api/user/:username', (req, res) => {
//     res.send("Borrando");
// })


// app.post( '/api/login', (req, res) => {
//     let { username, pass } = req.body;

//     let user = users.find( item => item.username==username && item.pass==pass);
//     if (user) {
//         const token = jwt.sign({
//             username: user.username,
//             rol: user.rol,
//             exp: Math.floor(Date.now() / 1000) + (60 * 60), // 1 hora
//         }, JWT_SECRET);
//         res
//             .status(200)
//             .setHeader('Content-Type', 'application/json')
//             .send(JSON.stringify( {
//                 username,
//                 token
//             } ));
//     } else {
//         res.status(401).send();
//     }
// })

// app.listen( PORT, () => {
//     console.log(`Ejecutando en el puerto ${PORT}`);
// })