<?php
    require 'connect.php';
    session_start();
    if (!isset($_SESSION['isLogged'])) {
        header('Location: login_form.php');
        exit;
    }

    // Get user data
    $sql = "SELECT * FROM users WHERE email=:email";
    $get_user = $conn->prepare($sql);
    $get_user->execute([ 'email' => $_SESSION['loggedEmail'] ]);
    $result = $get_user->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Update
        $sql = "UPDATE users SET name=:name, avatar=:avatar WHERE email=:email";
        $update_user = $conn->prepare($sql);

        // Name filter
        if (trim($_POST['name']) == ""){
            $_SESSION['info'] .= "<div> Name can't be blank or just spaces </div>";
        } else {
            $name = $_SESSION['name'] = $_POST['name'];
        }

        // Reset avatar?
        $reset = $_POST['reset'] == "yes" ? $_POST['reset'] : NULL;

        // Upload avatar
        if ($_FILES['avatar']['name'] != ""){
            $extension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            $file_name = uniqid('avatar_', true) . '.' . $extension;
            $file_path = 'uploads/' . $file_name;

            $upload = $reset ? false : true;

            if ($extension != "png" && $extension != "jpg" && $extension != "jpeg") {
                $_SESSION['info'] .= "<div> Only PNG, JPG and JPEG allowed </div>";
                $upload = false;
            }

            $check = getimagesize($_FILES['avatar']['tmp_name']);
            if ($check == false) {
                $_SESSION['info'] .= "<div> File is not and image </div>";
                $upload = false;
            }
        }

        if ($upload){
            move_uploaded_file($_FILES['avatar']['tmp_name'], $file_path);
        }

        // Finally execute update
        $update_user->execute([
            'name' => $name ? $name : $result['name'],
            'avatar' => $reset ? 'default-avatar.png' : ($file_name ? $file_name : $result['avatar']),
            'email' => $result['email']
        ]);

        // Remove image if reset
        if ($reset && $_SESSION['loggedAvatar'] != 'default-avatar.png') {
            unlink('uploads/' . $_SESSION['loggedAvatar']);
        }

        $_SESSION['loggedName'] = $name ? $name : $result['name'];
        $_SESSION['loggedAvatar'] = $reset ? 'default-avatar.png' : ($file_name ? $file_name : $result['avatar']);
        header('Location: profile.php');
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Profile - <?php echo $_SESSION['loggedName'] ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php' ?>
    <div class="container">
        <div class="container-row" style="align-items: center; gap: 20px;">
            <a href='index.php'><button class='back'> Go back </button></a>
            <h1> Profile </h1>
        </div>
        <form enctype="multipart/form-data" method="POST">
            <div class="container-row">
                <label for="name"> Name </label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($result['name']) ?>">
            </div>
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