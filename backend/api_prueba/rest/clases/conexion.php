<?php
    Class Conexion extends mysqli {
        private $host = 'localhost';
        private $db = 'pruebas_api';
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