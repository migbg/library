<?php
require 'connect.php';
session_start();

if (!isset($_SESSION['isLogged'])) header('Location: login_form.php');
else {
    // Categories
    $sql = "SELECT name FROM categories";
    $get_categories = $conn->prepare($sql);
    $get_categories->execute();
    $result = $get_categories->fetchAll(PDO::FETCH_ASSOC);
}
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
    <div class="container">
    <h1> Register a new book </h1>
        <form action="insert_book.php" method="post" enctype="multipart/form-data">
            <div class="container-row">
                <label for="title"> Title* </label>
                <input type="text" name="title" id="title" value="" required>
            </div>
            <div class="container-row">
                <label for="description"> Description </label>
                <textarea name="description" id="description" value=""></textarea>
            </div>
            <div class="container-row">
                <label for="author"> Author* </label>
                <input type="text" name="author" id="author">
            </div>
            <div class="container-row">
                <label for="url"> URL </label>
                <input type="url" name="url" id="url">
            </div>
            <div class="container-row">
                <label for="year"> Year </label>
                <input type="number" name="year" id="year" min=1>
            </div>
            <div class="container-row">
                <label for="category"> Category </label>
                <div>
                    <?php
                        foreach ($result as $category) {
                            echo "<div class='container-row'>";
                            echo "<label for='cat-{$category['name']}'>{$category['name']}</label>";
                            echo "<input type='checkbox' id='cat-{$category['name']}' name='categories[]' value='{$category['name']}'>";
                            echo "</div>";
                        }
                    ?>
                </div>
            </div>
            <div class="container-row">
                <label for="userfile"> Cover </label>
                <input type="file" name="userfile" id="userfile">
            </div>
            <button type="submit" style="width: 100%;"> Register </button>
        </form>
        <?php 
            if (isset($_SESSION['bookinfo'])){
                echo "<div class=container-info>". $_SESSION['bookinfo'] . "</div>";
                unset($_SESSION['bookinfo']);
            }
        ?>
    </div>
</body>
</html>
