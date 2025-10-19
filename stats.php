<?php
    require 'connect.php';
    include 'functions.php';
    session_start();
    if (!isset($_SESSION['isLogged'])) {
        header('Location: login_form.php');
        exit;
    }
    
    /* Books data and votes mean from owned books */
    $result = my_books_data_and_votes_mean($conn, $_SESSION['loggedEmail']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> My books stats </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php' ?>
    <div class="container">
        <div class="container-row" style="align-items: center; gap: 20px;">
            <a href='profile.php'><button class='back'><i class="bi bi-arrow-left-circle icon"></i> Go back </button></a>
            <h1> My books stats </h1>
        </div>
        <div class="center-container stats-list">
            <table class="stats-table">
                <tr>
                    <th><i class="bi bi-file-image form-icon"></i> Cover </th>
                    <th><i class="bi bi-alphabet-uppercase form-icon"></i> Title </th>
                    <th><i class="bi bi-file-person-fill form-icon"></i> Author </th>
                    <th><i class="bi bi-activity form-icon"></i> Visits </th>
                    <th><i class="bi bi-emoji-laughing form-icon"></i> Votes </th>
                </tr>
                    <?php 
                        if ($result == []) {
                            echo "<tr>";
                                echo "<td colspan='5'> No results to show </td>";
                            echo "</tr>";
                        } else {
                            foreach ($result as $book) {
                                echo "<tr>";
                                    echo "<td><a href='book.php?id=" . $book['id'] . "'><img src='uploads/" . htmlspecialchars($book['cover']) . "' alt='Book cover'></a></td>";
                                    echo "<td><a href='book.php?id=" . $book['id'] . "'>" . htmlspecialchars($book['title']) . "</a></td>";
                                    echo "<td>" . htmlspecialchars($book['author']) . "</td>";
                                    echo "<td>" . htmlspecialchars($book['visits']) . "</td>";
                                    echo "<td>" . ($book['mean'] ? htmlspecialchars((int)$book['mean']) . "%" : '----') . "</td>";
                                echo "</tr>";
                            }
                        }
                    ?>
            </table>           
        </div>
    </div>
</body>
</html>