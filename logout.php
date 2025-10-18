<?php
require 'connect.php';
session_start();
session_unset();

if (isset($_COOKIE['user_cookie']) && $_COOKIE['user_cookie'] != "") {
    $sql = "DELETE FROM users_tokens WHERE token=:token";
    $delete_token = $conn->prepare($sql);
    $delete_token->execute([ 'token' => $_COOKIE['user_cookie']] );

    setcookie("user_cookie", "", (time() - 3600), "/", "");
}

session_destroy();
header('Location: login_form.php');
?>