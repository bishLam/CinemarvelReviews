<?php
session_start();
include("database.php");
$response = ['success' => false, 'error' => ""];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
  } else {
    header("cinemarvel_login.php");
  }


  $movie_id = filter_input(INPUT_POST, "movie_id", FILTER_SANITIZE_SPECIAL_CHARS);
  try {

    $stmt = $conn->prepare("SELECT * FROM favorites WHERE user_id = ? AND movie_id = ?");
    $stmt->bind_param("ii", $user_id, $movie_id);
    $stmt->execute();
    $result = $stmt->get_result()->num_rows;
    if ($result > 0) {
      $response['error'] = "Movie already added";
    } else {
      $stmt = $conn->prepare("INSERT INTO favorites(user_id, movie_id) VALUES (?, ?)");
      $stmt->bind_param("ii", $user_id, $movie_id);
      $stmt->execute();
      $response['success'] = true;
    }
  } catch (mysqli_sql_exception $e) {
    $response['error'] = "Sqli exception " . $e;
  }
  echo json_encode($response);
}
