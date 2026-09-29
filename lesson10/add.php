<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=autosallon", "root", "");

    $sql = "ALTER TABLE makina1  ADD Emri VARCHAR(255)";

    $pdo->exec($sql);

    echo "Column created successfully!";
} catch (PDOException $e) {
    echo "Error creating column: " . $e->getMessage();
}



?>