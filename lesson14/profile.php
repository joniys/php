<?php
    session_start();

    include_once('config.php');

    if(empty($_SESSION['username'])){
        header('Location: login.php');
    }

    $id = $_GET['id'];
    $sql = "SELECT * from users WHERE id=:id";
    $selectUsers = $conn->prepare($sql);
    $selectUsers->bindParam(':id,$id')
    $selectUsers->execute();

    $users_data = $selectUsers->fetch();
?>
     
     <?php include("header.php");?>

<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Welcome, <i><?php echo $_SESSION['username'];?></i></a>
    <ul class="navbar-nac px-3">
        <li class="nav-item text-nowrap">
            <a  class="nav-link" href="logout.php">Sign out</a>
        </li>
    </ul>
</nav>

<div class="contanier-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <span date-feather="home"></span>
                            dashboard <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="profile.php?id=<?= $users_data[id];?>">
                            <span date-feather="file"></span>
                            Edit Profile
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-centre pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>

            <div class="contanier">
                <div class="row">
                    <div class="col-md-5">
                        <form class="form-profile" action="update.php" method="post">
                            <span class="text-muted" for="id">Id</span>
                            <input class="form-control" type="number" id="floatingInput" placeholder="Id" name="id" value="<?php echo $users_data['id']?>" readonly>

                            <span class="text-muted" for="name">Name</span>
                            <input class="form-control" type="text" name="name" value="<?php echo $users_data['name']?>" require>

                            <span class="text-muted" for="surname">SurName</span>
                            <input class="form-control" type="text" name="surname" value="<?php echo $users_data['surname']?>" require>

                            <span class="text-muted" for="username">Username</span>
                            <input class="form-control" type="text" name="username" value="<?php echo $users_data['username']?>" require>

                            <span class="text-muted" for="email">Email</span>
                            <input class="form-control" type="text" name="email" value="<?php echo $users_data['email']?>" require>

                            <span class="text-muted" >Password</span>
                            <input class="form-control" type="password" name="password" readonly>

                            <button class="btn btn-lg btn-primary" type="submit" name="update">Update</button>
                        </form>
                    </div>
                </div>
            </div>
    </div>
</div>

   <?php include("footer.php");?>