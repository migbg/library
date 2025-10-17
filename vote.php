<?php
    require 'connect.php';
    session_start();
    if (!isset($_SESSION['isLogged'])) {
        header('Location: login_form.php');
        exit;
    }

    $vote = (int)$_REQUEST['vote'];
    $id_books = (int)$_REQUEST['id'];
    $user_email = $_SESSION['loggedEmail'];

    $sql = "SELECT * FROM users_votes WHERE user_email=:user_email AND id_books=:id_books";
    $get_vote = $conn->prepare($sql);
    $get_vote->execute([
        'user_email' => $user_email,
        'id_books' => $id_books
    ]);
    $result_vote = $get_vote->fetch(PDO::FETCH_ASSOC);

    if (!$result_vote) {
        $sql = "INSERT INTO users_votes (id_books, user_email, vote) VALUES (:id_books, :user_email, :vote)";
        $insert_vote = $conn->prepare($sql);
        $insert_vote->execute([
            'id_books' => $id_books,
            'user_email' => $user_email,
            'vote' => $vote
        ]);

        $sql = "SELECT (AVG(vote)*100) AS mean FROM users_votes WHERE id_books=:id_books";
        $votes_mean = $conn->prepare($sql);
        $votes_mean->execute([
            'id_books' => $id_books
        ]);
        $result_votes_mean = $votes_mean->fetchColumn();

        echo (int)$result_votes_mean . "%";

    } else {
        $sql = "UPDATE users_votes SET vote=:vote WHERE id_books=:id_books AND user_email=:user_email";
        $update_vote = $conn->prepare($sql);
        $update_vote->execute([
            'vote' => $vote,
            'id_books' => $id_books,
            'user_email' => $user_email
        ]);

        $sql = "SELECT (AVG(vote)*100) AS mean FROM users_votes WHERE id_books=:id_books";
        $votes_mean = $conn->prepare($sql);
        $votes_mean->execute([
            'id_books' => $id_books
        ]);
        $result_votes_mean = $votes_mean->fetchColumn();

        echo (int)$result_votes_mean . "%";
    }

?>