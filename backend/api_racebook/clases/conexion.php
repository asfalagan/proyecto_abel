<?php
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Authorization, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, DELETE");
    $method = $_SERVER['REQUEST_METHOD'];

    $jwtkey = base64_encode('Mariscada');
    
    if($method == "OPTIONS") {
        die();
    }
    Class Conexion extends mysqli {
        private $host = 'localhost';
        private $db = 'racebook';
        private $user = 'abeldes';
        private $pass = '1234';

        public function __construct(){
            try {
                parent::__construct($this->host, $this->user, $this->pass, $this->db);
            } catch (mysql_sql_exception $e){
                echo "ERROR: {$e -> getMessage()}";
                //header("HTTP/1.1 400 Bad Request");
            }
        }
    }

?>