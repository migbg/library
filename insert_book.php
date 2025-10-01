<?php
require 'connect.php';
session_start();
unset($_SESSION['bookinfo'], $_SESSION['title']);

// Title filter
if (trim($_POST['title']) == ""){
    $_SESSION['bookinfo'] .= "<div> Title can't be blank or just spaces </div>";
} else {
    $title = $_POST['title'];
}

// Description
$description = $_POST['description'];

// Author
if (trim($_POST['author']) == ""){
    $_SESSION['bookinfo'] .= "<div> Author can't be blank or just spaces </div>";
} else {
    $author = $_POST['author'];
}

// URL
$url = filter_var($_POST['url'], FILTER_VALIDATE_URL) ? $_POST['url'] : NULL;

// Category
$categories = $_POST['categories'] != [] ? $_POST['categories'] : NULL;

// Year
$year = $_POST['year'] > 0 ? $_POST['year'] > 0 : NULL;

// Upload cover
if ($_FILES['userfile']['name'] != ""){
    $extension = strtolower(pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION));
    $file_name = uniqid('cover_', true) . '.' . $extension;
    $file_path = 'uploads/' . $file_name;

    $upload = true;
    $image_uploaded = false;

    if ($extension != "png" && $extension != "jpg" && $extension != "jpeg") {
        echo "Only PNG, JPG and JPEG allowed";
        $upload = false;
    }

    $check = getimagesize($_FILES['userfile']['tmp_name']);
    if ($check == false) {
        echo "File is not and image";
        $upload = false;
    }
}

if (isset($title) && isset($author)) {

    if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $file_path)){
        echo "Image uploaded";
    } else {
        echo "There was an error uploading your image";
    }

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
                $sql = "INSERT INTO books_categories VALUES (:id_books, :category)";
                $insert_books_genres = $conn->prepare($sql);
                $insert_books_genres->execute([
                    'id_books' => $book['id'],
                    'category' => $category
                ]);
            }
        }
    }

}

header('Location: book_form.php');