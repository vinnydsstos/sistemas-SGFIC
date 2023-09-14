<?php

require_once __DIR__ . '/../config.php';


class Connect {

    static $connect;

    public static function getConnection() {
        global $servername, $username, $password, $db_name;
        
        if (self::$connect == null) {
            self::$connect = new mysqli($servername, $username, $password, $db_name);

            if (mysqli_connect_error()):
                echo "Erro na conexão: " . mysqli_connect_error();
            endif;
        }
        return self::$connect;
    }

}
