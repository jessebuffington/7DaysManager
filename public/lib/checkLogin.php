<?php
//DO NOT ECHO ANYTHING ON THIS PAGE OTHER THAN RESPONSE
//'true' triggers login success
ob_start();
require($_SERVER["DOCUMENT_ROOT"] . "/includes/config.php");
require($_SERVER["DOCUMENT_ROOT"] . "/includes/functions.php");


// Define $myusername and $mypassword
$loginUsername=$_POST['loginUsername'];
$loginPassword=$_POST['loginPassword'];

// To protect MySQL injection (more detail about MySQL injection)
$loginUsername = stripslashes($loginUsername);
$loginPassword = stripslashes($loginPassword);
$loginUsername = mysql_real_escape_string($loginUsername);
$loginPassword = mysql_real_escape_string($loginPassword);
$sql="SELECT * FROM GNOC.gnoc_users WHERE email='$loginUsername' and password='$loginPassword'";
$result=mysql_query($sql) or die(mysql_error());

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row
if($count==1){
  // Register $myusername, $mypassword and redirect to file "login_success.php"
  $_SESSION['oginUsername'] = $loginUsername;
  header("location:/index.php");
} else {
  header("location:/login.php?authFail");
}

ob_end_flush();

?>
