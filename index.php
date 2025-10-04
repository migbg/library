<?php
require 'connect.php';
session_start();
if (!isset($_SESSION['isLogged'])) {
    header('Location: login_form.php');
    exit;
}

$sql = "SELECT id, title, author, cover FROM books ORDER BY title";
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
                echo "<a class=container-book href='book.php?id=" . htmlspecialchars($book['id']) . "' style='background-image: url(uploads/" . htmlspecialchars($book['cover']) . "); background-size: cover;'>";
                echo "<div style='font-size: 1.5rem'><b>" . htmlspecialchars($book['title']) . "</b></div>";
                echo "<div>" . htmlspecialchars($book['author']) . "</div>";
                echo "</a>";
            }
        ?>
    </div>
</body>
</html>
