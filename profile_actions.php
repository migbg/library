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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Name filter
        if (trim($_POST['name']) == ""){
            $_SESSION['info'] .= "<div> Name can't be blank or just spaces </div>";
        } else {
            $name = $_SESSION['name'] = $_POST['name'];
        }

        // Current password
        $current_passwd_check = password_verify($_POST['current-passwd'], $result['password']);
        if ($current_passwd_check) {
            // Check if passwords match and hash it
            if ($_POST['passwd'] !== $_POST['re-passwd']) {
                $_SESSION['info'] .= "<div> Passwords must match </div>";
            } else if (trim($_POST['passwd']) === "") {
                $_SESSION['info'] .= "<div> Password can't be empty </div>";
            } else {
                $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+\[\]{}|;:\'",.<>?\/])[A-Za-z\d!@#$%^&*()\-_=+\[\]{}|;:\'",.<>?\/]{10,}$/';
                if (preg_match($regex, $_POST['passwd'])) {
                    $passwd = password_hash($_POST['passwd'], PASSWORD_ARGON2ID, [
                        'memory_cost' => 131072,
                        'time_cost' => 4,
                        'threads' => 2
                    ]);
                    $_SESSION['passwd'] = $_POST['passwd'];
                } else {
                    $_SESSION['info'] .= "<div> Password doesn't meet requirements </div>";
                }
            }
        } else if (trim($_POST['current-passwd']) != "" && trim($_POST['passwd']) === ""){
            $_SESSION['info'] .= "<div> You must specify a new password </div>";
        } else if (trim($_POST['current-passwd']) === "" && trim($_POST['passwd'] != "")) {
            $_SESSION['info'] .= "<div> Current password must be set to change your password </div>";
        } elseif (trim($_POST['current-passwd']) === "" && trim($_POST['passwd'] === "")){
            // Nothing
        } else {
            $passwd = NULL;
            $_SESSION['info'] .= "<div> Current password doesn't match the new one </div>";
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

        // Update
        update_user_data($conn, $result['email'], $name, $result['name'], $reset, $file_name, $result['avatar'], $passwd, $result['password']);

        // Remove image if reset
        if ($reset && $_SESSION['loggedAvatar'] != 'default-avatar.png') {
            unlink('uploads/' . $_SESSION['loggedAvatar']);
        }

        $_SESSION['loggedName'] = $name ? $name : $result['name'];
        $_SESSION['loggedAvatar'] = $reset ? 'default-avatar.png' : ($file_name ? $file_name : $result['avatar']);
        header('Location: profile.php');
    }
?>