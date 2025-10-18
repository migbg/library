<?php
require 'connect.php';
session_start();
unset($_SESSION['info']);

// Check a given cookie token
if (isset($_COOKIE['user_cookie']) && $_COOKIE['user_cookie'] != "") {
    $sql = "SELECT * FROM users_tokens WHERE token=:token";
    $check_token = $conn->prepare($sql);
    $check_token->execute([ 'token' => $_COOKIE['user_cookie'] ]);
    $result_token = $check_token->fetch(PDO::FETCH_ASSOC);

    if ($result_token && $result_token['expires_at'] >= date('Y-m-d H:i:s', time())) {
        $sql = "SELECT * FROM users WHERE email=:email";
        $searchUser = $conn->prepare($sql);
        $searchUser->execute(['email' => $result_token['user_email']]);
        $result = $searchUser->fetch(PDO::FETCH_ASSOC);

        $_SESSION['loggedName'] = $result['name'];
        $_SESSION['loggedEmail'] = $result['email'];
        $_SESSION['loggedAvatar'] = $result['avatar'];
        $_SESSION['isLogged'] = true;

        header('Location: index.php');
    }
} else {
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

        if ($_POST['remember-me'] == "yes") {
            $token = bin2hex(random_bytes(32));
            $expires_at = strtotime('+30 days');
            setcookie('user_cookie', $token, $expires_at, "/", "", false, true);

            $sql = "INSERT INTO users_tokens (token, expires_at, user_email) VALUES (:token, :expires_at, :user_email)";
            $insert_token = $conn->prepare($sql);
            $insert_token->execute([ 
                'token' => $token,
                'expires_at' => date('Y-m-d H:i:s', $expires_at),
                'user_email' => $result['email']
            ]);
        }

        header('Location: index.php');
        exit;
    } else {
        $_SESSION['info'] .= "<div> Unable to log in. Please check your credentials. </div>";
        header('Location: login_form.php');
    }
}


?>