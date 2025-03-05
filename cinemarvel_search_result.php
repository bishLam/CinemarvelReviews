<!DOCTYPE html>
<?php
include "header.php";
?>

<html lang="en">

<head>
  <title>Search result</title>
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

<body>
  <!-- search result row - each row will display three results -->

  <?php
  include "database.php";
  $search_text = htmlspecialchars($_GET["search_text"]);
  $sql = "SELECT * FROM movies WHERE movie_title LIKE '%$search_text%'";

  try {
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {

      echo "<div class=\"bg-danger p-5 m-1 rounded\">";
      echo "<h1 m-3>Search result for \"$search_text\" </h1>";
      echo "<div class=\"card-group m-5\">";
      $counter = 0;
      // some data exists
      while ($row = mysqli_fetch_assoc($result)) {
        $movie_id = htmlspecialchars($row["movie_id"]);
        $movie_title = htmlspecialchars($row["movie_title"]);
        $description = htmlspecialchars($row["description"]);
        $poster_filepath = htmlspecialchars($row["poster_filepath"]);
        $duration = htmlspecialchars($row["duration"]);

        echo "
        <a href=\"cinemarvel_product_details.php?movie_id=$movie_id\" style=\"text-decoration:none; color:inherit\">
        <div class=\"card m-2\">
    <img src=\"assets/movie_banners/$poster_filepath\" class=\"card-img-top\" alt=\"$movie_title poster\" style=\"max-height:250px\">
    <div class=\"card-body\">
      <h5 class=\"card-title\">$movie_title</h5>
      <p class=\"card-text\">$description</p>
      <p class=\"card-text\"><small class=\"text-muted\">Last updated 3 mins ago</small></p>
      </a>
    </div>
  </div>
        
        ";

        $counter++;

        if ($counter % 3 == 0) {
          echo "</div> <div class=\"card-group m-5\">";
        }
      }
      echo "</div>";
      echo "</div>";
    } else {
      echo "<h1>No results found for $search_text </h1>";
    }
  } catch (mysqli_sql_exception $e) {
    // echo "Exception occured";
    $_SESSION["error_code"] = " Sql exception"; //0 means sql exceptoin
  }
  ?>
  <style>
    
  </style>

</body>
</html>

<?php
include "footer.php"
?>