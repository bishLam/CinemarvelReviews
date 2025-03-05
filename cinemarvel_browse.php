<!DOCTYPE html>
<?php
include "header.php";
?>

<html lang="en">

<head>
  <title>Browse marvel movies</title>
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

<body class="bg-dark">
  <h2 class="text-light container">Apply filters</h2>
  <div class="container mt-3 d-flex justify-content-center">
    <?php
    include "database.php";
    $stmt = $conn->prepare("SELECT * FROM genres LIMIT 10");
    $stmt->execute();

    $result = $stmt->get_result();
    foreach ($result as $genre):
    ?>

      <div class="form-check me-3">
        <?php
        $genre_id = $genre["genre_id"];
        $genre_name = $genre["genre_name"];

        ?>
        <input class="form-check-input" onchange="itemselected('<?= $genre_id ?>', '<?= $genre_name ?>')" type="checkbox" id="genre-<?= $genre["genre_id"] ?>">
        <label class="form-check-label text-light" for="genre-<?= $genre["genre_id"] ?>">
          <?= $genre["genre_name"] ?>
        </label>
      </div>

    <?php endforeach; ?>

  </div>

  <!-- filtering functionalities -->
  <div class="container mt-2 d-flex flex-row justify-content-center align-items-center">
    <textarea readonly style="min-width:300px; font-size:small" class="rounded text-bg-dark" name="" id="selectedGenres"></textarea>
    <button id="filterBtn" class="btn float-end ms-5 rounded btn-outline-light ps-3 pe-3" type="button" value=""> Filter </button>
  </div>

  <div id="filteredDiv" class="container mt-5 bg-danger p-4 border-danger rounded">

    <?php
    include "database.php";
    $stmt = $conn->prepare("SELECT m.* , g.genre_name FROM movie_genres as mg INNER JOIN movies AS m ON mg.movie_id = m.movie_id INNER JOIN genres AS g ON mg.genre_id = g.genre_id WHERE mg.genre_id = ?");
    $stmt->bind_param("i", $genre_id);
    $genre_id = $_GET["genre_id"] ?? 0; //if undefined set 0 as genre id
    $stmt->execute();

    $result = $stmt->get_result();
    $movie = $result->fetch_array();
    $genre_name = $movie["genre_name"];
    if (!$genre_name) {
      $stmt = $conn->prepare("SELECT * FROM movies");
      $stmt->execute();

      $result = $stmt->get_result();
      $genre_name = "All movies";
      echo "<h3> All Movies </h3>";
    } else {
      echo "<h3 class=\" mb-4 \">Movies on $genre_name </h3>";
    }
    $i = 0;
    foreach ($result as $movie): ?>
      <?php if ($i == 0): ?>
        <div class="card-group">
        <?php endif ?>
        <?php if ($i % 3 == 0 && $i != 0): ?>
        </div>
        <div class="card-group">
        <?php endif ?>
        <div style="max-width: 31%;" class="card m-3 rounded">
          <div class="card-img-top" style="position:relative">
            <img style="height: 200px;" class="card-img-top" src="assets/movie_banners/<?= $movie["poster_filepath"] ?>" alt="Card image cap">
            <?php
            $movie_id = $movie["movie_id"];
            $user_id = $_SESSION["user_id"];
            $stmt = $conn->prepare("SELECT * FROM favorites WHERE user_id = ? AND movie_id = ?");
            $stmt->bind_param("ii", $user_id, $movie_id);
            $stmt->execute();
            $result2 = $stmt->get_result()->num_rows;
            $isInFav = $result2 > 0 ? "red" : "gray";
            ?>
            <div
              style="float:right; position:absolute; right: 10px; top: 0px; font-size:3em;">
              <i class="fa-solid fa-bookmark" style="color: <?php echo $isInFav ?> ;"></i>
            </div>
          </div>

          <div class="card-body">
            <h5 class="card-title"><?= $movie["movie_title"] ?></h5>
            <p class="card-text"><?= $movie["description"] ?></p>
            <div class="d-flex flex-row justify-content-between">
              <button onclick="addToFavs(<?= $movie_id ?>)" class="btn btn-outline-info">Add to fav</button>
              <button onclick="viewMoreDetails(<?= $movie_id ?>)" class="btn btn-outline-info">View more</button>
            </div>
          </div>
          <div class="card-footer">
            <small class="text-muted">Released on <?= $movie["release_date"] ?></small>
          </div>
        </div>

      <?php $i++;
    endforeach; ?>
        </div>
        <p class="font-monospace fs-4 d-flex flex-row justify-content-center m-4">End of movies list</p>
  </div>


</body>
<?php
include "footer.php";
?>

</html>


<script>
  var selectedGenres = [];

  function itemselected(genre_id, genre_name) {
    if (selectedGenres.includes(genre_id)) {
      let index = selectedGenres.indexOf(genre_id);
      selectedGenres = selectedGenres.filter(id => id !== genre_id);


    } else {
      selectedGenres.push(genre_id);
    }

    let selectedNames = "";
    $(".form-check-input").each(function() {
      // element == this
      if (this.checked) {
        selectedNames += $(this).siblings("label").text().trim() + ", ";
      }

    });
    $("#selectedGenres").html(selectedNames.slice(0, -2));

  }

  $("#filterBtn").click(function(e) {
    JSONstring = JSON.stringify(selectedGenres);
    e.preventDefault();
    if (selectedGenres.length > 0) {

      $.ajax({
        type: "post",
        url: "filter_movies.php",
        data: {
          selectedGenres: JSONstring
        },
        dataType: "json",
        success: function(response) {
          if (response.success) {
            //we need to use the data received from the sql and show it here


            let moviesHTML = response.movies.map((movie, index) => {
              // Open a new card group every 3 movies
              let startOfGroup = index % 3 === 0 ?
                `<div class="card-group">` :
                '';
              let startOfContainer = index == 0 ?
                `<h3> Filtered movies </h3>` :
                '';

              // Close the card group after the last movie in the group
              let endOfGroup = (index % 3 === 2 || index === response.movies.length - 1) ?
                `</div>` :
                '';

              return `
            ${startOfContainer}
        ${startOfGroup}
        <div style="max-width: 31%;" class="card m-3 rounded">
            <div class="card-img-top" style="position:relative">
                <img style="height: 200px;" class="card-img-top" src="assets/movie_banners/${movie.poster_filepath}" alt="Card image cap">
                <div style="float:right; position:absolute; right: 10px; top: 0px; font-size:3em;">
                    <i class="fa-solid fa-bookmark" style="color: gray;"></i>
                </div>
            </div>
            <div class="card-body">
                <h5 class="card-title">${movie.movie_title}</h5>
                <p class="card-text">${movie.description}</p>
                <div class="d-flex flex-row justify-content-between">
                <button onclick="addToFav(${movie.movie_id})" class="btn btn-outline-info float-end">Add to fav</button>
                <button onclick="viewMoreDetails(${movie.movie_id})" class="btn btn-outline-info float-end">View more</button>
                </div>
            </div>
            <div class="card-footer">
                <small class="text-muted">Released on ${movie.release_date}</small>
            </div>
        </div>
        ${endOfGroup}
    `;
            }).join();

            // Append the final HTML
            $("#filteredDiv").html(moviesHTML);






          } else {
            alert(response.error);
          }
        },
        error: function(response) {
          alert(response.error);
        }
      });
    } else {
      alert("Please select a filtering option");
    }

  });

  function addToFavs(movie_id) {

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
            window.location.reload();
            alert("Movie successfully added to favorites");
          } else {
            alert(response.error);
          }
        },
        error: function(response) {
          alert("Something went wrong");
        }
      });
    } else {
      if (confirm("Please login first to add movies on favourites")) {
        window.location.href = "cinemarvel_login.php";
      } else {

      }
    }
  }

  //function to show details of the movie
  function viewMoreDetails(movie_id) {
    window.location.href = "cinemarvel_product_details.php?movie_id=" + movie_id;
  }
</script>