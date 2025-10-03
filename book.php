<?php 
    require 'connect.php';
    session_start();
    if (!isset($_SESSION['isLogged'])) header('Location: login_form.php');

    $sql = "SELECT * FROM books WHERE id=:id";
    $get_book = $conn->prepare($sql);
    $get_book->execute([ 'id' => $_GET['id'] ]);
    $result = $get_book->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT name_categories FROM books_categories WHERE id_books=:id_books";
    $get_categories = $conn->prepare($sql);
    $get_categories->execute([
        'id_books' => $result['id']
    ]);
    $result_categories = $get_categories->fetchAll(PDO::FETCH_COLUMN);
    
    if(!$result) header('Location: books_list.php');
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
    <div class="book-card">
        <div class="book-cover">
            <img src="uploads/<?php echo htmlspecialchars($result['cover']); ?>" alt="Book Cover">
        </div>
        <div class="book-info">
            <h3><?php echo htmlspecialchars($result['title']); ?></h3>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($result['author']); ?></p>
            <p><strong>Year:</strong> <?php echo htmlspecialchars($result['year']); ?></p>
            <p><strong>Categories:</strong> <?php echo htmlspecialchars(implode(", ", $result_categories)); ?></p>
            <p><strong>URL:</strong> <a href="<?php echo htmlspecialchars($result['URL']); ?>"><?php echo htmlspecialchars($result['URL']); ?></a></p>
            <p class="description"><?php echo nl2br(htmlspecialchars($result['description'])); ?></p>
        </div>
        <?php
            if ($result['user_email'] == $_SESSION['loggedEmail']) {
                echo "<a href='book_form.php?id=" . htmlspecialchars($result['id']) . "&update=yes'><button> Edit </button></a>";
                echo "<a href='delete_book.php?id=" . htmlspecialchars($result['id']) . "'><button class=delete> Delete </button></a>"; 
            }  
        ?>
    </div>
</body>
</html>