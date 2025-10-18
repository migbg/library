<?php
require 'connect.php';
include 'functions.php';

$search = $_REQUEST['q'];
$search_result = select_book_AJAX($conn, $search);

if (!$search_result) {
    echo "<div style='color: rgb(190, 190, 190);'> No result </div>";
}

foreach ($search_result as $book) {
    echo "<div><a href='book.php?id=". $book['id'] . "'>". $book['title'] . "<br><span style='color: rgba(148, 148, 148, 1); font-weight: normal;'> by " . $book['author'] . "</span></a></div>";
}

?>