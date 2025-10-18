<?php
    require 'connect.php';
    include 'functions.php';

    session_start();
    if (!isset($_SESSION['isLogged'])) {
        header('Location: login_form.php');
        exit;
    }

    // Get user data
    $result = select_user_data($conn, $_SESSION['loggedEmail']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Profile - <?php echo $_SESSION['loggedName'] ?></title>
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
    <?php include 'nav.php' ?>
    <div class="container">
        <div class="container-row" style="align-items: center; gap: 20px;">
            <a href='index.php'><button class='back'> Go back </button></a>
            <h1> Profile </h1>
        </div>
        <form action="profile_actions.php" enctype="multipart/form-data" method="POST">
            <div class="container-row">
                <label for="name"> Name </label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($result['name']) ?>">
            </div>
            <hr>
            <div class="container-row">
                <label for="current-passwd"> Current password </label>
                <input type="password" name="current-passwd" id="current-passwd">
            </div>
            <div class="container-row">
                <label for="passwd"> New password </label>
                <input type="password" name="passwd" id="passwd" onkeyup="passwdHelp(this.value)">
            </div>
            <div class="container-row">
                <label for="re-passwd"> Repeat new password </label>
                <input type="password" name="re-passwd" id="re-passwd">
            </div>
            <div class="passwd-hint" id="passwd_hint"></div>
            <hr>
            <div class="container-row">
                <label for="avatar"> Avatar </label>
                <input type="file" name="avatar" id="avatar">
            </div>
            <div class="container-row cover">
                <label class="container-row reset">
                    <span> Reset avatar </span>
                    <input type="checkbox" name="reset" value="yes">
                </label>
                <img class="profile-avatar" src="uploads/<?php echo htmlspecialchars($result['avatar']) ?>">
            </div>
            <button type="submit" style="width: 100%;"> Update profile </button>
        </form>
        <div class="center-container" style="margin-top: 20px">
            <a href="stats.php"><button class="stats" type="submit" style="width: 100%;"> Check my books stats </button></a>
        </form>
    </div>
</body>
</html>