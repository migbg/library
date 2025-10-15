<?php
require 'connect.php';
session_start();
unset($_SESSION['info']);

$sql = "SELECT * FROM users WHERE email=:email";
$searchUser = $conn->prepare($sql);
$searchUser->execute(['email' => $_POST['email']]);

$result = $searchUser->fetch(PDO::FETCH_ASSOC);

// Comprueba que el email exista y si contraseña coincida
if ($result && password_verify($_POST['passwd'], $result['password'])) {

    $_SESSION['loggedName'] = $result['name'];
    $_SESSION['loggedEmail'] = $result['email'];
    $_SESSION['loggedAvatar'] = $result['avatar'];
    $_SESSION['isLogged'] = true;

    header('Location: index.php');
    exit;
} else {
    $_SESSION['info'] .= "<div> Unable to log in. Please check your credentials. </div>";
    header('Location: login_form.php');
}
?>