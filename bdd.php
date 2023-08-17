<?php

    $servidor="localhost"; // 127.0.0.1
    $nombre= "empleados_bdd";
    $usuario= "root";
    $password= "";

    try {

        $conexion = new PDO("mysql:host=$servidor;dbname=$nombre", $usuario, $password);
    
    } catch (Exception $e) {
        
        echo $e->getMessage();
    }
    

?>