<?php
    try{
        $pdo = new PDO ("mysql:host=localhost;dbname=autosallon","root","");

    $Mercedes = "Mercedes_e";
    $KILO = "19100";
    $MOTORRI = "2";
    $VITI = "2022";
    

    $sql = "INSERT INTO makina1 (Emri,KILOMETRAZHI,MOTORRI,VITI) VALUES ('$Mercedes','$KILO','$MOTORRI','$VITI')";

    $pdo -> exec($sql);

    echo "New record created successfully";

    }catch(Excpetion $e){
        echo $e -> getMessage();
    }

    //SELECT * FROM PRODUCTS AS P JOIN CATEGORIES AS C ON P.CATEGORY_ID = C.ID

    // SELECT products.name as 'Product name', categories.name as 'Category' FROM products INNER JOIN 
    // categories on products.category_id=categories.id
?>