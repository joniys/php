<?php
     $username = $_GET['username'];
     $password = $_GET['password'];
    echo $username;
    echo "<br>";
    echo $password;



?>

<!DOCTYPE html>
<html lang="en">
    <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width , initial-scale=1.0">
         <title> Our php Form</title>
    </head>
    <body>
        <form action="lesson10.php" method="get">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" placeholder="Username"><br>
             <label for="password">Pass:</label><br>
            <input type="password" id="password" name="password" placeholder="Password"><br><br>
            <input type="Submit" value="submit">
</form>
</body>



</html>