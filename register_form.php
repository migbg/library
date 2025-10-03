<?php 
session_start(); 
if (isset($_SESSION['isLogged'])) header('Location: books.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Register </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1> Register </h1>
        <form action="register.php" method="post">
            <div class="container-row">
                <label for="username"> Name </label>
                <input type="text" name="name" id="name" value="<?php if (isset($_SESSION['name'])) echo htmlspecialchars($_SESSION['name']); ?>">
            </div>
            <div class="container-row">
                <label for="email"> Email </label>
                <input type="email" name="email" id="email" value="<?php if (isset($_SESSION['email'])) echo htmlspecialchars($_SESSION['email']); ?>">
            </div>
            <div class="container-row">
                <label for="passwd"> Password </label>
                <input type="password" name="passwd" id="passwd" value="<?php if (isset($_SESSION['passwd'])) echo htmlspecialchars($_SESSION['passwd']); ?>">
            </div>
            <div class="container-row">
                <label for="re-passwd"> Repeat password </label>
                <input type="password" name="re-passwd" id="re-passwd" value="<?php if (isset($_SESSION['passwd'])) echo htmlspecialchars($_SESSION['passwd']); ?>">
            </div>
            <div class="container-row" style="align-items:flex-end;">
                <div> Already registered? Click <a class="login-register" href="login_form.php">here</a>.</div>
                <button type="submit"> Register </button> 
            </div>
        </form>
        <div>
            <?php 
                if (isset($_SESSION['info'])) {
                    echo "<div class=container-info><b><span style='color: rgb(194, 44, 44)'>NOTICE:</span></b>". $_SESSION['info'] . "</div>";
                    unset($_SESSION['info']);
                }
            ?>
        </div>
    </div>
</body>
</html>