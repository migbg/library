<?php
require 'connect.php';
session_start();

if (!isset($_SESSION['isLogged'])) header('Location: login_form.php');
else {
    //Update
    $update = isset($_GET['update']) ? $_GET['update'] : NULL;

    if ($update == "yes"){
        //All data
        $sql = "SELECT * FROM books WHERE id=:id AND user_email=:user_email";
        $get_book = $conn->prepare($sql);
        $get_book->execute([
            'id' => $_GET['id'],
            'user_email' => $_SESSION['loggedEmail']
        ]);
        $book = $get_book->fetch(PDO::FETCH_ASSOC);

        if(!$book || $book['user_email'] != $_SESSION['loggedEmail']) header('Location: books_list.php');

        //Selected categories
        $sql = "SELECT name_categories FROM books_categories WHERE id_books=:id_books";
        $get_categories = $conn->prepare($sql);
        $get_categories->execute([
            'id_books' => $book['id']
        ]);
        $result_categories = $get_categories->fetchAll(PDO::FETCH_COLUMN);
    }

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
    <title><?php echo $update == "yes" ? "Edit a book" : "Register a book" ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php' ?>
    <div class="container">
        <h1><?php echo $update == "yes" ? "Edit a book" : "Register a book" ?></h1>
        <form action="book_actions.php<?php if ($update == "yes") echo "?id=" . $book['id'] ?>" method="post" enctype="multipart/form-data">
            <div class="container-row">
                <label for="title"> Title* </label>
                <input type="text" name="title" id="title" value="<?php if ($update == "yes") echo htmlspecialchars($book['title']) ?>" required>
            </div>
            <div class="container-row">
                <label for="description"> Description </label>
                <textarea name="description" id="description">
                    <?php if ($update == "yes") echo nl2br(htmlspecialchars($book['description'])) ?>
                </textarea>
            </div>
            <div class="container-row">
                <label for="author"> Author* </label>
                <input type="text" name="author" id="author" value="<?php if ($update == "yes") echo htmlspecialchars($book['author']) ?>">
            </div>
            <div class="container-row">
                <label for="url"> URL </label>
                <input type="url" name="url" placeholder="https://example.com" id="url" value="<?php if ($update == "yes") echo htmlspecialchars($book['URL']) ?>">
            </div>
            <div class="container-row">
                <label for="year"> Year </label>
                <input type="number" name="year" id="year" value="<?php if ($update == "yes") echo (int)$book['year'] != NULL ?  htmlspecialchars((int)$book['year']) : ""?>">
            </div>
            <div class="container-row">
                <label for="category"> Category </label>
                <div>
                    <?php
                        foreach ($result as $category) {
                            echo "<div class='container-row'>";
                            echo "<label for='" . htmlspecialchars($category['name']) . "'>" . htmlspecialchars($category['name']) . "</label>";
                            echo "<input type='checkbox' id='" . htmlspecialchars($category['name']) . "' name='categories[]' value='" . htmlspecialchars($category['name']) . "'";
                            if ($update == "yes") {
                                foreach ($result_categories as $selected) {
                                    if ($category['name'] == $selected) {
                                        echo "checked";
                                    }
                                }
                            }
                            echo ">";
                            echo "</div>";
                        }
                    ?>
                </div>
            </div>
            <div class="container-row">
                <label for="userfile"> Cover </label>
                <input type="file" name="userfile" id="userfile">
            </div>
            <button name="action" type="submit" style="width: 100%;" <?php if ($update == "yes") echo "value='update'" ?>><?php echo $update == "yes" ? "Update" : "Register a book" ?></button>
        </form>
    </div>
</body>
</html>