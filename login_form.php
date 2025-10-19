<?php 
session_start();
if (isset($_SESSION['isLogged'])) {
    header('Location: index.php');
    exit;
}

// Check if cookie exists
if (isset($_COOKIE['user_cookie']) && $_COOKIE['user_cookie'] != "") {
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Sign in </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1> Sign in </h1>
        <form action="login.php" method="post">
            <div class="container-row">
                <label for="email"><i class="bi bi-envelope-at-fill form-icon"></i> Email: </label>
                <input type="email" name="email" id="email" autofocus>
            </div>
            <div class="container-row">
                <label for="passwd"><i class="bi bi-braces-asterisk form-icon"></i> Password: </label>
                <input type="password" name="passwd" id="passwd">
            </div>
            <div class="container-row remember">
                <label class="remember-label" for="remember-me"> Remember me </label>
                <input class="remember-me" type="checkbox" name="remember-me" id="remember-me" value="yes">
            </div>
            <div class="container-row" style="align-items:flex-end;">
                <div>Don't have an account? <a class="login-register" href="register_form.php">Sign up</a>.</div>
                <button type="submit"><i class="bi bi-box-arrow-in-right icon"></i> Sign in </button>
            </div>
        </form>
        <div>
            <?php 
                if (isset($_SESSION['info'])) {
                    echo "<dialog class='container-info' open>";
                    echo "<b><span style='color: rgb(194, 44, 44)'>NOTICE:</span></b>";
                    echo "<div>" . $_SESSION['info'] . "</div>";
                    echo "<form method='dialog' class='dialog-form'>";
                    echo "<button class='back'>x</button>";
                    echo "</form></dialog>";
                    unset($_SESSION['info']);
                }
            ?>
        </div>
    </div>
</body>
</html>