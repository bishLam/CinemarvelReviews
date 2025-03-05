<!DOCTYPE html>
<html lang="en">


<?php
include("header.php");
include("database.php");


// gets the movie if it's sent 
if (isset($_GET["movie_id"])) {

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

<head>
  <title>Movie details - Cinemarvel Reviews</title>
  <meta charset="utf-8">
  <meta name="author" content="bishonath">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/cinemarvel_icons/cinemarvel_logo.png">
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
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous">
  </script>
  <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
  <!-- jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


</head>
<?php
if ($movieID != null):
?>

  <!-- main product details container -->
  <div class="container-fluid m-3 bg-dark p-4">
    <div class="d-inline-flex container-fluid">
      <!-- image of the product on the left-->
      <img style="max-width: 300px; max-height:500px" class="img-fluid w-100 rounded me-3 "
        src="assets/movie_posters/<?php echo "$poster_filepath" ?>">
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
            <p><?php echo "$release_date" ?> | PG-13 | <?php echo "$duration" ?></p>
          </div>
          <div class="d-inline-flex">

            <div data-coreui-read-only="true" data-coreui-precision="0.25" data-coreui-toggle="rating" data-coreui-value="<?php
                                                                                                                          include("database.php");
                                                                                                                          // set the average for the movie ratings
                                                                                                                          $stmt = $conn->prepare("SELECT AVG(stars) AS average_stars FROM reviews WHERE movie_id = ?");
                                                                                                                          $stmt->bind_param("i", $movie_id);
                                                                                                                          $stmt->execute();
                                                                                                                          $result = $stmt->get_result();

                                                                                                                          foreach ($result as $row) {
                                                                                                                            $average_stars = $row['average_stars'];
                                                                                                                            echo $average_stars;
                                                                                                                          }
                                                                                                                          ?>">
              <p class="me-1"><?= number_format($average_stars, 2, ".", "," ) ?> / 5</p>
            </div>
            <!-- Display total number of reviews -->
            <p>
              <?php
              include("database.php");
              $stmt = $conn->prepare("SELECT COUNT(review_id) AS total_reviews FROM reviews WHERE movie_id = ?");
              $stmt->bind_param("i", $movie_id);
              $stmt->execute();
              $result = $stmt->get_result();

              foreach ($result as $row) {
                $total_reviews = $row['total_reviews'];
                echo $total_reviews . " reviews";
              }
              ?>
            </p>
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
                      <?php
                      include("database.php");
                      $stmt = $conn->prepare("SELECT R.writer_name AS writer_name FROM writer_movie AS WM INNER JOIN writers AS R ON R.writer_id = WM.writer_id  WHERE WM.movie_id = ? ");
                      $stmt->bind_param("i", $movie_id);
                      $stmt->execute();
                      $result = $stmt->get_result();

                      foreach ($result as $row) {
                        $writer = $row['writer_name'];
                        echo "<li> $writer </li>";
                      }


                      ?>
                    </ul>
                  </div>

                  <!-- display directors -->
                  <div>
                    <h4>Directed by</h4>
                    <ul>
                      <?php
                      include("database.php");
                      $stmt = $conn->prepare("SELECT D.director_name AS director_name FROM movie_directors AS MD INNER JOIN directors AS D ON D.director_id = MD.director_id  WHERE movie_id = ? ");
                      $stmt->bind_param("i", $movie_id);
                      $stmt->execute();
                      $result = $stmt->get_result();

                      foreach ($result as $row) {
                        $director = $row['director_name'];
                        echo "<li> $director </li>";
                      }


                      ?>
                    </ul>
                  </div>

                </div>
              </div>
            </div>
            <div class="container-fluid d-flex flex-column">
              <h5 class="m-2">Have some thoughts? Write a review</h5>
              <div class="container-fluid d-flex flex-column">
                <!-- rating bar -->
                <div class="d-flex align-items-center mb-1">
                  <div id="myRatingCustomFeedbackStart" class="text-body-secondary me-3">0 / 5</div>
                  <div id="myRatingCustomFeedback"></div>
                  <div id="myRatingCustomFeedbackEnd" class="badge text-bg-dark ms-3">Meh</div>
                </div>
                <form>
                  <input class=" mt-3 rounded-3" style="min-width: 300px;" type="text" name="userReviewText"
                    id="userReviewText" placeholder="Write a review...">
                  <input class="rounded w-20" type="submit" value="Post a review" id="submitReview">
                </form>
              </div>
              <div>

              </div>
            </div>
          </div>

          <!-- add to favourites section -->
          <?php
          include("database.php");
          $stmt = $conn->prepare("SELECT favorite_id FROM favorites WHERE movie_id = ? AND user_id = ?");
          $stmt->bind_param("ii", $movie_id, $user_id);
          $user_id = $_SESSION["user_id"];
          $stmt->execute();
          $result = $stmt->get_result();
          if ($result->num_rows < 1):
          ?>
            <div class=" d-flex flex-column align-items-center float-end">
              <i onclick="addToFavourite(<?= $movie_id ?>)" style="font-size: 20px; background-color:darkgray" class="fa-regular fa-bookmark float-end p-2 text-white rounded"></i>
              <p class="badge">Add to favourties</p>
            </div>
          <?php else: ?>
            <div class=" d-flex flex-column align-items-center float-end">
              <i style="font-size: 20px; background-color:darkgray" class="fa-regular fa-bookmark  float-end p-2 rounded"></i>
              <p class="badge">On favorites</p>
            </div>
          <?php endif; ?>

        </div>


      </div>
    </div>
  </div>
  </div>
  <h1></h1>
<?php
endif;
if (isset($_SESSION["user_id"])) {
  $user_id = $_SESSION["user_id"];
} else {
  $user_id = 0;
}
?>

<div class="bg-secondary rounded p-3 mt-3 mb-4 m-3">
  <h3>Reviews on this movie</h3>
  <?php
  $stmt->prepare("SELECT U.first_name, U.last_name, R.comment, R.date, R.stars FROM reviews AS R INNER JOIN users AS U on R.user_id = U.user_id WHERE movie_id = ? ORDER BY R.date DESC");
  $stmt->bind_param("i", $movie_id);
  $stmt->execute();
  $reviews = $stmt->get_result();
  foreach ($reviews as $row):
  ?>

    <div class="container-fluid d-flex flex-column bg-body-tertiary rounded p-2 mb-2 mt-2">
      <div class="container-fluid">
        <div>
          <h5 class="me-4"> Reviewed by <?= $row["first_name"] ?></h5>
        </div>
        <div>
          <p> On <?= $row["date"] ?></p>
        </div>
      </div>
      <div class=" mt-2 container-fluid d-flex flex-column align-content-center">
        <div class="d-flex flex-row align-content-center">
          <div data-coreui-read-only="true" data-coreui-toggle="rating" data-coreui-value="<?= $row["stars"] ?>"></div>
          <p class="ms-2">(<?= $row["stars"]?> out of 5)</p>
        </div>
        <div>
          <p class="fst-italic"><?= $row["comment"] ?></p>
        </div>
      </div>

    </div>
  <?php endforeach ?>

</div>

<!-- Similar movies container -->
<div id="carouselSimilarMovies" class="carousel carousel-dark slide" data-bs-ride="carousel">
  <h1 class="m-5">Similar Movies</h1>
  <div class="carousel-inner">

    <?php
    include("database.php");
    $stmt = $conn->prepare("SELECT genre_id FROM movie_genres WHERE movie_id = ?");
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $genre_id;

    foreach ($result as $row) {
      $genre_id = $row['genre_id'];
    }

    $stmt = $conn->prepare("SELECT M.movie_id, M.movie_title, M.release_date, M.poster_filepath FROM movie_genres AS MG INNER JOIN movies AS M ON M.movie_id = MG.movie_id WHERE genre_id = ? ORDER BY RAND() LIMIT 9");
    $stmt->bind_param("i", $genre_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $active = true;
    $index = 0;
    foreach ($result as $row) {
      $poster_filepath = $row["poster_filepath"];
      $movie_id = $row["movie_id"];
      $movie_title = $row["movie_title"];
      $release_date = $row["release_date"];
      if ($active) {
        echo '<div class="carousel-item active">';
      }

      if ($index % 3 == 0 || $index == 0) {
        echo '<div class="card-wrapper container-sm d-flex  justify-content-around">
        ';
      }

      echo "
    <div class=\"card bg-dark border-4 border-dark text-light\" style=\"width: 18rem; height: 32rem\">
    <a href=\"cinemarvel_product_details.php?movie_id= $movie_id\">
          <img class=\"card-img-top img-fluid\" src=\"assets/movie_posters/$poster_filepath\" alt=\"$movie_title Poster\">
          </a>
          <div class=\"card-body text-center\">
            <h5 class=\"card-title fs-3\">$movie_title</h5>
            <p class=\"card-text\">$release_date</p>
          </div>
        </div>
    ";

      $index++;
      $active = false;

      if ($index % 3 == 0 && $index != 0 && $result->num_rows != $index) {
        echo '
      </div>
      </div>
      <div class="carousel-item">
      ';
      }

      else if($index % 3 == 0 && $index != 0 && $result->num_rows == $index){
        echo `
        </div>
        </div>
        `;
      }
    }
    echo "</div>";
    ?>

  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselSimilarMovies" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselSimilarMovies" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>


<!-- script to make the ratings bar reponsive to hover and click -->
<script>
  var rating_score = 0;
  const myRatingCustomFeedback = document.getElementById('myRatingCustomFeedback')
  const myRatingCustomFeedbackStart = document.getElementById('myRatingCustomFeedbackStart')
  const myRatingCustomFeedbackEnd = document.getElementById('myRatingCustomFeedbackEnd')
  if (myRatingCustomFeedback) {
    let currentValue = 0
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
      rating_score = event.value;
    })

    myRatingCustomFeedback.addEventListener('hover.coreui.rating', event => {
      myRatingCustomFeedbackEnd.innerHTML = event.value ? labels[event.value] : labels[currentValue]
    })

    // function when submit review button is clicked
    $("#submitReview").click(function(e) {
      e.preventDefault();
      var review_comment = $("#userReviewText").val() + "";
      review_comment = review_comment.trim();
      var movie_id = <?php echo $_GET["movie_id"] ?>;
      var user_id = <?php echo $user_id ?>;
      if (user_id > 0) {
        if (review_comment != "" && rating_score != 0) {
          $.ajax({
            type: "post",
            url: "submit_review.php",
            data: {
              rating_score: rating_score,
              review_comment: review_comment,
              movie_id: movie_id,
              user_id: user_id,
            },
            dataType: "json",
            success: function(response) {
              if (response.success) {
                //successfully submitted review
                window.location.reload();
                alert("Review Successfully added");


              } else {
                //show error message
                alert(response.error);
              }

            },

            error: function(response) {

            }
          });
        } else {
          alert("Star selection or comment cannot be empty");
        }

      } else {
        if (confirm("Please log in to review a movie")) {
          window.location.href = "cinemarvel_login.php";
        }
      }




    });
  }

  function addToFavourite(movie_id) {
    <?php
    if (isset($_SESSION["user_id"])) {
      echo "var user_id = " . $_SESSION["user_id"];
    } else {
      echo "var user_id = 0";
    }
    ?>

    if (user_id > 0) {
      $.ajax({
        type: "post",
        url: "add_to_fav.php",
        data: {
          movie_id: movie_id
        },
        dataType: "json",
        success: function(response) {
          console.log('AJAX response:', response);
          if (response.success) {
            alert("Movie successfully added to favourites");
          } else {
            alert(response.error);
          }
        },
        error: function(response) {
          alert("Something went wrong");
        }
      });
    } else {
      if (confirm("Please log in to add movies in favourite")) {
        window.location.href = "cinemarvel_login.php";
      }
    }
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