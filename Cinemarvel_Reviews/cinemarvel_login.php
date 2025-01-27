<?php
session_start();
$isFormSubmitted = ($_SERVER["REQUEST_METHOD"] == "POST");
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
                <a href="index.php" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="assets/cinemarvel_icons/cinemarvel_logo.png" width="100" alt="cinemarvel logo">
                </a>
                <p class="text-center">Your reviewing destination for all Marvels</p>
                <p class="text-center text-danger" id="user-message">
                  <?php
                  if ($isFormSubmitted && isset($_SESSION["error_code"])) {
                    // display the error code 
                    echo $_SESSION["error_code"];
                    unset($_SESSION["error_code"]);
                  }
                  ?>

                </p>
                <!-- actual sign up form -->
                <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                  <div class="mb-3">
                    <label for="userInputEmail1" class="form-label">Username</label>
                    <input type="email" class="form-control" id="userInputEmail1" name="userInputEmail1" aria-describedby="emailHelp">
                  </div>
                  <div class="mb-4">
                    <label for="userInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" name="userInputPassword1" id="userInputPassword1">
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary text-siz" type="checkbox" value="" id="flexCheckChecked" checked>
                      Remember this Device
                      </label>
                    </div>
                    <a class="text-primary fw-bold" href="#">Forgot Password ?</a>
                  </div>
                  <button class="btn btn-primary w-100 fs-4 mb-4 rounded-2" name="signInButton1" id="signInButton1">Sign In</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-7 mb-0 fw-bold">New to Cinemarvel Movies?</p>
                    <a class="text-primary fw-bold ms-2 fs-7" href="./cinemarvel_signup.php" id="signUpButton1">Create an account</a>
                  </div>
                </form>
                </di>
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
include "verifyUser.php"
?>