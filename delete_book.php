<?php
    require 'connect.php';
    session_start();

    $sql = "SELECT * FROM books WHERE id=:id AND user_email=:user_email";
    $get_book = $conn->prepare($sql);
    $get_book->execute([
        'id' => $_GET['id'],
        'user_email' => $_SESSION['loggedEmail']
    ]);
    $result = $get_book->fetch(PDO::FETCH_ASSOC);

    if(!$result || $result['user_email'] != $_SESSION['loggedEmail']) header('Location: books_list.php');
    else {
        if(isset($_POST['action']) && $_POST['action'] == "yes"){
            $sql = "DELETE FROM books WHERE id={$_GET['id']}";
            $result = $conn->query($sql);
            header('Location: books_list.php');

        } else if (isset($_POST['action']) && $_POST['action'] == "no"){
            header('Location: book.php?id=' . $_GET['id']);
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
    <div class="container">
        <form method="POST">
            <div class="container-row">
                <div> Are you sure you want to delete "<b><?php echo htmlspecialchars($result['title'])?>"</b> by <?php echo htmlspecialchars($result['author'])?></div>
                <button class="delete" type="submit" name="action" value="yes"> YES </button>
                <button type="submit" name="action" value="no"> No </button>
            </div>
        </form>
    </div>
</body>
</html>