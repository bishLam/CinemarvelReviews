<?php
include("header.php");
include("database.php");

// gets the movie if it's sent 
if(isset($_GET["movie_id"])){

  $movieID = intval($_GET["movie_id"]);
  $sql = "SELECT * FROM movies WHERE movie_id = $movieID";

  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);

  $movie_id = $row["movie_id"];
  $movie_title = $row["movie_title"];
  $release_date = $row["release_date"];
  $description = $row["description"];
  $poster_filepath = $row["poster_filepath"];
  $duration = $row["duration"];

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Cinemarvel Reviews</title>
  <meta charset="utf-8">
  <meta name="author" content="bishonath+syeda">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/cinemarvel_icons/cinemarvel_logo.png">
  <link rel="stylesheet" href="cinemarvel_main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- this is for the star rating from core ui -->
  <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@5.5.0/dist/css/coreui.min.css" rel="stylesheet"
    integrity="sha384-r/2d1aBwRhQSqjQh/21GvBPd8Oq6JOk0rWn05ngWyhiPOL79wFjw7vcbn3HwYGC/" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@5.5.0/dist/js/coreui.bundle.min.js"
    integrity="sha384-fB1r9DlcQtqFJcV4iBZiGPf1lH3BrXFpqFFYC0QTRc29fB9HQhpuEhjQwM9j96yG" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
  <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>


</head>

<!-- main product details container -->
<div class="container-fluid m-3 bg-dark p-4">
  <div class="d-inline-flex container-fluid">
    <!-- image of the product on the left-->
    <img style="max-width: 300px; max-height:500px" class="img-fluid w-100 rounded me-3 " src="assets/movie_banners/<?php echo"$poster_filepath"?>">
    <!-- details of the product on the right -->
    <div class="d-flex flex-column container-fluid">
      <div class="text-light">
        <h1 class="movieName">
          <?php
          echo "$movie_title";
          ?>

        </h1>
      </div>
      <div class="container-fluid d-flex justify-content-between text-light">
        <div>
          <p><?php echo "$release_date"?> | PG-13 | <?php echo"$duration"?></p>
        </div>
        <div class="d-inline-flex">
          <div data-coreui-read-only="true" data-coreui-toggle="rating" data-coreui-value="4.5"></div>
          <p>350k reviews</p>
        </div>
      </div>

      <div class="rounded container-fluid bg-danger p-3" style="max-height:max-content ">
        <div class="container-fluid d-flex flex-column">
          <div>
            <div class="d-flex justify-content-between">
              <!-- summary and description section -->
              <div class="d-flex flex-column m-2 w-75">
                <h2>Summary</h2>
                <p class="text-start">
                  <?php
                  echo "$description";
                  ?>
                </p>
              </div>

              <!-- written by and directed by section -->
              <div class="d-flex flex-column m-1" style="min-width: 150px;">
                <div>
                  <h4>Written by</h4>
                  <ul>
                    <li>Mark Fergus</li>
                    <li>Mark Fergus</li>
                    <li>Mark Fergus</li>
                  </ul>
                </div>
                <div>
                  <h4>Directed by</h4>
                  <ul>
                    <li>Jon Favreau</li>
                  </ul>
                </div>

              </div>
            </div>
          </div>
          <div class="container-fluid d-flex flex-column">
            <h5 class="m-2">Have some thoughts?</h5>
            <div class="container-fluid d-flex flex-column">
              <!-- rating bar -->
              <div class="d-flex align-items-center mb-1">
                <div id="myRatingCustomFeedbackStart" class="text-body-secondary me-3">0 / 5</div>
                <div id="myRatingCustomFeedback"></div>
                <div id="myRatingCustomFeedbackEnd" class="badge text-bg-dark ms-3">Meh</div>
              </div>
              <form method="get">
                <input class=" mt-3 rounded-3" style="min-width: 300px;" type="text" name="userReviewText" id="userReviewText" placeholder="Write a review...">
                <input class="rounded w-20" type="submit" value="Post a review">
              </form>
              </di>
              <div>

              </div>
            </div>
          </div>

        </div>


      </div>
    </div>
  </div>
</div>

<!-- Similar movies container -->

<div id="carouselUpcoming" class="carousel carousel-dark slide" data-bs-ride="carousel">
<h1 class="m-5">Similar Movies</h1>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="card-wrapper container-sm d-flex  justify-content-around">
                <div class="card bg-dark border-4 border-dark text-light" style="width: 18rem; height: 32rem">
                  <img class="card-img-top" src="assets/movie_posters/blade.jpg" alt="Blade Poster">
                  <div class="card-body text-center">
                    <h5 class="card-title fs-3">Ironman 2</h5>
                    <p class="card-text">7 NOV 2025</p>
                  </div>
                </div>
                <div class="card bg-dark border-4 border-dark text-light" style="width: 18rem; height: 32rem">
                  <img class="card-img-top" src="assets/movie_posters/blade.jpg" alt="Blade Poster">
                  <div class="card-body text-center">
                    <h5 class="card-title fs-3">Ironman 3</h5>
                    <p class="card-text">7 NOV 2025</p>
                  </div>
                </div>
                <div class="card bg-dark border-4 border-dark text-light" style="width: 18rem; height: 32rem">
                  <img class="card-img-top" src="assets/movie_posters/blade.jpg" alt="Blade Poster">
                  <div class="card-body text-center">
                    <h5 class="card-title fs-3">The marvels</h5>
                    <p class="card-text">7 NOV 2025</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="card-wrapper container-sm d-flex  justify-content-around">
                <div class="card bg-dark border-4 border-dark text-light" style="width: 18rem; height: 32rem">
                  <img class="card-img-top" src="assets/movie_posters/blade.jpg" alt="Blade Poster">
                  <div class="card-body text-center">
                    <h5 class="card-title fs-3">Guardians of the galaxy</h5>
                    <p class="card-text">7 NOV 2025</p>
                  </div>
                </div>
                <div class="card bg-dark border-4 border-dark text-light" style="width: 18rem; height: 32rem">
                  <img class="card-img-top" src="assets/movie_posters/blade.jpg" alt="Blade Poster">
                  <div class="card-body text-center">
                    <h5 class="card-title fs-3">Avengers</h5>
                    <p class="card-text">7 NOV 2025</p>
                  </div>
                </div>
                <div class="card bg-dark border-4 border-dark text-light" style="width: 18rem; height: 32rem">
                  <img class="card-img-top" src="assets/movie_posters/blade.jpg" alt="Blade Poster">
                  <div class="card-body text-center">
                    <h5 class="card-title fs-3">Spiderman Homecoming</h5>
                    <p class="card-text">7 NOV 2025</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselUpcoming" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselUpcoming" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>












<!-- script to make the ratings bar reponsive to hover and click -->
<script>
  const myRatingCustomFeedback = document.getElementById('myRatingCustomFeedback')
  const myRatingCustomFeedbackStart = document.getElementById('myRatingCustomFeedbackStart')
  const myRatingCustomFeedbackEnd = document.getElementById('myRatingCustomFeedbackEnd')
  if (myRatingCustomFeedback) {
    let currentValue = 3
    const labels = {
      1: 'Very bad',
      2: 'Bad',
      3: 'Meh',
      4: 'Good',
      5: 'Very good'
    }
    const optionsCustomFeedback = {
      value: currentValue
    }

    new coreui.Rating(myRatingCustomFeedback, optionsCustomFeedback)

    myRatingCustomFeedback.addEventListener('change.coreui.rating', event => {
      currentValue = event.value
      myRatingCustomFeedbackStart.innerHTML = `${event.value} / 5`
      myRatingCustomFeedbackEnd.innerHTML = labels[event.value]
    })

    myRatingCustomFeedback.addEventListener('hover.coreui.rating', event => {
      myRatingCustomFeedbackEnd.innerHTML = event.value ? labels[event.value] : labels[currentValue]
    })
  }
</script>


<!-- script reuired for ui core to function -->
<script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.1.2/dist/js/coreui.bundle.min.js"
  integrity="sha384-yoEOGIfJg9mO8eOS9dgSYBXwb2hCCE+AMiJYavhAofzm8AoyVE241kjON695K1v5" crossorigin="anonymous">
</script>

</html>

<?php
include("footer.php");
?>