<?php
require 'connect.php';
session_start();
if (isset($_SESSION['isLogged'])) {
    header('Location: index.php');
    exit;
}

unset($_SESSION['info'], $_SESSION['name'], $_SESSION['email'], $_SESSION['passwd']);

// Filtro para el nombre
if (trim($_POST['name']) == ""){
    $_SESSION['info'] .= "<div> Name can't be blank or just spaces </div>";
} else {
    $name = $_SESSION['name'] = $_POST['name'];
}

// Filtro para el email
if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $email = $_SESSION['email'] = $_POST['email'];
} else {
    $_SESSION['info'] .= "<div> Given email is not valid </div>";
}

// Comprobar que coindican las contraseñas y aplicar hash
if ($_POST['passwd'] !== $_POST['re-passwd']) {
    $_SESSION['info'] .= "<div> Passwords must match </div>";
} else if ($_POST['passwd'] === "") {
    $_SESSION['info'] .= "<div> Password can't be empty or just spaces </div>";
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

// Upload avatar
if ($_FILES['avatar']['name'] != ""){
    $extension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
    $file_name = uniqid('avatar_', true) . '.' . $extension;
    $file_path = 'uploads/' . $file_name;

    $upload = true;

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

// Comprueba que el usuario no exista y lo crea
$sqlSearch = "SELECT email from users WHERE email=:email";
$sqlSearch = $conn->prepare($sqlSearch);
$sqlSearch->execute(['email' => $email]);
$result = $sqlSearch->fetch(PDO::FETCH_ASSOC);
if ($result) $_SESSION['info'] .="<div> Email already in use </div>";

if ($email && $passwd && $name) {
    try {
        $sql = "INSERT INTO users VALUES (:email, :passwd, :name, :avatar)";
        $insertUser = $conn->prepare($sql);
        $insertUser->execute([
            'name' => $name,
            'email' => $email,
            'passwd' => $passwd,
            'avatar' => $upload ? $file_name : "default-avatar.png"
        ]);
        $_SESSION['info'] .="<div> User created </div>";
        header('Location: login_form.php');
        exit;

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

header('Location: register_form.php');
?>