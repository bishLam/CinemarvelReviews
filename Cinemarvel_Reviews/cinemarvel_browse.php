<?php
include("database.php");
// Fetch genres from the genre table
$genre_query = "SELECT genre_id, genre_name FROM genres";
$genre_result = mysqli_query($conn, $genre_query);
if (!$genre_result) {
  die("Genre query failed: " . mysqli_error($conn));
}

$genres = mysqli_fetch_all($genre_result, MYSQLI_ASSOC);

// Fetch movies for each genre
$all_movies = [];
foreach ($genres as $genre) {
  $current_genre_id = $genre['genre_id'];
  $movies_query = "SELECT m.movie_title, m.release_date, m.poster_filepath 
                 FROM movies m 
                 JOIN movie_genres mg ON m.movie_id = mg.movie_id 
                 WHERE mg.genre_id = $current_genre_id";
  $movies_result = mysqli_query($conn, $movies_query);
  if (!$movies_result) {
    die("Movie query failed for genre_id $current_genre_id: " . mysqli_error($conn));
  }
  $all_movies[$current_genre_id] = mysqli_fetch_all($movies_result, MYSQLI_ASSOC);
}

// Get the genre ID from the URL, defaulting to 0 if not set
$genre_id = intval($_GET['genre_id'] ?? 0);

// Query to fetch movies based on genre ID or fetch all movies if genre ID is 0
$query = $genre_id > 0
  ? "SELECT movie_title, release_date, poster_filepath FROM movies WHERE genre_id = $genre_id LIMIT 20"
  : "SELECT movie_title, release_date, poster_filepath FROM movies LIMIT 20";

$result = mysqli_query($conn, $query);
if (!$result) {
  die("Movie query failed: " . mysqli_error($conn));
}
include("header.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie Card</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js">
  </script>

  <style>
    .navbar-nav {
      display: flex;
      justify-content: center;
    }

    .nav-item {
      margin-right: 20px;
    }

    .movies-container {
      display: none;
    }

    #genre-card div {
      transition: background-color 0.3s ease-in-out;
    }

    #genre-card div:hover {
      background-color: #002d64;
    }
  </style>
</head>

<body class="bg-dark">
  <div class="container mt-2 bg-danger p-4 border-danger rounded">
    <h2 class="text-light">Browse by Genre</h2>

    <!-- Carousel of Genre Cards -->
    <div id="carouselGenres" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php
        $is_first = true;
        for ($i = 0; $i < count($genres); $i += 3): ?>
          <div class="-md-4 mb-4 carousel-item <?= $is_first ? 'active' : '' ?>">
            <div id="genre-card" class="d-flex justify-content-around">
              <?php for ($j = $i; $j < $i + 3 && $j < count($genres); $j++): ?>
                <div class="card bg-dark text-light m-3" style="width: 18rem;"
                  onclick="showMovies(<?= $genres[$j]['genre_id'] ?>)">
                  <div class="card-body text-center">
                    <h5 class="card-title"><?= htmlspecialchars($genres[$j]['genre_name']) ?></h5>
                  </div>
                </div>
              <?php endfor; ?>
            </div>
          </div>
        <?php
          $is_first = false;
        endfor; ?>
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

    <!-- Filtered Results -->
    <?php foreach ($all_movies as $genre_id => $movies): ?>
      <div class="movies-container" id="movies-<?= $genre_id ?>">
        <h3 class="text-light mt-4">Movies in
          <?= htmlspecialchars($genres[array_search($genre_id, array_column($genres, 'genre_id'))]['genre_name']) ?>
        </h3>
        <div class="row">
          <?php foreach ($movies as $movie): ?>
            <div class="col-md-4 mb-4">
              <div class="card bg-dark text-light">
                <a href="cinemarvel_product_details.php?movie_id=<?php echo $movie['movie_id']; ?>">
                  <img class="card-img-top" src="assets/movie_posters/<?php echo $movie['poster_filepath']; ?>"
                    alt="<?php echo htmlspecialchars($movie['movie_title']); ?> Poster">
                </a>
                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($movie['movie_title']) ?></h5>
                  <p class="card-text">Released: <?= htmlspecialchars($movie['release_date']) ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="container mt-5 bg-danger p-5 border-danger rounded">
    <h2 class="text-light mb-4">Browse All Movies</h2>
    <div class="row">
      <?php while ($movie = mysqli_fetch_assoc($result)): ?>
        <div class="col mb-4">
          <div class="card bg-dark border-1 border-dark text-light p-2" style="width: 16rem; height: 30rem">
            <img class="card-img-top" src="assets/movie_posters/<?php echo $movie['poster_filepath']; ?>"
              alt="<?php echo htmlspecialchars($movie['movie_title']); ?> Poster">
            <div class="card-body text-center">
              <h5 class="card-title fs-4"><?php echo htmlspecialchars($movie['movie_title']); ?></h5>
              <p class="card-text"><?php echo date('d M Y', strtotime($movie['release_date'])); ?></p>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <script>
    function showMovies(genreId) {
      // Hide all movie sections
      document.querySelectorAll('.movies-container').forEach(container => {
        container.style.display = 'none';
      });
      // Show the selected genre's movies
      document.getElementById('movies-' + genreId).style.display = 'block';
    }
  </script>

</body>

</html>