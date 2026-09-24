<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=autosallon", "root", "");

    $sql = "drop TABLE makina  ";

    $pdo->exec($sql);

    echo "table dropped!";
} catch (PDOException $e) {
    echo "Error creating column: " . $e->getMessage();
}



?>