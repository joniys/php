<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=autosallon", "root", "");

    $sql = "CREATE TABLE Makina1 (
    id INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    VITI INT NOT NULL ,
    MOTORRI INT NOT NULL ,
    EMRI INT NOT NULL,
    KILOMETRAZHI INT NOT NULL
    
    
    
    
    )";

    $pdo->exec($sql);

    echo "Column created successfully!";
} catch (PDOException $e) {
    echo "Error creating column: " . $e->getMessage();
}



?>




    //PDO

    $host = "localhost";
    $db = "db4";
    $user = "root";
    $pass = "";

    try{
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$pass);

        $sql = "CREATE TABLE users (id INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR (30) NOT NULL ,
        password VARCHAR (50) NOT NULL
        )";

        $pdo -> exec($sql);

        echo "Table created successfully";

    }catch(Exception $e){
        echo "Error creating table" . $e->getMessage();
    }





try {
    $pdo = new PDO("mysql:host=localhost;dbname=db3", "root", "");

    $sql = "ALTER TABLE users  ADD Bmw 5 series VARCHAR(255)";

    $pdo->exec($sql);

    echo "Column created successfully!";
} catch (PDOException $e) {
    echo "Error creating column: " . $e->getMessage();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=db3", "root", "");

    $sql = "ALTER TABLE users  ADD Audi A6 VARCHAR(255)";

    $pdo->exec($sql);

    echo "Column created successfully!";
} catch (PDOException $e) {
    echo "Error creating column: " . $e->getMessage();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=db3", "root", "");

    $sql = "ALTER TABLE users  ADD Volswagen Taureg VARCHAR(255)";

    $pdo->exec($sql);

    echo "Column created successfully!";
} catch (PDOException $e) {
    echo "Error creating column: " . $e->getMessage();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=db3", "root", "");

    $sql = "ALTER TABLE users  ADD Ferrari La Ferrari VARCHAR(255)";

    $pdo->exec($sql);

    echo "Column created successfully!";
} catch (PDOException $e) {
    echo "Error creating column: " . $e->getMessage();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=db3", "root", "");

    $sql = "ALTER TABLE users  ADD Mercedes benz GLE VARCHAR(255)";

    $pdo->exec($sql);

    echo "Column created successfully!";
} catch (PDOException $e) {
    echo "Error creating column: " . $e->getMessage();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=db3", "root", "");

    $sql = "ALTER TABLE users  ADD Ferrari Purosangue VARCHAR(255)";

    $pdo->exec($sql);

    echo "Column created successfully!";
} catch (PDOException $e) {
    echo "Error creating column: " . $e->getMessage();
}



