<?php
    session_start();
    session_destroy();
    session_unset();
    unset($_SESSION['loginUsername']);
    unset($_SESSION['loginPassword']);
    unset($_SESSION['loginIP']);

    echo 'Logged out!';
    header('Refresh: 2; URL = /login.php');
?>
