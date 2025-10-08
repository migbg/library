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
    <title> Register </title>
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
                <input type="password" name="passwd" id="passwd" onkeyup="passwdHelp(this.value)" value="<?php if (isset($_SESSION['passwd'])) echo htmlspecialchars($_SESSION['passwd']); ?>">
            </div>
            <div class="container-row">
                <label for="re-passwd"> Repeat password </label>
                <input type="password" name="re-passwd" id="re-passwd" value="<?php if (isset($_SESSION['passwd'])) echo htmlspecialchars($_SESSION['passwd']); ?>">
            </div>
            <div class="passwd-hint" id="passwd_hint"></div>
            <div class="container-row" style="align-items:flex-end;">
                <div> Already have an account? <a class="login-register" href="login_form.php">Sign in</a>.</div>
                <button type="submit"> Sign up </button> 
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
<?php unset($_SESSION['name'], $_SESSION['email'], $_SESSION['passwd']) ?>