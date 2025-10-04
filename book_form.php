<?php
require 'connect.php';
session_start();

if (!isset($_SESSION['isLogged'])) {
    header('Location: login_form.php');
    exit;
} else {
    //Update
    $update = isset($_GET['update']) ? $_GET['update'] : NULL;

    if ($update == "yes"){
        // Get book if user owns it
        $sql = "SELECT * FROM books WHERE id=:id AND user_email=:user_email";
        $get_book = $conn->prepare($sql);
        $get_book->execute([
            'id' => $_GET['id'],
            'user_email' => $_SESSION['loggedEmail']
        ]);
        $book = $get_book->fetch(PDO::FETCH_ASSOC);

        // If not, redirect to index
        if(!$book || $book['user_email'] != $_SESSION['loggedEmail']) {
            header("Location: index.php");
            exit;
        }

        //Selected categories
        $sql = "SELECT id_categories FROM books_categories WHERE id_books=:id_books";
        $get_categories = $conn->prepare($sql);
        $get_categories->execute([
            'id_books' => $book['id']
        ]);
        $result_categories = $get_categories->fetchAll(PDO::FETCH_COLUMN);
    }

// Categories
$sql = "SELECT id, name FROM categories ORDER BY name";
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
    <title><?php echo $update == "yes" ? "Edit a book" : "Register a book" ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php' ?>
    <div class="container">
        <div class="container-row" style="align-items: center; gap: 20px;">
            <?php 
                if ($update == "yes") echo "<a href='book.php?id=" .  htmlspecialchars($book['id']) . "'><button class='back'> Go back </button></a>";
                else echo "<a href='index.php'><button class='back'> Go back </button></a>";
            ?>
            <h1><?php echo $update == "yes" ? "Edit a book" : "Register a book" ?></h1>
        </div>
        <form action="book_actions.php<?php if ($update == "yes") echo "?id=" . htmlspecialchars($book['id']) ?>" method="post" enctype="multipart/form-data">
            <div class="container-row">
                <label for="title"> Title* </label>
                <input type="text" name="title" placeholder="Lord of the Rings" id="title" value="<?php if ($update == "yes") echo htmlspecialchars($book['title']); else echo isset($_SESSION['book_title']) ? htmlspecialchars($_SESSION['book_title']) : ""; ?>" required>
            </div>
            <div class="container-row">
                <label for="description"> Description </label>
                <textarea name="description" placeholder="This is the description..." id="description"><?php if ($update == "yes") echo nl2br(htmlspecialchars($book['description'])); else echo isset($_SESSION['book_description']) ? htmlspecialchars($_SESSION['book_description']) : ""; ?></textarea>
            </div>
            <div class="container-row">
                <label for="author"> Author* </label>
                <input type="text" name="author" placeholder="John Doe" id="author" value="<?php if ($update == "yes") echo htmlspecialchars($book['author']); else echo isset($_SESSION['book_author']) ? htmlspecialchars($_SESSION['book_author']) : ""; ?>">
            </div>
            <div class="container-row">
                <label for="url"> URL </label>
                <input type="url" name="url" placeholder="https://example.com" id="url" value="<?php if ($update == "yes") echo htmlspecialchars($book['URL']); else echo isset($_SESSION['book_url']) ? htmlspecialchars($_SESSION['book_url']) : ""; ?>">
            </div>
            <div class="container-row">
                <label for="year"> Year </label>
                <input type="number" name="year" placeholder="2025" min=1 id="year" value="<?php if ($update == "yes") echo (int)$book['year'] != NULL ?  htmlspecialchars((int)$book['year']) : ""; else echo isset($_SESSION['book_year']) ? htmlspecialchars($_SESSION['book_year']) : "";?>">
            </div>
            <div class="container-row">
                <label for="userfile"> Cover </label>
                <input type="file" name="userfile" id="userfile">
            </div>
            <b> Categories </b>
            <hr>
            <div class="scroll">
                <?php
                    foreach ($result as $category) {
                        echo "<label class='container-row categories'>";
                        echo "<span>" . htmlspecialchars($category['name']) . "</span>";
                        echo "<input type='checkbox' name='categories[]' value='" . htmlspecialchars($category['id']) . "'";
                        if ($update == "yes") {
                            foreach ($result_categories as $selected) {
                                if ($category['id'] == $selected) {
                                    echo "checked";
                                }
                            }
                        }
                        echo ">";
                        echo "</label>";
                    }
                ?>
            </div>
            <hr>
            <?php
                if ($update == "yes"){
                    echo "<div class='container-row cover'>";
                    echo "<label class='container-row reset'>";
                    echo "<span> Reset cover </span>";
                    echo "<input type='checkbox' name='reset' value='yes'>";
                    echo "</label>";
                    echo "<img src='uploads/" . htmlspecialchars($book['cover']) . "' alt='Book cover' height='170px' width='120px'>";
                    echo "</div>";
                }
            ?>
            <button name="action" type="submit" style="width: 100%;" <?php if ($update == "yes") echo "value='update'" ?>><?php echo $update == "yes" ? "Update" : "Register a book" ?></button>
        </form>
    </div>
</body>
</html>
<?php unset($_SESSION['book_title'], $_SESSION['book_author'], $_SESSION['book_year'], $_SESSION['book_url'], $_SESSION['book_description']); ?>