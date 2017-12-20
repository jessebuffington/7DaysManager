<?php
  session_start();
  if (!isset($_SESSION['loginUsername'])) {
    return header('location:/login.php');
  }
  
//  if(!($_SESSION['loginUsername'])) {
//    header("Location: /login.php");
//    die("Redirecting to login.php");
//  }
?>
