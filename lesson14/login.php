<?php include("header.php");?>

<div class="login">
        <form action="form-singin" action="loginLogic.php" method="POST">
            <h1 class="h3 mb-3 front-weight-normal"> Please sign in</h1>

            <label for="inputEmail" class="sr-only">Email</label>
            <input type="text" id="inputEmail" class="form-control" placeholder="username" name="username" required autofovus>

            <label for="inputPassword" class="sr-only">Password</label>
            <input type="email" id="password" class="form-control" placeholder="Password" name="password" required autofovus>
            <br>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Sign in</button>

            <small>Don't have account ? <a href="signup.php">Sign up</a></small>

            <p class="mt-5 mb-3 text-muted">Digital school &copy; 2023</p>
        </form>

     </div>
<?php include("footer.php");?>
