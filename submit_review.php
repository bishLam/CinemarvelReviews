<?php
$response = ['success' => false, 'error' => ""];
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $user_id = filter_input(INPUT_POST, "user_id", FILTER_SANITIZE_SPECIAL_CHARS);
  $movie_id = filter_input(INPUT_POST, "movie_id", FILTER_SANITIZE_SPECIAL_CHARS);
  $rating_score = filter_input(INPUT_POST, "rating_score", FILTER_SANITIZE_SPECIAL_CHARS);
  $review_comment = filter_input(INPUT_POST, "review_comment", FILTER_SANITIZE_SPECIAL_CHARS);

  include "database.php";
  try {

    $stmt = $conn->prepare("SELECT * FROM reviews WHERE movie_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $movie_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $response['error'] = "You already have a review for this movie";
    } else {
      $stmt = $conn->prepare("INSERT INTO reviews (stars, `comment`, user_id, movie_id) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("isii", $rating_score, $review_comment, $user_id, $movie_id);
      $stmt->execute();
      $response['success'] = true;
    }
  } catch (mysqli_sql_exception $e) {
    $response['error'] = "Sql exception: " . $e;
  }

  echo json_encode($response);
  exit();
}
