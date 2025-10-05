<?php
require 'connect.php';
session_start();
if (!isset($_SESSION['isLogged'])) {
    header('Location: login_form.php');
    exit;
}

unset($_SESSION['bookinfo']);

//Update
$update = $_POST['action'] == "update" ? $_POST['action'] : NULL;
$book_id = isset($_GET['id']) ? $_GET['id'] : NULL;

//Reset cover
$reset = $_POST['reset'] == "yes" ? $_POST['reset'] : NULL;

// Title filter
if (trim($_POST['title']) == ""){
    $_SESSION['bookinfo'] .= "<div> Title can't be blank or just spaces </div>";
} else {
    $title = $_SESSION['book_title'] = $_POST['title'];
}

// Description
$description = $_SESSION['book_description'] = trim($_POST['description']);

// Author
if (trim($_POST['author']) == ""){
    $_SESSION['bookinfo'] .= "<div> Author can't be blank or just spaces </div>";
} else {
    $author = $_SESSION['book_author'] = $_POST['author'];
}

// URL
if (filter_var($_POST['url'], FILTER_VALIDATE_URL) || $_POST['url'] == "") {
    $url = $_SESSION['book_url'] = $_POST['url'];
} else {
    $_SESSION['bookinfo'] .= "<div> Use a valid URL or leave it blank </div>";
}

// Category
$categories = $_POST['categories'] != [] ? $_POST['categories'] : NULL;

// Year
if ($_POST['year'] > 0 || $_POST['year'] == NULL) {
    $year = $_SESSION['book_year'] = $_POST['year'];
} else {
    $_SESSION['bookinfo'] .= "<div> Year must be grater than 1 at least or leave it blank </div>";
}

// Upload cover
if ($_FILES['userfile']['name'] != ""){
    $extension = strtolower(pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION));
    $file_name = uniqid('cover_', true) . '.' . $extension;
    $file_path = 'uploads/' . $file_name;

    $upload = $reset ? false : true;

    if ($extension != "png" && $extension != "jpg" && $extension != "jpeg") {
        $_SESSION['bookinfo'] .= "<div> Only PNG, JPG and JPEG allowed </div>";
        $upload = false;
    }

    $check = getimagesize($_FILES['userfile']['tmp_name']);
    if ($check == false) {
        $_SESSION['bookinfo'] .= "<div> File is not and image </div>";
        $upload = false;
    }
}

if (isset($title) && isset($author) && isset($year) && isset($url)) {

    if ($upload){
        move_uploaded_file($_FILES["userfile"]["tmp_name"], $file_path);
    }

    if (isset($update) && isset($book_id)) {
        // Check if user owns the book
        $sql = "SELECT * FROM books WHERE id=:id AND user_email=:user_email";
        $get_book = $conn->prepare($sql);
        $get_book->execute([
            'id' => $book_id,
            'user_email' => $_SESSION['loggedEmail']
        ]);
        $result = $get_book->fetch(PDO::FETCH_ASSOC);

        if(!$result || $result['user_email'] != $_SESSION['loggedEmail']) {
            header('Location: index.php');
            exit;
        } else {
            $sql = "SELECT id_categories FROM books_categories WHERE id_books=:id";
            $get_actual_categories = $conn->prepare($sql);
            $get_actual_categories->execute([ 'id' => $result['id']]);
            $result_categories = $get_actual_categories->fetchAll(PDO::FETCH_COLUMN);

            if (isset($categories)) {
                foreach ($result_categories as $actual_category) {
                    if (!in_array($actual_category, $categories)) {
                        $sql = "DELETE FROM books_categories WHERE id_books=:id_books AND id_categories=:id_categories";
                        $delete_category = $conn->prepare($sql);
                        $delete_category->execute([
                            'id_books' => $result['id'],
                            'id_categories' => $actual_category
                        ]);
                    }
                }

                foreach ($categories as $update_categories) {
                    if (!in_array($update_categories, $result_categories)) {
                        $sql = "INSERT INTO books_categories (id_books, id_categories) VALUES (:id_books, :id_categories)";
                        $insert_categories = $conn->prepare($sql);
                        $insert_categories->execute([
                            'id_books' => $result['id'],
                            'id_categories' => (int)$update_categories
                        ]);
                    }
                }
            } else {
                $sql = "DELETE FROM books_categories WHERE id_books=:id_books";
                $delete_all_categories = $conn->prepare($sql);
                $delete_all_categories->execute([
                    'id_books' => $result['id']
                ]);
            }

            $sql = "UPDATE books SET title=:title, description=:description, url=:url, year=:year, user_email=:user_email, author=:author, cover=:cover WHERE id=:id";
            $update_books = $conn->prepare($sql);
            $update_books->execute([
                'title' => $title,
                'description' => $description,
                'url' => $url,
                'year' => $year,
                'user_email' => $_SESSION['loggedEmail'],
                'author' => $author,
                'cover' => $reset ? 'default.png' : ($upload ? $file_name : $result['cover']),
                'id' => $book_id
            ]);

            if ($reset) {
                unlink('uploads/' . $result['cover']);
            }
            $_SESSION['bookinfo'] .= "<div> Book updated </div>";
        }

    } else {
        $sql = "INSERT INTO books (title, description, url, year, user_email, author, cover) VALUES (:title, :description, :url, :year, :user_email, :author, :cover)";
        $insert_book = $conn->prepare($sql);
        $insert_book->execute([
            'title' => $title,
            'description' => $description,
            'url' => $url,
            'year' => $year,
            'user_email' => $_SESSION['loggedEmail'],
            'author' => $author,
            'cover' => $upload ? $file_name : "default.png"
        ]);

        $_SESSION['bookinfo'] .= "<div> Book registered </div>";

        $sql = "SELECT id FROM books ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        if (isset($categories)) {
            foreach ($result as $book) {
                foreach ($categories as $category) {
                    $sql = "INSERT INTO books_categories (id_books, id_categories) VALUES (:id_books, :id_categories)";
                    $insert_books_genres = $conn->prepare($sql);
                    $insert_books_genres->execute([
                        'id_books' => $book['id'],
                        'id_categories' => (int)$category
                    ]);
                }
            }
        }

        unset($_SESSION['book_title'], $_SESSION['book_author'], $_SESSION['book_year'], $_SESSION['book_url'], $_SESSION['book_description']);
    }
}

if ($update) {
    header('Location: book_form.php?id=' . $book_id . '&update=yes');
    exit;
} else {
    header('Location: book_form.php');
    exit;
}
?>