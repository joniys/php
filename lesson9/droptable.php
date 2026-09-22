<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=db2", "root", "");

    $sql = "drop TABLE users  ";

    $pdo->exec($sql);

    echo "table dropped!";
} catch (PDOException $e) {
    echo "Error creating column: " . $e->getMessage();
}



?>
