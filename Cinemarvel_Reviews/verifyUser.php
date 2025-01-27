<?php
session_start();
//implement login
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (!empty($_POST["userInputEmail1"]) && !empty($_POST["userInputPassword1"])) {
    if (isset($_POST["signInButton1"])) {
      include("database.php");
      $username = filter_input(INPUT_POST, "userInputEmail1", FILTER_SANITIZE_SPECIAL_CHARS);
      $password = filter_input(INPUT_POST, "userInputPassword1", FILTER_SANITIZE_SPECIAL_CHARS);

      $sql = "SELECT * FROM users WHERE email = '$username'";



      try {
        $result = mysqli_query($conn, $sql);
      } catch (mysqli_sql_exception $e) {
        // echo "Exception occured";
        $_SESSION["error_code"] = " Sql exception"; //0 means sql exceptoin
      }

      if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        if (password_verify($password, $row["password"])) {

          $_SESSION["user_id"] = $row["user_id"];
          $_SESSION["username"] = $row["username"];
          $_SESSION["email"] = $row["email"];
          $_SESSION["member_since"] = $row["member_since"];
          header("Location: index.php");
          exit();
        } else {
          $_SESSION["error-code"] = "Wrong password";
        }
      } else {
        // echo "User not found in the database";
        $_SESSION["user_id"] = null;
        $_SESSION["email"] = null;
        $_SESSION["username"] = null;
        $_SESSION["email"] = null;
        $_SESSION["member_since"] = null;
        $_SESSION["error_code"] = "User not found in the database"; //1 means user not found in the database
        exit();
      }
    } else {
      $_SESSION["user_id"] = null;
      $_SESSION["email"] = null;
      $_SESSION["username"] = null;
      $_SESSION["email"] = null;
      $_SESSION["member_since"] = null;
      $_SESSION["error_code"] = "Username or password cannot be empty";
      //2 means Username or password cannot be null

      // echo"Username or password cannot be null";
      exit();
    }
  }
}
