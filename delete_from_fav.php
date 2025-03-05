<?php
$response = ['success' => false, 'error' => ""];

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $fav_id = filter_input(INPUT_POST, "fav_id", FILTER_SANITIZE_SPECIAL_CHARS);

  try{
    include"database.php";
  $stmt = $conn->prepare("DELETE FROM favorites WHERE favorite_id = ?");
  $stmt->bind_param("i", $fav_id);
  $stmt->execute();
  $response['success'] = true;
}
catch(mysqli_sql_exception $e){
    $response['error'] = "Sql exception occured" .$e;
}

}
echo json_encode($response);