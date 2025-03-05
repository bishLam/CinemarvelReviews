<?php
include "database.php";

$response = ['success' => false, 'error' => " ", 'movies' => []];
$result = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $selectedGenres = json_decode(stripslashes($_POST['selectedGenres']));
  $string = implode(",", array_fill(0, count($selectedGenres), "?"));
  $type = str_repeat("i", count($selectedGenres)) . "i";
  $params = array_merge($selectedGenres, [count($selectedGenres)]);

  try {
    $stmt = $conn->prepare("SELECT m.*
FROM movies AS m
INNER JOIN movie_genres AS mg ON m.movie_id = mg.movie_id
WHERE mg.genre_id IN ($string)
GROUP BY m.movie_id
HAVING COUNT(DISTINCT mg.genre_id) = ?");
    $stmt->bind_param($type, ...$params);
    $stmt->execute();

    $result = $stmt->get_result();
    $movies = [];
    foreach ($result as $row) {
      $movies[] = $row;
    }
    if (!empty($movies)) {
      $response['movies'] = $movies;
      $response['success'] = true;
    } else {
      $response['error'] = "No movies found for that genre(s)";
    }
  } catch (mysqli_sql_exception $e) {
    $response['error'] = "Sqli exception: " . $e;
  }
}
else{
  $response['error'] = "Request method is not post";
}

echo json_encode($response);
