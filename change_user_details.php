<?php
session_start();
include("database.php");
$response = ['success' => false, 'error' => 'err'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user_id = $_SESSION["user_id"];


  // idk for some reason the below code caused the details change to not happen. after commenting it, it is working fine
  // if (!empty($_POST["firstName"]) && !empty($_POST["lastName"]) && !empty($_POST["email"]) && !empty($_POST["oldPassword"]) && !empty($_POST["newPassword"]) && !empty($_POST["phoneNumber"])) {
  //new changed details

  $newFirstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_SPECIAL_CHARS);
  $newLastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_SPECIAL_CHARS);
  $newEmail = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $oldPassword = $_POST["oldPassword"];
  $newPassword1 = filter_input(INPUT_POST, "newPassword", FILTER_SANITIZE_SPECIAL_CHARS);
  $newPassword = password_hash($newPassword1, PASSWORD_DEFAULT);
  $newPhoneNumber = filter_input(INPUT_POST, "phoneNumber", FILTER_SANITIZE_SPECIAL_CHARS);

  // verify the password
  try {
    $sql = "SELECT `password` FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);


    $hash = $row['password'];

    if (password_verify($oldPassword, $hash)) {

      $sql = "UPDATE users SET first_name = '$newFirstName', last_name = '$newLastName', email = '$newEmail', `password` = '$newPassword', phone_number = '$newPhoneNumber' WHERE user_id = $user_id";
      $result = mysqli_query($conn, $sql);
      $response['success'] = true;
      $_SESSION["first_name"] = $newFirstName;
      $_SESSION["last_name"] = $newLastName;
      $_SESSION["email"] = $newEmail;
      $_SESSION["phone_number"] = $newPhoneNumber;
      echo json_encode($response);
      exit();
    } else {
      $response['error'] = "Old password is not correct";
      echo json_encode($response);
      exit();
    }
  } catch (mysqli_sql_exception) {
    $response['error'] = "Could not update user";
    echo json_encode($response);
    exit();
  }
  // } else {
  //   $response['error'] = "Not every value was set" . $value;
  //   echo json_encode($response);
  //   exit();
  // }
}
// }
