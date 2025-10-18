<?php
require 'connect.php';
include 'functions.php';
session_start();

/* Delete cookie from DB if belongs to to the logged user and delete it from browser */
if (isset($_COOKIE['user_cookie']) && $_COOKIE['user_cookie'] != "") {
    delete_token($conn, $_COOKIE['user_cookie'], $_SESSION['loggedEmail']);
    setcookie("user_cookie", "", (time() - 3600), "/", "");
}

session_unset();
session_destroy();
header('Location: login_form.php');
?>