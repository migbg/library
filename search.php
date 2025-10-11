<?php
require 'connect.php';

$search = $_REQUEST['q'];
$sql = "SELECT id, title, author from books WHERE title LIKE :title";

$search_book = $conn->prepare($sql);
$search_book->execute([ 'title' => "%" . $search . "%" ]);
$search_result = $search_book->fetchAll(PDO::FETCH_ASSOC);

if (!$search_result) {
    echo "<div style='color: rgb(190, 190, 190);'> No result </div>";
}

foreach ($search_result as $book) {
    echo "<div><a href='book.php?id=". $book['id'] . "'>". $book['title'] . "<br><span style='color: rgba(148, 148, 148, 1); font-weight: normal;'> by " . $book['author'] . "</span></a></div>";
}

?>