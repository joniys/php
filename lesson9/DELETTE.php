<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=db2", "root", "");

    $sql = "ALTER TABLE users  drop password";

    $pdo->exec($sql);

    echo "Column dropped successfully!";
} catch (PDOException $e) {
    echo "Error creating column: " . $e->getMessage();
}



?>
