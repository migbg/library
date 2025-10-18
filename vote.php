<?php
    require 'connect.php';
    include 'functions.php';

    session_start();
    if (!isset($_SESSION['isLogged'])) {
        header('Location: login_form.php');
        exit;
    }

    $vote = $_REQUEST['vote'];
    $id_books = $_REQUEST['id'];
    $user_email = $_SESSION['loggedEmail'];

    // Check if book exits
    $result_book = select_book($conn, $id_books);

    if (!$result_book) {
        echo "Invalid book id";
        exit;
    }

    //Check if vote is not 1 or 0 (coming as string)
    if (($vote != '1' && $vote != '0')) {
        echo "Invalid vote value";
        exit;
    }

    /* Select user votes */
    $result_vote = select_user_votes($conn, $user_email, $id_books);

    if (!$result_vote) {

        /* Insert user votes */
        insert_user_vote($conn, $id_books, $user_email, $vote);

        /* Gets the votes mean of a book */
        $result_votes_mean = calculate_votes_mean($conn, $id_books);

        echo (int)$result_votes_mean . "%";

    } else {

        /* Update user vote */
        update_user_vote($conn, $id_books, $user_email, $vote);

        /* Gets the votes mean of a book */
        $result_votes_mean = calculate_votes_mean($conn, $id_books);

        echo (int)$result_votes_mean . "%";
    }

?>