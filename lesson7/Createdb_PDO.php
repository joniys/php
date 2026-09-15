<?php
$host = "localhost";
$user = "root";
$pass = ""; 

try {
    $conn = new PDO("mysql:host=$host", $user , $pass);
    $sql = "create database testdb";
    $conn->exec($sql);
    echo "Database is Connected";
} catch(Exception $e) {
    echo"Database is Not Connected";
}


?>