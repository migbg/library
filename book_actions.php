<?php
require 'connect.php';
include 'functions.php';

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
        $result = user_owns_book($conn, $book_id, $_SESSION['loggedEmail']);

        if(!$result || $result['user_email'] != $_SESSION['loggedEmail']) {
            header('Location: index.php');
            exit;
        } else {
            /* Get book categories */
            $result_categories = book_categories($conn, $result['id']);

            if (isset($categories)) {
                foreach ($result_categories as $actual_category) {
                    if (!in_array($actual_category, $categories)) {
                        delete_not_selected_book_categories($conn, $result['id'], $actual_category);
                    }
                }

                foreach ($categories as $update_categories) {
                    if (!in_array($update_categories, $result_categories)) {
                        insert_book_category($conn, $result['id'], $update_categories);
                    }
                }
            } else {
                delete_book_categories($conn, $result['id']);
            }

            /* Update book */
            update_book($conn, $book_id, $title, $description, $url, $year, $_SESSION['loggedEmail'], $author, $reset, $upload, $file_name, $result['cover']);

            if ($reset && $result['cover'] != "default.png") {
                unlink('uploads/' . $result['cover']);
            }
            $_SESSION['bookinfo'] .= "<div> Book updated </div>";
        }

    } else {
        /* Insert new book */
        insert_book($conn, $title, $description, $url, $year, $_SESSION['loggedEmail'], $author, $upload, $file_name);
        $_SESSION['bookinfo'] .= "<div> Book registered </div>";

        $result = select_last_book($conn);

        if (isset($categories)) {
            foreach ($result as $book) {
                foreach ($categories as $category) {
                    insert_book_category($conn, $book['id'], $category);
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