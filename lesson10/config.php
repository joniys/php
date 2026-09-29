<?php
    session_start();

    $user="root";
    $pass="";
    $server="localhost";
    $dbname="db4";

    try{
        $conn = new PDO("mysql:host=$server;dbaname=$dbname",$user,$pass);
    }catch(PDOExeption $e){
        echo "error: ".$e->getMessage();
    }


?>