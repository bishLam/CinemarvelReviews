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
          <form class="d-flex w-100" method="GET" action="search_results.php">
            <div class="input-group">
              <input type="text" class="form-control" name="search" placeholder="Search for movies..."
                aria-label="Search">
              <button class="btn btn-danger" type="submit">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Sign In and Profile -->
        <div class="col-3 d-flex align-items-center justify-content-end">
          <!-- If user is signed in their username should appear instead of the sign in button-->
          <?php if (isset($_SESSION["username"])): ?>
          <span class="link-light">Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
          <a href="cinemarvel_logout.php" class="link-light ms-3">Logout</a>
          <?php else: ?>
          <a href="cinemarvel_login.php" class="link-light">Sign In</a>
          <?php endif; ?>

          <!-- Profile icon -->
          <a href="#">
            <img class="rounded-circle ms-3" src="assets/cinemarvel_icons/navigation/icons8-test-account-96.png"
              width="45" height="45" alt="Profile icon">
          </a>
        </div>
      </div>
    </div>

    <!-- Page Links -->
    <div class="row w-100 mt-2">
      <div class="col-12 d-flex justify-content-center">
        <ul class="navbar-nav d-flex flex-row justify-content-center w-auto gap-4">
          <li class="nav-item">
            <a class="nav-link link-light" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link link-light" href="cinemarvel_browse.php">Browse</a>
          </li>
          <li class="nav-item">
            <a class="nav-link link-light" href="#">New Releases</a>
          </li>
          <li class="nav-item">
            <a class="nav-link link-light" href="#">About Us</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

</header>