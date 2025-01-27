<?php
session_start();
?>

<!doctype html>
<html lang="en">

<head>
  <title>Cinemarvel Reviews</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- this is for the star rating from core ui -->
  <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@5.5.0/dist/css/coreui.min.css" rel="stylesheet"
    integrity="sha384-r/2d1aBwRhQSqjQh/21GvBPd8Oq6JOk0rWn05ngWyhiPOL79wFjw7vcbn3HwYGC/" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@5.5.0/dist/js/coreui.bundle.min.js"
    integrity="sha384-fB1r9DlcQtqFJcV4iBZiGPf1lH3BrXFpqFFYC0QTRc29fB9HQhpuEhjQwM9j96yG" crossorigin="anonymous">
  </script>


</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="./assets/cinemarvel_icons/cinemarvel_logo.png" width="100" alt="">
                </a>
                <p class="text-center">Review like a Marvelite</p>
                <p class="text-warning">
                  <?php
                  if (isset($_SESSION["error-code"])) {
                    echo $_SESSION["error-code"];
                    unset($_SESSION["error-code"]);
                  }
                  ?>
                </p>
                <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                  <div class="mb-3">
                    <label for="signupFirstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="signupUsername" aria-describedby="textHelp">
                  </div>
                  <div class="mb-3">
                    <label for="signupLastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="signupLastName" aria-describedby="textHelp">
                  </div>
                  <div class="mb-3">
                    <label for="signupEmail" class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="signupEmail" aria-describedby="emailHelp">
                  </div>
                  <div class="mb-4">
                    <label for="signupPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" name="signupPassword">
                  </div>
                  <button name="signUpButton" class="btn btn-primary w-100 fs-4 mb-5 rounded-2">Sign Up</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-6 mb-0 fw-bold">Already have an Account?</p>
                    <a class="text-primary fw-bold ms-2" href="./cinemarvel_login.php">Sign In</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["signUpButton"])) {
    if (!empty($_POST["signupUsername"]) && !empty($_POST["signupLastName"]) && !empty($_POST["signupEmail"]) && !empty($_POST["signupPassword"])) {
      include "database.php";
      $firstname = filter_input(INPUT_POST, "signupUsername", FILTER_SANITIZE_SPECIAL_CHARS);
      $lastname = filter_input(INPUT_POST, "signupLastName", FILTER_SANITIZE_SPECIAL_CHARS);
      $email = filter_input(INPUT_POST, "signupEmail", FILTER_SANITIZE_SPECIAL_CHARS);
      $password = filter_input(INPUT_POST, "signupPassword", FILTER_SANITIZE_SPECIAL_CHARS);

      try {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql1 = "SELECT * FROM `users` WHERE email = '$email'";
        $result1 = mysqli_query($conn, $sql1);

        if (mysqli_num_rows($result1) > 0) {
          $_SESSION["error-code"] = "User already exists, please log in instead";
          exit();
        } else {
          $sql = "INSERT INTO users (first_name, last_name, email, `password`) VALUES ('$firstname', '$$lastname', '$email', '$hash')";
          if ($conn->query($sql) === TRUE) {
            $_SESSION["email"] = $email;
            $_SESSION["first_name"] = $firstname;
            $_SESSION["last_name"] = $lastname;
            $_SESSION["member_since"] = date("Y-m-d");
            $_SESSION["user_id"] = $row["user_id"];
            header("Location: index.php");
            exit();
          } else {
            $_SESSION["error-code"] = "could not insert user in the database";
          }
        }
      } catch (mysqli_sql_exception $e) {
        $_SESSION["error-code"] = "Server problem";
        exit();
      }
    } else {
      $_SESSION["error-code"] = "All the fields with * are mandatory";
      exit();
    }
  }
}

?>