<?php 
session_start();
session_unset();
session_destroy();
session_abort();
session_start();

header("Location: cinemarvel_login.php");
?>