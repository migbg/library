<?php
require 'connect.php';

$search = $_REQUEST['q'];
$sql = "SELECT id, title from books WHERE title LIKE :title";

$search_book = $conn->prepare($sql);
$search_book->execute([ 'title' => "%" . $search . "%" ]);
$search_result = $search_book->fetchAll(PDO::FETCH_ASSOC);

foreach ($search_result as $book) {
    echo "<div><a href='book.php?id=". $book['id'] . "'>". $book['title'] . "</a></div>";
}

?>