<?php 
    require 'connect.php';
    session_start();
    if (!isset($_SESSION['isLogged'])) {
        header('Location: login_form.php');
        exit;
    }

    // Get book data
    $sql = "SELECT * FROM books WHERE id=:id";
    $get_book = $conn->prepare($sql);
    $get_book->execute([ 'id' => $_GET['id'] ]);
    $result = $get_book->fetch(PDO::FETCH_ASSOC);

    // Get book categories
    $sql = "SELECT name FROM categories INNER JOIN books_categories ON books_categories.id_categories=categories.id WHERE books_categories.id_books=:id_books ORDER BY name";
    $get_categories = $conn->prepare($sql);
    $get_categories->execute([
        'id_books' => $result['id']
    ]);
    $result_categories = $get_categories->fetchAll(PDO::FETCH_COLUMN);
    
    if(!$result) {
        header('Location: index.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Book - <?php echo htmlspecialchars($result['title']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php' ?>
    <div class="info-card">
        <div class="card-column">
            <a href='index.php'><button class='back'> Go back </button></a>
            <img class="book-img" src="uploads/<?php echo htmlspecialchars($result['cover']); ?>" alt="Book Cover">
        <?php
            if ($result['user_email'] == $_SESSION['loggedEmail']) {
                echo "<a href='book_form.php?id=" . htmlspecialchars($result['id']) . "&update=yes'><button> Edit </button></a>";
                echo "<a href='delete_book.php?id=" . htmlspecialchars($result['id']) . "'><button class=delete> Delete </button></a>"; 
            }  
        ?>
        </div>
        <div class="card-row">
            <h3><?php echo htmlspecialchars($result['title']); ?></h3>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($result['author']); ?></p>
            <p><strong>Year:</strong> <?php echo $result['year'] != NULL ? htmlspecialchars($result['year']) : "N/A"; ?></p>
            <p><strong>Categories:</strong> <?php echo $result_categories != NULL ? htmlspecialchars(implode(", ", $result_categories)) : "N/A"; ?></p>
            <p><strong>URL:</strong> <a target="_blank" href="<?php echo htmlspecialchars($result['URL']); ?>"><?php echo $result['URL'] != NULL ? htmlspecialchars($result['URL']) : "N/A"; ?></a></p>
            <p class="description"><?php echo $result['description'] != NULL ? nl2br(htmlspecialchars($result['description'])) : "No description."; ?></p>
        </div>
    </div>
</body>
</html>