<?php
session_start();
?>
<header>
  <!-- Navigation bar start -->
  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <div class="row w-100">
        <!-- Logo -->
        <div class="col-3 d-flex align-items-center">
          <a class="navbar-brand" href="index.php">
            <img class="d-inline-block align-top" height="50" width="50"
              src="assets/cinemarvel_icons/cinemarvel_logo.png" alt="Logo">
          </a>
        </div>

        <!-- Search Bar -->
        <div class="col-6 d-flex justify-content-center align-items-center">
          <form class="d-flex w-100" method="GET" action="cinemarvel_search_result.php">
            <div class="input-group">
              <input type="text" class="form-control" name="search_text" placeholder="Search for movies..."
                aria-label="Search">
              <button class="btn btn-danger" type="submit" name="search-button">
                <i class="fa fa-search"></i></button>
            </div>
          </form>
        </div>

        <!-- Sign In and Profile -->
        <div class="col-3 d-flex align-items-center justify-content-end">
          <!-- If user is signed in their username should appear instead of the sign in button-->

          <?php
          if (isset($_SESSION["first_name"])) {
            $username = $_SESSION["first_name"];
            echo "<span class=\"link-light\">Hi, $username </span>";
          } else {
            echo "<a href=\"cinemarvel_login.php\" class=\"link-light\">Sign In</a>";
          }
          ?>

          <!-- Profile icon -->
          <a href='<?php
                    if (isset($_SESSION["user_id"])) {
                      echo "cinemarvel_profile.php";
                    } else {
                      echo "cinemarvel_login.php";
                    }
                    ?>'>
            <img class="rounded-circle ms-3" src="assets/cinemarvel_icons/navigation/icons8-test-account-96.png"
              width="45" height="45" alt="Profile icon">
          </a>
        </div>
      </div>
    </div>

    <!-- Page Links -->
    <div class="row container-fluid">
      <div class="col-12 d-flex justify-content-center">
        <ul class="navbar-nav d-flex flex-row justify-content-center w-auto gap-4">
          <li class="nav-item">
            <a class="nav-link link-light" href="index.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link link-light dropdown-toggle" href="cinemarvel_browse.php?genre_id=0" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="true">
              Browse
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

              <li><a class="dropdown-item" href="cinemarvel_browse.php?genre_id=1">Sci-fi</a></li>
              <li><a class="dropdown-item" href="cinemarvel_browse.php?genre_id=4">Adventure</a></li>
              <li><a class="dropdown-item" href="cinemarvel_browse.php?genre_id=3">Superhero</a></li>

              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="cinemarvel_browse.php?genre_id=0">Browse all</a></li>
            </ul>
          </li>

          <li id="newReleases" class="nav-item">
            <a class="nav-link link-light" href="
            <?php
            if (isset($_SESSION["user_id"])) {
              echo "cinemarvel_favourites.php";
            } else {
              echo "cinemarvel_login.php";
            }
            ?>
            ">My Favourites</a>
          </li>

          <li class="nav-item">
            <a class="nav-link link-light" href="cinemarvel_about_us.php">About Us</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

</header>

<!--  -->
<script>

</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>