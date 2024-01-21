const express = require('express');
const cors = require('cors');

const PORT = 3000

class Server {

    constructor() {
        this.app = express();
        this.port = PORT;

        // Conectar a base de datos
        // dbConnection()

        // Middlewares
        this.middlewares();

        // Rutas de la aplicación
        this.routes();
    }


    middlewares() {
        // CORS 
        this.app.use( cors() );

        // Lectura y parseo del body
        this.app.use( express.json() );

        // Directorio público
        this.app.use( express.static('public') );
    }

    routes() {
        this.app.use('/api/auth', require('../routes/auth'));
        this.app.use('/api/user', require('../routes/user'));
    }

    listen() {
        this.app.listen(this.port, () => {
            console.log('Servidor corriendo en puerto', this.port)
        })
    }
}

module.exports = Server;