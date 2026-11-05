<?php 
  session_start();
  include_once('config.php');

  if (empty($_SESSION['username']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 'true') {
      header("Location: login.php");
      exit();
  }
?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Add Movie</title>
 	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#7952b3">
  <style>
    .form-floating{
      margin: 20px 0px;
    }
  </style>
 </head>
 <body>
 
 <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#"><?php echo "Welcome to dashboard ".htmlspecialchars($_SESSION['username']); ?></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <input class="form-control form-control-dark w-50" type="text" placeholder="Search" aria-label="Search">
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3" href="logout.php">Sign out</a>
    </div>
  </div>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
      <ul class="nav flex-column">
           <?php if ($_SESSION['is_admin'] == 'true') { ?>
            <li class="nav-item">
              <a class="nav-link" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="list_movies.php">Movies</a>
            </li>
        <?php } ?>
          <li class="nav-item">
            <a class="nav-link" href="bookings.php">Bookings</a>
          </li>
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Add Movie</h1>
      </div>

       <form action="addMovie.php" method="post" enctype="multipart/form-data">
        <div class="form-floating">
          <input type="text" class="form-control" id="floatingMovieName" placeholder="Movie Name" name="movie_name" required>
          <label for="floatingMovieName">Movie name</label>
        </div>
        <div class="form-floating">
          <input type="text" class="form-control" id="floatingMovieDesc" placeholder="Movie Description" name="movie_desc" required>
          <label for="floatingMovieDesc">Movie Description</label>
        </div>
        <div class="form-floating">
          <input type="text" class="form-control" id="floatingQuality" placeholder="Quality" name="movie_quality" required>
          <label for="floatingQuality">Movie Quality</label>
        </div>
        <div class="form-floating">
          <input type="number" step="0.1" class="form-control" id="floatingRating" placeholder="Rating" name="movie_rating" required>
          <label for="floatingRating">Rating</label>
        </div>
        <div class="form-floating">
          <input type="file" class="form-control" id="floatingImage" placeholder="Image" name="movie_image" required>
          <label for="floatingImage">Image</label>
        </div>
         <button class="w-100 btn btn-lg btn-primary" type="submit" name="submit">Add Movie</button> 
      </form>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
