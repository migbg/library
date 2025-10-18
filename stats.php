<?php
    require 'connect.php';
    session_start();
    if (!isset($_SESSION['isLogged'])) {
        header('Location: login_form.php');
        exit;
    }

    $sql = "SELECT books.*, (AVG(users_votes.vote)*100) as mean FROM books LEFT JOIN users_votes ON books.id = users_votes.id_books WHERE books.user_email=:user_email GROUP BY id ORDER BY visits DESC";
    $stats = $conn->prepare($sql);
    $stats->execute([ 'user_email' => $_SESSION['loggedEmail'] ]);
    $result = $stats->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> My books stats </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php' ?>
    <div class="container">
        <div class="container-row" style="align-items: center; gap: 20px;">
            <a href='profile.php'><button class='back'> Go back </button></a>
            <h1> My books stats </h1>
        </div>
        <div class="center-container stats-list">
            <table class="stats-table">
                <tr>
                    <th> Cover </th>
                    <th> Title </th>
                    <th> Author </th>
                    <th> Visits </th>
                    <th> Votes </th>
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