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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1> Sign in </h1>
        <form action="login.php" method="post">
            <div class="container-row">
                <label for="email"> Email: </label>
                <input type="email" name="email" id="email" autofocus>
            </div>
            <div class="container-row">
                <label for="passwd"> Password: </label>
                <input type="password" name="passwd" id="passwd">
            </div>
            <div class="container-row remember">
                <label class="remember-label" for="remember-me"> Remember me </label>
                <input class="remember-me" type="checkbox" name="remember-me" id="remember-me" value="yes">
            </div>
            <div class="container-row" style="align-items:flex-end;">
                <div>Don't have an account? <a class="login-register" href="register_form.php">Sign up</a>.</div>
                <button type="submit"> Sign in </button>
            </div>
        </form>
        <div>
            <?php 
                if (isset($_SESSION['info'])) {
                    echo "<div class=container-info><b><span style='color: rgb(194, 44, 44)'>NOTICE:</span></b>" . $_SESSION['info'] . "</div>";
                    unset($_SESSION['info']);
                }
            ?>
        </div>
    </div>
</body>
</html>