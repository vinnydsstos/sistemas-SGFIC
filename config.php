<?php

$ambient = "homolog";
//$ambient = "prod";

if ($ambient == "homolog"){

$servername = "localhost:3307";
$username = "root";
$password = "";
$db_name = "SGFIC";

}else if ($ambient == "prod"){

    $servername = "localhost:3306";
    $username = "root";
    $password = "";
    $db_name = "SGFIC";

}