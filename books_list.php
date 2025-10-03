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
    <title> List of books </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php' ?>
    <div class="books-list">
        <?php 
            foreach ($get_books as $book) {
                echo "<a class=container-book href='book.php?id=" . $book['id'] . "'>";
                echo "<div><b>" . $book['title'] . "</b></div>";
                echo "<div>" . $book['author'] . "</div>";
                echo "<img src='uploads/" . $book['cover'] . "' width='100px' height='150px'>";
                echo "</a>";
            }
        ?>
    </div>
</body>
</html>
