<!doctype html>
<?php
include "header.php";
?>

<html lang="en">

<head>
  <title>Cinemarvel Reviews</title>
  <meta charset="utf-8">
  <meta name="author" content="bishonath+syeda">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/cinemarvel_icons/cinemarvel_logo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous">
  </script>
  <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
</head>

<body class="bg-dark">
  <section class="banner" id="main_banner">
    <!-- Carousel -->
    <div id="demo" class="carousel slide" data-bs-ride="carousel">

      <!-- Indicators/dots -->
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
      </div>

      <!-- The slideshow/carousel -->
      <div class="carousel-inner">

        <?php
        include "database.php";
        $sql = "SELECT movie_id, poster_filepath FROM movies";
        $result = mysqli_query($conn, $sql);

        $imageURL = array();
        $movieIDs = array();

        if ($result != null) {
          while ($row = mysqli_fetch_assoc($result)) {
            $imageURL[] = $row["poster_filepath"];
            $movieIDs[] = $row["movie_id"];
          }
          for ($i = 0; $i < 3; $i++) {
            $randomIndex = array_rand($imageURL);
            $movieURL = $imageURL[$randomIndex];
            $movieID = $movieIDs[$randomIndex];
            $active = $i === 0 ? "active" : "";


            echo "
          <div class=\"carousel-item $active\">
          <a href=\"cinemarvel_product_details.php?movie_id=$movieID \">
          <img src=\"assets/movie_banners/$movieURL \" alt=\"Banner URL\" class=\"d-block w-100 h-80\">
          </a>
          </div>";
          }
        } else {
          echo "
          <div class=\"carousel-item-active\">
          <a href=\" cinemarvel_product_details.php \">
          <img src=\"assets/movie_banners/ironman.jpg\" alt=\"Banner URL\" class=\"d-block w-100 h-80\">
          </a>
          </div>
          ";
        }
        ?>

      </div>

      <!-- Left and right controls/icons -->
      <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
  </section>
  <section class="p-4">
    <!--Intro-->
    <div class="bg-danger border border-danger rounded mx-auto p-3" style="max-width: 90%;">
      <div class="mx-auto px-3 text-light">
        <h2 class="fs-1">Welcome to Cinemarvel Reviews</h2>
        <div class="row align-items-center">
          <div class="col">
            <p class="fs-5">Join the ultimate Marvel fan community—rate, review, and discuss your favorite movies and
              shows.</p>
          </div>
          <div class="col-6"><img src="assets/screenshots/screenshot_1.png" class="img-fluid"></div>
        </div>
        <div class="row align-items-center">
          <div class="col-6"><img src="assets/screenshots/screenshot_2.png" class="img-fluid"></div>
          <div class="col">
            <p class="fs-5">
              Find detailed info and ratings on every Marvel release—sorted by fans, for fans.
            </p>
          </div>
        </div>
        <div class="row align-items-center">
          <div class="col">
            <p class="fs-5">Personalize your experience: create lists, manage reviews, and explore new releases.</p>
          </div>
          <div class="col-6"><img src="assets/screenshots/screenshot_3.png" class="img-fluid"></div>
        </div>
      </div>
    </div>






    <!--Movie Carousel-->
    <div class="text-light">
      <div class=" mx-auto py-3">
        <h1>New Releases</h1>
        <!--Carousel of New Movie Cards-->
        <div id="carouselNewReleases" class="carousel carousel-dark slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php
            include("database.php");

            $stmt = $conn->prepare("SELECT m.* FROM newRelease as n INNER JOIN movies as m ON n.movie_id = m.movie_id ORDER BY m.release_date DESC LIMIT 10");
            $stmt->execute();

            $result = $stmt->get_result();
            $active = "active";
            $i = 0;
            echo "<div class=\"carousel-item active\">";
            echo "<div class=\"card-group\">";
            foreach ($result as $row):
            ?>



              <div class="card ms-3 me-5 rounded" style="max-width: 500px;">
                <img style="min-height: 200px; max-height: 400px;" src="assets/movie_banners/<?= $row["poster_filepath"] ?>" class="card-img-top" alt="...">
                <div class="card-body">
                  <h5 class="card-title"><?= $row["movie_title"] ?></h5>
                  <p class="card-text"><?= $row["description"] ?></p>
                  <a href="cinemarvel_product_details.php?movie_id=<?= $row["movie_id"] ?>" class="btn btn-outline-info float-end floa">View more</a>
                </div>

                <div class="card-footer">
                  <small class="text-muted">Released on <?= $row["release_date"] ?></small>
                </div>
              </div>
              <?php $i++;
              if ($i % 3 == 0 && $i != 0):
              ?>
          </div>
        </div>
        <div class="carousel-item">
          <div class="card-group">
          <?php endif; ?>
        <?php endforeach; ?>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselNewReleases" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselNewReleases" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
      </button>
    </div>







    <hr class="border-danger border-4">
    <div class="mx-auto py-3">
      <h1>Upcoming</h1>
      <!--Carousel of Upcoming Movie Cards-->
      <div id="carouselUpcoming" class="carousel carousel-dark slide mb-4 mt" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php
          include("database.php");

          $stmt = $conn->prepare("SELECT m.* FROM upcoming as u INNER JOIN movies as m ON u.movie_id = m.movie_id ORDER BY m.release_date DESC LIMIT 10");
          $stmt->execute();

          $result = $stmt->get_result();
          $active = "active";
          $i = 0;
          echo "<div class=\"carousel-item active\">";
          echo "<div class=\"card-group\">";
          foreach ($result as $row):
          ?>



            <div class="card ms-3 me-5 rounded" style="max-width: 500px;">
              <img style="min-height: 200px; max-height: 400px;" src="assets/movie_banners/<?= $row["poster_filepath"] ?>" class="card-img-top" alt="...">
              <div class="card-body">
                <h5 class="card-title"><?= $row["movie_title"] ?></h5>
                <p class="card-text"><?= $row["description"] ?></p>
                <a href="cinemarvel_product_details.php?movie_id=<?= $row["movie_id"] ?>" class="btn btn-outline-info float-end floa">View more</a>
              </div>

              <div class="card-footer">
                <small class="text-muted">Releasing on <?= $row["release_date"] ?></small>
              </div>
            </div>
            <?php $i++;
            if ($i % 3 == 0 && $i != 0):
            ?>
        </div>
      </div>
      <div class="carousel-item">
        <div class="card-group">
        <?php endif; ?>
      <?php endforeach; ?>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselUpcoming" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselUpcoming" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
    </button>
    </div>
    <div class="text-light">
      <div class="bg-danger border border-danger rounded mx-auto py-3">
        <h1>Browse By Genre</h1>
        <!--Carousel of Genre Cards-->

        <div id="carouselGenres" class="carousel carousel-dark slide" data-bs-ride="carousel">
          <div class="carousel-inner">

            <?php
            include("database.php");

            $stmt = $conn->prepare("SELECT * FROM genres");
            $stmt->execute();

            $result = $stmt->get_result();
            $active = "active";
            $i = 0;
            foreach ($result as $row): ?>
              <?php if ($i % 3 == 0 || $i == 0): ?>

                <div class="carousel-item <?= $i == 0 ? "active" : "" ?>">
                  <div class="card-wrapper container-sm d-flex  justify-content-around">
                  <?php endif ?>

                  <!-- genre card -->
                  <div onclick="browseMovies(<?= $row['genre_id'] ?>)" class="card bg-dark border-4 border-danger  text-light" style="width: 18rem;">
                    <div class="card-body text-center">
                      <h5 class="card-title fs-2"><?= $row["genre_name"] ?></h5>
                    </div>
                  </div>

                  <?php
                  $i++;
                  if ($i % 3  == 0 && $i != 0): ?>
                  </div>
                </div>
              <?php endif ?>
            <?php

            endforeach;
            if ($result->num_rows - 1 === $i) {
              echo "</div>";
              echo "</div>";
            }
            ?>
          </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselGenres" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselGenres" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
    <hr class="border-danger border-4">
  </section>
</body>

</html>
<script>
  function browseMovies(genre_id){
    window.location.href = "cinemarvel_browse.php?genre_id=" +genre_id;
  }
</script>


<?php
include("footer.php");
?>