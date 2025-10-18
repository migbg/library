<?php
require 'connect.php';
include 'functions.php';

session_start();
unset($_SESSION['info']);

// Check a given cookie token
if (isset($_COOKIE['user_cookie']) && $_COOKIE['user_cookie'] != "") {

    /* Check user token */
    $result_token = check_token($conn, $_COOKIE['user_cookie']);

    if ($result_token && $result_token['expires_at'] >= date('Y-m-d H:i:s')) {

        /* Get user data */
        $result = select_user_data($conn, $result_token['user_email']);

        $_SESSION['loggedName'] = $result['name'];
        $_SESSION['loggedEmail'] = $result['email'];
        $_SESSION['loggedAvatar'] = $result['avatar'];
        $_SESSION['isLogged'] = true;

        header('Location: index.php');
    }
} else {

    /* Get user data */
    $result = select_user_data($conn, $_POST['email']);

    // Comprueba que el email exista y si contraseña coincida
    if ($result && password_verify($_POST['passwd'], $result['password'])) {

        $_SESSION['loggedName'] = $result['name'];
        $_SESSION['loggedEmail'] = $result['email'];
        $_SESSION['loggedAvatar'] = $result['avatar'];
        $_SESSION['isLogged'] = true;

        if ($_POST['remember-me'] == "yes") {
            $token = bin2hex(random_bytes(32));
            $expires_at = strtotime('+15 days');
            setcookie('user_cookie', $token, $expires_at, "/", "", false, true);

            /* Insert token to DB */
            create_token($conn, $token, $expires_at, $result['email']);
        }

        header('Location: index.php');
        exit;
    } else {
        $_SESSION['info'] .= "<div> Unable to log in. Please check your credentials. </div>";
        header('Location: login_form.php');
    }
}

?>