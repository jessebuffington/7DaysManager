<?php
  include($_SERVER["DOCUMENT_ROOT"] . "/includes/dbConfig.php");

  // Get values passe from form in login.php file
  $username = $POST['username'];
  $password = $PORT['password'];

  // to prevent mysql injection
  $username = stripcslashes($username);
  $password = stripcslashes($password);
  $username = mysql_real_escape_string($username);
  $password = mysql_real_escape_string($password);

  // connect to the server and select database
  $login = mysql_query("SELECT * FROM site_users where username = '$username' and password = '$password'");
  if (!$login) {
    die('Failed to query database - ' . mysql_error());
  }
  $row = mysql_fetch_array($login);
  if ($row['username'] == $username && $row['password'] == $password ) {
    echo 'Login success!!! Welcome ' . $row['username'];
  } else {
    echo 'Failed to login!';
  }
 ?>
