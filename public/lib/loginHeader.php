<?php
  //PUT THIS HEADER ON TOP OF EACH UNIQUE PAGE
  session_start();
  if (!isset($_SESSION['loginUsername'])) {
    return header('location:/login.php');
  } else {
    echo $_SESSION['loginUsername'];
  }

//  if(!($_SESSION['loginUsername'])) {
//    header("Location: /login.php");
//    die("Redirecting to login.php");
//  }
?>
