<!DOCTYPE html>
<?php
include "header.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>My favourites</title>
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
  <h3 class="m-3">My favourites list</h3>
  <?php
  include "database.php";
  $user_id = $_SESSION["user_id"];
  $stmt = $conn->prepare("SELECT f.favorite_id, f.added_date, f.movie_id, m.movie_title, m.release_date, m.description, m.poster_filepath, m.duration FROM favorites AS f INNER JOIN movies AS m on f.movie_id = m.movie_id  WHERE user_id = ? ORDER BY f.added_date DESC");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  ?>

  <?php if ($result->num_rows > 0): ?>

    <?php foreach ($result as $row): ?>
      <div class="card bg-body-tertiary container-fluid mb-5">
        <div class="card-header">
          Added on <?= $row['added_date'] ?> <!-- Display the favourite added date -->
        </div>
        <div class="d-flex flex-row align-items-center">
          <div class="img-fluid p-2 me-3">
            <img class="img-fluid" src="assets/movie_posters/<?= $row['poster_filepath'] ?>" style="max-width: 150px;" alt="<?= $row['movie_title'] ?> poster">
          </div>
          <div class="card-body">
            <h5 class="card-title"><?= $row['movie_title'] ?></h5> <!-- Display the movie title -->
            <p class="card-text"><?= $row['description'] ?></p> <!--  Display  movie description -->
            <p class="card-text">Released on : <?= $row['release_date'] ?></p> <!--  Display  movie description -->

            <?php $movie_id = $row["movie_id"] ?>
            <button onclick="viewMoreDetails(<?= $movie_id ?>)" class="btn btn-outline-info">View more</button>
          </div>
          <div>
            <?php
            $fav_id = $row["favorite_id"];
            ?>
            <i style="font-size: 20px; color:red" class="fa-solid fa-trash deleteReview p-2" onclick="deleteFavourite(<?= $fav_id ?>)"></i>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

  <?php else: ?>
    <p>You do not have any movies in favorites. Add movies to manage your favourites list</p>
    <a href="cinemarvel_browse.php">Click me to browse all movies</a>

  <?php endif; ?>



</body>

</html>

<!-- script for delete functionality -->

<script>
  function deleteFavourite(fav_id) {
    if (confirm("Are you sure you want to delete this movie from favourites?")) {
      $.ajax({
        type: "post",
        url: "delete_from_fav.php",
        data: {
          fav_id
        },
        dataType: "json",
        success: function(response) {
          if (response.success) {
            alert("Movie removed from favourties successfully.");
            location.reload();
          } else {
            alert(response.error);
          }

        },
        error: function(response) {
          alert("something went wrong");
        }
      });
    }

  }

  //function to show details of the movie
  function viewMoreDetails(movie_id) {
    window.location.href = "cinemarvel_product_details.php?movie_id=" + movie_id;
  }
</script>