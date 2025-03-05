<!DOCTYPE html>
<?php
include "header.php";
?>
<html lang="en">

<head>
  <title>Edit Review</title>
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
  <!-- this is for the star rating from core ui -->
  <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@5.5.0/dist/css/coreui.min.css" rel="stylesheet"
    integrity="sha384-r/2d1aBwRhQSqjQh/21GvBPd8Oq6JOk0rWn05ngWyhiPOL79wFjw7vcbn3HwYGC/" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@5.5.0/dist/js/coreui.bundle.min.js"
    integrity="sha384-fB1r9DlcQtqFJcV4iBZiGPf1lH3BrXFpqFFYC0QTRc29fB9HQhpuEhjQwM9j96yG" crossorigin="anonymous">
  </script>
  <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
</head>

<body>
  <?php
  include("database.php");
  $user_id = $_SESSION["user_id"];
  $review_id = $_GET["review_id"];
  $stmt = $conn->prepare("SELECT R.stars AS stars, R.date AS date, R.movie_id AS movie_id, R.comment AS comment, M.movie_title AS movie_title FROM reviews AS R INNER JOIN movies AS M ON R.movie_id = M.movie_id  WHERE R.review_id = ?");
  $stmt->bind_param("i", $review_id);
  $stmt->execute();
  $result = $stmt->get_result();

  foreach ($result as $row) {
    $review_stars = $row['stars'];
    $review_comment = $row['comment'];
    $review_date = $row['date'];
    $movie_id = $row['movie_id'];
    $movie_title = $row['movie_title'];
  }
  ?>

  <div class="card bg-body-secondary border-dark p-3 edit_review_div mt-1">
    <h5>New review details for <?= $movie_title ?></h5>
    <div class="d-flex align-items-center mb-1">
      <div id="myRatingCustomFeedbackStart" class="text-body-secondary me-3"><?= $review_stars ?> / 5</div>
      <div id="myRatingCustomFeedback"></div>
      <div id="myRatingCustomFeedbackEnd" class="badge text-bg-dark ms-3">select</div>
    </div>
    <form method="get">
      <input class=" mt-3 rounded-3" style="min-width: 300px;" type="text" name="userReviewText" id="userReviewText"
        placeholder="<?= $review_comment ?>">
      <input class="rounded w-20" type="submit" value="Save Changes" id="saveChanges">
    </form>
  </div>

</body>

</html>










<script>
  //  js for core ui rating bar

  var rating_score = 0;
  const myRatingCustomFeedback = document.getElementById('myRatingCustomFeedback')
  const myRatingCustomFeedbackStart = document.getElementById('myRatingCustomFeedbackStart')
  const myRatingCustomFeedbackEnd = document.getElementById('myRatingCustomFeedbackEnd')
  if (myRatingCustomFeedback) {
    let currentValue = 0
    const labels = {
      1: 'Very bad',
      2: 'Bad',
      3: 'Meh',
      4: 'Good',
      5: 'Very good'
    }
    const optionsCustomFeedback = {
      value: currentValue
    }

    new coreui.Rating(myRatingCustomFeedback, optionsCustomFeedback)

    myRatingCustomFeedback.addEventListener('change.coreui.rating', event => {
      currentValue = event.value
      myRatingCustomFeedbackStart.innerHTML = `${event.value} / 5`
      myRatingCustomFeedbackEnd.innerHTML = labels[event.value]
      rating_score = event.value;
    })

    myRatingCustomFeedback.addEventListener('hover.coreui.rating', event => {
      myRatingCustomFeedbackEnd.innerHTML = event.value ? labels[event.value] : labels[currentValue]
    })
  }


  $("#saveChanges").click(function(e) {
    e.preventDefault();
    var userReviewText = $("#userReviewText").val() + "";
    var review_id = <?= $_GET["review_id"] ?>

    if (userReviewText.trim() != "" && rating_score != 0) {
      $.ajax({
        type: "post",
        url: "edit_review.php",
        data: {
          userReviewText,
          rating_score,
          review_id

        },
        dataType: "json",
        success: function(response) {
          if (response.success) {
            window.location.href = "cinemarvel_manage_reviews.php";
            alert("Review successfully updated");

          } else {
            alert(response.error);
          }
        },
        error: function(response) {
          alert(response.error);
        }
      });
    } else {
      alert("Comment and star cannot be empty");
    }

  });
</script>