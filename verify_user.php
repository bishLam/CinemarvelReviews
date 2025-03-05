<?php
//implement login
session_start();
include("database.php");
$response = ['success' => false, 'error'  => "error"];
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

  $sql = "SELECT * FROM users WHERE email = '$username'";



  try {
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_array($result);
      if (password_verify($password, $row["password"])) {
        $_SESSION["user_id"] = $row["user_id"];
        $_SESSION["first_name"] = $row["first_name"];
        $_SESSION["last_name"] = $row["last_name"];
        $_SESSION["email"] = $row["email"];
        $_SESSION["member_since"] = $row["member_since"];
        $_SESSION["phone_number"] = $row["phone_number"];
        $response['success'] = true;
      } else {
        $response['error'] = "Wrong password";
      }
    } else {
      $response['error'] = "User not found in the database";
    }
  } catch (mysqli_sql_exception $e) {
    // echo "Exception occured";
    $response['error'] = " Sql exception"; //0 means sql exception
  }
  echo json_encode($response);
}
