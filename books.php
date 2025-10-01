<?php
require 'connect.php';
session_start();
if (!isset($_SESSION['isLogged'])) header('Location: login_form.php');

$sql = "SELECT id, title, author, cover FROM books";
$get_books = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Restringido </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-row">
        <h1> List of books </h1>
        <a href="logout.php"><button type="button"> Log out </button></a>
    </div>
    <a href="book_form.php"><button type="button">  New book </button></a>
    <hr>
        <?php 
            foreach ($get_books as $book) {
                echo "<a href='book.php?id=" . $book['id'] . "'>";
                echo "<div class=container-book>";
                echo "<div><b>" . $book['title'] . "</b></div>";
                echo "<div>" . $book['author'] . "</div>";
                echo "<img src='uploads/" . $book['cover'] . "' width='100px' height='150px'>";
                echo "</div>";
                echo "</a>";
            }
        ?>

</body>
</html>
