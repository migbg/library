<?php 
session_start();
if (isset($_SESSION['isLogged'])) header('Location: books.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2> Log in </h2>
        <form action="login.php" method="post">
            <div class="container-row">
                <label for="email"> Email: </label>
                <input type="email" name="email" id="email">
            </div>
            <div class="container-row">
                <label for="passwd"> Password: </label>
                <input type="password" name="passwd" id="passwd">
            </div>
            <div class="container-row" style="align-items:flex-end;">
                <div>If you are not registered yet, click <a href="register_form.php">here</a>.</div>
                <button type="submit"> Log in </button>
            </div>
        </form>
        <div>
            <?php 
                if (isset($_SESSION['info'])) {
                    echo "<div class=container-info>" . $_SESSION['info'] . "</div>";
                    unset($_SESSION['info']);
                }
            ?>
        </div>
    </div>
</body>
</html>