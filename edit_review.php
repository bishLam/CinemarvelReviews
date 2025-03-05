<?php

$response = ['success' => false, 'error' => ""];
if($_SERVER['REQUEST_METHOD'] == "POST"){
  $review_id = filter_input(INPUT_POST, "review_id", FILTER_SANITIZE_SPECIAL_CHARS);
  $stars = filter_input(INPUT_POST, "rating_score", FILTER_SANITIZE_SPECIAL_CHARS);
  $comment = filter_input(INPUT_POST, "userReviewText", FILTER_SANITIZE_SPECIAL_CHARS);


  try{
  include("database.php");
  $stmt = $conn->prepare("UPDATE reviews SET stars = ?, comment = ? WHERE review_id = ?");
  $stmt->bind_param("isi", $stars, $comment, $review_id );
  $stmt->execute();
  $response['success'] = true;
  }

  catch(mysqli_sql_exception $e){
    $response['error'] = "sql exception";
  }
  echo json_encode($response);
}