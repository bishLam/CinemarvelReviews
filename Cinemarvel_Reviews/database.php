<?php
//connection of database will be done here

$db_server = "localhost";
$db_user = "root";
$db_password = "";
$db_name= "CinemarvelReviews";

$conn = mysqli_connect($db_server, $db_user, $db_password, $db_name);
// echo "connection successful"
?>