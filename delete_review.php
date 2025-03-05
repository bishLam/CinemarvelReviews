<?php
include "database.php";

$response = ['success' => false, 'error' => ""];

if($_SERVER['REQUEST_METHOD'] == "POST"){
  $review_id = filter_input(INPUT_POST, "review_id", FILTER_SANITIZE_SPECIAL_CHARS);

  try{
    $stmt = $conn->prepare("DELETE FROM reviews WHERE review_id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    $response['success'] = true;
  }
  catch(mysqli_sql_exception $e){
    $response['error'] = "sql exception: " .$e;
  }
}

echo json_encode($response);