<?php 
session_start(); 
if (isset($_SESSION['isLogged'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Sign up </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <script>
        function passwdHelp(str) {
            if (str.length == 0) {
                document.getElementById("passwd_hint").innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("passwd_hint").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", 'passwd_help.php?q=' + encodeURIComponent(str), true);
            xmlhttp.send();
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1> Sign up </h1>
        <form action="register.php" method="post" enctype="multipart/form-data">
            <div class="container-row">
                <label for="name"><i class="bi bi-alphabet-uppercase form-icon"></i> Name* </label>
                <input type="text" name="name" id="name" value="<?php if (isset($_SESSION['name'])) echo htmlspecialchars($_SESSION['name']); ?>">
            </div>
            <div class="container-row">
                <label for="email"><i class="bi bi-envelope-at-fill form-icon"></i> Email* </label>
                <input type="email" name="email" id="email" value="<?php if (isset($_SESSION['email'])) echo htmlspecialchars($_SESSION['email']); ?>">
            </div>
            <div class="container-row">
                <label for="passwd"><i class="bi bi-braces-asterisk form-icon"></i> Password* </label>
                <input type="password" name="passwd" id="passwd" onkeyup="passwdHelp(this.value)" value="<?php if (isset($_SESSION['passwd'])) echo htmlspecialchars($_SESSION['passwd']); ?>">
            </div>
            <div class="container-row">
                <label for="re-passwd"> Repeat password </label>
                <input type="password" name="re-passwd" id="re-passwd" value="<?php if (isset($_SESSION['passwd'])) echo htmlspecialchars($_SESSION['passwd']); ?>">
            </div>
            <div class="passwd-hint" id="passwd_hint"></div>
            <div class="container-row">
                <label for="avatar"><i class="bi bi-person-bounding-box form-icon"></i> Avatar </label>
                <input type="file" name="avatar" id="avatar">
            </div>
            <div class="container-row" style="align-items:flex-end;">
                <div> Already have an account? <a class="login-register" href="login_form.php">Sign in</a>.</div>
                <button type="submit"><i class="bi bi-person-plus-fill icon"></i> Sign up </button> 
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
<?php unset($_SESSION['name'], $_SESSION['email'], $_SESSION['passwd']) ?>