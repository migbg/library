<?php
require 'connect.php';
include 'functions.php';

session_start();
if (!isset($_SESSION['isLogged'])) {
    header('Location: login_form.php');
    exit;
}

//Check if the user owns the book
$result = user_owns_book($conn, $_GET['id'], $_SESSION['loggedEmail']);

// If not, redirect to index
if(!$result || $result['user_email'] != $_SESSION['loggedEmail']) {
    header('Location: index.php');
    exit;
} else {
    // The owner can delete it
    if(isset($_POST['action']) && $_POST['action'] == "yes"){
        // Remove image
        if ($result['cover'] != "default.png") {
            unlink('uploads/' . $result['cover']);
        }
        
        // Delete book
        delete_book($conn, $_GET['id']);
        $_SESSION['bookinfo'] = "<div> Book deleted </div>";
        header('Location: index.php');
        exit;
    } else if (isset($_POST['action']) && $_POST['action'] == "no"){
        header('Location: book.php?id=' . $_GET['id']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Book delete </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php' ?>
    <div class="container">
        <form method="POST">
            <div class="container-row" style="gap: 20px;">
                <div> Are you sure you want to delete "<b><?php echo htmlspecialchars($result['title'])?>"</b> by <?php echo htmlspecialchars($result['author'])?></div>
                <button class="delete" type="submit" name="action" value="yes"> YES </button>
                <button type="submit" name="action" value="no"> No </button>
            </div>
        </form>
    </div>
</body>
</html>