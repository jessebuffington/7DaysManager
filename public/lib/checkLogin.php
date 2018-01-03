<?php
//DO NOT ECHO ANYTHING ON THIS PAGE OTHER THAN RESPONSE
//'true' triggers login success
ob_start();
session_start();
require($_SERVER["DOCUMENT_ROOT"] . "/includes/siteConfig.php");
require($_SERVER["DOCUMENT_ROOT"] . "/includes/functions.php");


// Define $loginUsername, $loginPassword and $loginIP
$loginUsername=$_POST['loginUsername'];
$loginPassword=$_POST['loginPassword'];
$loginIP = $_SERVER['REMOTE_ADDR'];

// To protect MySQL injection (more detail about MySQL injection)
$loginUsername = stripslashes($loginUsername);
$loginPassword = stripslashes($loginPassword);
$loginUsername = mysql_real_escape_string($loginUsername);
$loginPassword = mysql_real_escape_string($loginPassword);
$sql="SELECT * FROM site_users WHERE email='$loginUsername' and password='$loginPassword'";
$result=mysql_query($sql)
  or die(mysql_error());

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row
if($count==1){
  // Register $loginUsername, $loginIP and redirect to file "/index.php"
  $_SESSION['loginUsername'] = $loginUsername;
  $_SESSION['loginIP'] = $loginIP;
  $sql_login = "UPDATE site_users set lastLogin = NOW(), timeout = NOW() where email = '$loginUsername'";
  $sql_attempts = "INSERT INTO site_loginAttempts (username, ip, lastLogin) VALUES ('$loginUsername', '$loginIP', NOW()) ON DUPLICATE KEY UPDATE ip = '$loginIP', lastLogin = NOW()";
  if (!mysql_query($sql_login)) {
    die('Error: ' . mysql_error());
  }
  if (!mysql_query($sql_attempts)) {
    die('Error: ' . mysql_error());
  }
  header("location:/index.php");
} else {
  $loginIP = $_SERVER['REMOTE_ADDR'];
  $sql_authfail = "INSERT INTO site_loginAttempts (username, ip, failedAttempts, lastFailedAttempt) VALUES ('$loginUsername', '$loginIP', failedAttempts + 1, NOW()) ON DUPLICATE KEY UPDATE ip = '$loginIP', failedAttempts = failedAttempts + 1, lastFailedAttempt = NOW()";
  if (!mysql_query($sql_authfail)) {
    die('Error: ' . mysql_error());
  }
  unset($_POST);
  header("location:/login.php?authFail");
}

ob_end_flush();
?>
