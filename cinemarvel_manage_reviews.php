<!DOCTYPE html>
<?php
include "header.php";
?>

<html lang="en">

<head>
  <title>Manage your reviews</title>
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
  <h3 class="m-3">All reviews</h3>
  <div class="container-fluid display-reviews container-fluid mt-4">
    <?php
    include("database.php");
    $user_id = $_SESSION["user_id"];
    $stmt = $conn->prepare("SELECT R.review_id AS review_id, R.stars AS stars, R.date AS date, R.movie_id AS movie_id, R.comment AS comment, M.movie_title AS movie_title FROM reviews AS R INNER JOIN movies AS M ON R.movie_id = M.movie_id  WHERE R.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();



    // 
    ?>
    <?php if ($result->num_rows > 0): ?>

      <?php foreach ($result as $row): ?>
        <div class="card container-fluid mb-5">
          <div class="card-header">
            Reviewed on <?= $row['date'] ?> <!-- Display the review date -->
          </div>
          <div class="d-flex flex-row align-items-center justify-content-between">
            <div class="card-body">
              <h5 class="card-title"><?= $row['movie_title'] ?></h5> <!-- Display the movie title -->
              <div class="mt-3 mb-1" data-coreui-disabled="true" data-coreui-toggle="rating"
                data-coreui-value="<?= $row['stars'] ?>"></div> <!-- Display the star rating -->
              <p class="card-text"><?= $row['comment'] ?></p> <!-- Display the review comment -->
            </div>
            <div class="d-flex flex-column align-items-center">
              <div>
                <a href="cinemarvel_edit_review.php?review_id=<?= $row['review_id'] ?>"> <!-- Pass review_id dynamically -->
                  <i style="font-size: 20px; color:blue" class="fa-solid fa-pencil editReviewBtn p-2"></i>
                </a>
              </div>
              <div>
                <?php $review_id = $row["review_id"] ?>
                <i onclick="deleteReview(<?= $review_id ?>)" style="font-size: 20px; color:red" class="fa-solid fa-trash deleteReview p-2"></i>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p> You do not have any reviews yet. Review a movie to become a Marlelite. </p>
    <?php endif ?>
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
  };


  //script to delete the review
  function deleteReview(review_id){
    if(confirm("Are you sure you want to delete this review? This cannot be undone later")){
      $.ajax({
        type: "post",
        url: "delete_review.php",
        data: {
          review_id
        },
        dataType: "json",
        success: function (response) {
          if(response.success){
            alert("Review deleted successfully");
            window.location.reload();
          }

          else{
            alert(response.error);
          }
          
        },
        error:function(response){
          alert("something went wrong");
        }
      });
    }
  }
</script>