<?php
include_once('dashboard.php');

if(isset($_POST['submit'])){
    $name = $_POST['name'];  
    $username = $_POST['username'];
    $email = $_POST['email'];

    $sql = "insert into users(name,username,email) values (:name, :username, :email)";
    $sqlQuery = $conn->prepare($sql);

    $sqlQuery->bindParam(':name',$name);
    $sqlQuery->bindParam(':username',$username);
    $sqlQuery->bindParam(':email',$email);

    $sqlQuery->execute();
}

?>



<!DOCTYPE html>
<html lang="en">
    <body>
        <form action="add.php" method="POST">
            <input type="text" name="name" placeholder="Name"><br>
            <input type="text" name="username" placeholder="username"><br>
            <input type="email" name="email" placeholder="Email"><br>
            <button type="submit" name="submit">Add </button>
</form>
</body>



</html>