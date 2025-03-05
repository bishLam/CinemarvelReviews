<!DOCTYPE html>
<?php
include "header.php";
?>

<html lang="en">

<head>
  <title>Profile</title>
  <meta charset="utf-8">
  <meta name="author" content="bishonath+syeda">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/cinemarvel_icons/cinemarvel_logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous">
  </script>
  <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
</head>

<body>
  <div class="row container-fluid bg-secondary rounded p-3">
    <div class="col-lg-10">
      <div class="d-flex flex-column justify-content-center align-items-center">
        <div class="img-rounded p-3">
          <img src="assets/cinemarvel_icons/navigation/icons8-test-account-96.png" alt="">
        </div>
        <div class="text text-light">
          <h2 class="font-monospace"><?php
              if (isset($_SESSION["first_name"]) && $_SESSION["last_name"]) {
                echo $_SESSION["first_name"] . " " . $_SESSION["last_name"];
              } else {
                echo "Not logged in";
              }
              ?></h2>
        </div>

        <div class="text text-light">
          <p class="font-monospace"><?php
              if (isset($_SESSION["email"])) {
                echo $_SESSION["email"];
              }
              ?></p>
          <p class="font-monospace">
            <?php
            if (isset($_SESSION["member_since"])) {
              echo "Member since: " . $_SESSION["member_since"];
            }
            ?>
          </p>
        </div>

        <div class="text text-light">
          <p class="font-monospace">
            <?php
            include("database.php");
            $user_id = $_SESSION["user_id"];
            $stmt = $conn->prepare("SELECT COUNT(review_id) AS total_reviews FROM reviews WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $total_reviews;
            foreach ($result as $row) {
              $total_reviews =  $row['total_reviews'];
            }
            if ($total_reviews) {
              echo "$total_reviews total reviews";
            } else {
              echo "No reviews yet";
            }




            ?>
          </p>
        </div>
      </div>
    </div>

    <div class="col-lg-2 rounded bg-dark-subtle p-2 d-flex flex-column justify-content-lg-start">

      <div class="m-1">
        <h4>Account settings</h4>
      </div>

      <div class="m-1" id="editDetailsButton">
        <button>Edit details</button>
      </div>
      <div class="m-1">
        <button id="manageReviews">Manage my reviews</button>

      </div>
      <div class="m-1">
        <button id="logoutButton">Log out</button>
      </div>
      <div class="m-1">
        <button id="myFavouritesButton">Favourites</button>
      </div>
    </div>

  </div>

  <!-- edit details form. This form will disappear until the button is clicked -->
  <div class="container-fluid bg-body-secondary" id="editDetailsDiv" style="display:none">
    <form method="post" class="row m-3 g-3">
      <div class="col-md-4">
        <label for="firstName" class="form-label">First name</label>
        <input type="text" class="form-control" name="firstName" id="firstName" value="<?php echo $_SESSION["first_name"] ?>" required>

      </div>
      <div class="col-md-4">
        <label for="lastName" class="form-label">Last name</label>
        <input type="text" class="form-control" name="lastName" id="lastName" value="<?php echo $_SESSION["last_name"] ?>">
        <div class="valid-feedback">
          Looks good!
        </div>
      </div>
      <div class="col-md-4">
        <label for="email" class="form-label">Email</label>
        <div class="input-group has-">
          <span class="input-group-text" id="inputGroupPrepend">@</span>
          <input type="email" class="form-control" name="email" id="email" value="<?php echo $_SESSION["email"] ?>" aria-describedby="inputGroupPrepend">
        </div>
      </div>
      <div class="col-md-4">
        <label for="oldPassword" class="form-label">Old Password</label>
        <input type="password" class="form-control" name="oldPassword" id="oldPassword">
      </div>

      <div class="col-md-4">
        <label for="newPassword" class="form-label">New Password</label>
        <input type="password" class="form-control" name="newPassword" id="newPassword">
      </div>

      <div class="col-md-4">
        <label for="phoneNumber" class="form-label">Contact Number</label>
        <input type="number" class="form-control" name="phoneNumber" id="phoneNumber" value="<?php echo $_SESSION["phone_number"] ?>">
      </div>
      <div class="col-12">
        <button name="submitForm" id="submitForm" class="btn btn-primary" type="submit">Save changes</button>
      </div>
      <h4 id="statusMessage"></h4>
    </form>

  </div>

  <?php
  include("footer.php");

  // edit user details implementation

  //original user details
  ?>

</body>

</html>

<script>
  $(document).ready(function() {
    // logout function
    $("#logoutButton").click(function(e) {
      e.preventDefault();
      if (confirm("Are you sure you want to log out?")) {
        window.location.href = "cinemarvel_logout.php";
      }
    });

    //manageReviews function
    $("#manageReviews").click(function(e) {
      window.location.href = "cinemarvel_manage_reviews.php"

    });

    // myfavourites button function
    $('#myFavouritesButton').click(function() {
      window.location.href = "cinemarvel_favourites.php";
    });

    //editdetails button 
    $("#editDetailsButton").click(function(e) {
      e.preventDefault();
      $("#editDetailsDiv").toggle();
    });


    $("#submitForm").click(function(e) {
      e.preventDefault();


      //collect form data
      const firstName = $("#firstName").val().trim();
      const lastName = $("#lastName").val().trim();
      const email = $("#email").val().trim();
      const oldPassword = $("#oldPassword").val().trim();
      const newPassword = $("#newPassword").val().trim();
      const phoneNumber = $("#phoneNumber").val().trim();

      if (!firstName || !lastName || !email || !oldPassword || !newPassword || !phoneNumber) {
        //all the values are not added
        $("#statusMessage").text("Text fields cannot be empty").css("color", "red");
      } else {
        $.ajax({
          type: "POST",
          url: "change_user_details.php",
          data: {
            firstName: firstName,
            lastName: lastName,
            email: email,
            oldPassword: oldPassword,
            newPassword: newPassword,
            phoneNumber: phoneNumber
          },
          dataType: "json",
          success: function(response) {
            if (response.success) {

              $("#statusMessage").text("Details changed successfully. Please reload page to see the changes").css("color", "green");
              

            } else {

              $("#statusMessage").text(response.error).css("color", "red");
            }
          },

          error: function(response) {

            $("#statusMessage").text(response.error).css("color", "red");
          }
        });

      }


    });

  });

  // Example starter JavaScript for disabling form submissions if there are invalid fields
  (function() {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap  styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function(form) {
        form.addEventListener('submit', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
  })()
</script>