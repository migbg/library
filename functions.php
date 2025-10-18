<?php 

/* Select ALL book */
function select_all_books($conn){
    $sql = "SELECT id, title, author, cover FROM books ORDER BY title";
    return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

/* Select book by id */
function select_book($conn, $id_books){
    $sql = "SELECT * FROM books WHERE id=:id";
    $get_book = $conn->prepare($sql);
    $get_book->execute([ 'id' => $id_books ]);
    return $get_book->fetch(PDO::FETCH_ASSOC);
}

/* Check if users owns the book */
function user_owns_book ($conn, $id_books, $user_email) {
    $sql = "SELECT * FROM books WHERE id=:id AND user_email=:user_email";
    $get_book = $conn->prepare($sql);
    $get_book->execute([
        'id' => $id_books,
        'user_email' => $user_email
    ]);
    return $get_book->fetch(PDO::FETCH_ASSOC);
}

/* Select all categories */
function select_categories($conn){
    $sql = "SELECT id, name FROM categories ORDER BY name";
    return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

/* Get book categories */
function book_categories ($conn, $id){
    $sql = "SELECT id_categories FROM books_categories WHERE id_books=:id";
    $get_actual_categories = $conn->prepare($sql);
    $get_actual_categories->execute([ 'id' => $id ]);
    return $get_actual_categories->fetchAll(PDO::FETCH_COLUMN);
}

/* Select categories names (junction table) */
function book_categories_name($conn, $id_books){
    $sql = "SELECT name FROM categories INNER JOIN books_categories ON books_categories.id_categories=categories.id WHERE books_categories.id_books=:id_books ORDER BY name";
    $get_categories = $conn->prepare($sql);
    $get_categories->execute([
        'id_books' => $id_books
    ]);
    return $get_categories->fetchAll(PDO::FETCH_COLUMN);
}

/* Delete a book's category from DB if it's not select when updating */
function delete_not_selected_book_categories($conn, $id_books, $category_name){
    $sql = "DELETE FROM books_categories WHERE id_books=:id_books AND id_categories=:id_categories";
    $delete_category = $conn->prepare($sql);
    $delete_category->execute([
        'id_books' => $id_books,
        'id_categories' => $category_name
    ]);
}

/* Insert book category */
function insert_book_category($conn, $id_books, $id_categories){
    $sql = "INSERT INTO books_categories (id_books, id_categories) VALUES (:id_books, :id_categories)";
    $insert_categories = $conn->prepare($sql);
    $insert_categories->execute([
        'id_books' => $id_books,
        'id_categories' => (int)$id_categories
    ]);
}

/* Delete all book categories */
function delete_book_categories($conn, $id_books){
    $sql = "DELETE FROM books_categories WHERE id_books=:id_books";
    $delete_all_categories = $conn->prepare($sql);
    $delete_all_categories->execute([
        'id_books' => $id_books
    ]);
}

/* Update book */
function update_book($conn, $id, $title, $description, $url, $year, $user_email, $author, $reset_cover, $can_upload_cover, $new_cover, $actual_cover){
    $sql = "UPDATE books SET title=:title, description=:description, url=:url, year=:year, user_email=:user_email, author=:author, cover=:cover WHERE id=:id";
    $update_books = $conn->prepare($sql);
    $update_books->execute([
        'title' => $title,
        'description' => $description,
        'url' => $url,
        'year' => $year,
        'user_email' => $user_email,
        'author' => $author,
        'cover' => $reset_cover ? 'default.png' : ($can_upload_cover ? $new_cover : $actual_cover),
        'id' => $id
    ]);
}

/* Insert new book */
function insert_book($conn, $title, $description, $url, $year, $user_email, $author, $can_upload_cover, $new_cover){
    $sql = "INSERT INTO books (title, description, url, year, user_email, author, cover) VALUES (:title, :description, :url, :year, :user_email, :author, :cover)";
    $insert_book = $conn->prepare($sql);
    $insert_book->execute([
        'title' => $title,
        'description' => $description,
        'url' => $url,
        'year' => $year,
        'user_email' => $user_email,
        'author' => $author,
        'cover' => $can_upload_cover ? $new_cover : "default.png"
    ]);
}

/* Delete book */
function delete_book($conn, $id_books){
    $sql = "DELETE FROM books WHERE id=:id";
    $delete_book = $conn->prepare($sql);
    $delete_book->execute([ 'id' => $id_books ]);
}

/* Get last book */
function select_last_book($conn){
    $sql = "SELECT id FROM books ORDER BY id DESC LIMIT 1";
    return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

/* Add 1 visit to book */
function increase_book_visits($conn, $id_books){
    $sql = "UPDATE books SET visits=visits+1 WHERE id=:id";
    $add_visit = $conn->prepare($sql);
    $add_visit->execute([ 'id' => $id_books ]);
}

/* Select user votes */
function select_user_votes($conn, $user_email, $id_books){
    $sql = "SELECT * FROM users_votes WHERE user_email=:user_email AND id_books=:id_books";
    $get_votes = $conn->prepare($sql);
    $get_votes->execute([
        'user_email' => $user_email,
        'id_books' => $id_books
    ]);
    return $get_votes->fetch(PDO::FETCH_ASSOC);
}

/* Insert user votes */
function insert_user_vote($conn, $id_books, $user_email, $vote){
    $sql = "INSERT INTO users_votes (id_books, user_email, vote) VALUES (:id_books, :user_email, :vote)";
    $insert_vote = $conn->prepare($sql);
    $insert_vote->execute([
        'id_books' => $id_books,
        'user_email' => $user_email,
        'vote' => $vote
    ]);
}

/* Update user vote */
function update_user_vote($conn, $id_books, $user_email, $vote){
    $sql = "UPDATE users_votes SET vote=:vote WHERE id_books=:id_books AND user_email=:user_email";
    $update_vote = $conn->prepare($sql);
    $update_vote->execute([
        'vote' => $vote,
        'id_books' => $id_books,
        'user_email' => $user_email
    ]);
}

/* Gets the votes mean of a book */
function calculate_votes_mean($conn, $id_books){
    $sql = "SELECT (AVG(vote)*100) AS mean FROM users_votes WHERE id_books=:id_books";
    $votes_mean = $conn->prepare($sql);
    $votes_mean->execute([
        'id_books' => $id_books
    ]);
    return $votes_mean->fetchColumn();
}

/* Check user token */
function check_token($conn, $token){
    $sql = "SELECT * FROM users_tokens WHERE token=:token";
    $check_token = $conn->prepare($sql);
    $check_token->execute([ 'token' => $token ]);
    return $check_token->fetch(PDO::FETCH_ASSOC);
}

/* Create user token */
function create_token($conn, $token, $expires_at, $user_email){
    $sql = "INSERT INTO users_tokens (token, expires_at, user_email) VALUES (:token, :expires_at, :user_email)";
    $insert_token = $conn->prepare($sql);
    $insert_token->execute([ 
        'token' => $token,
        'expires_at' => date('Y-m-d H:i:s', $expires_at),
        'user_email' => $user_email
    ]);
}

/* Delete user token */
function delete_token($conn, $token, $user_email){
    $sql = "DELETE FROM users_tokens WHERE token=:token AND user_email=:user_email";
    $delete_token = $conn->prepare($sql);
    $delete_token->execute([ 
        'token' => $token,
        'user_email' => $user_email
    ]);
}

/* Get user data */
function select_user_data($conn, $user_email){
    $sql = "SELECT * FROM users WHERE email=:email";
    $searchUser = $conn->prepare($sql);
    $searchUser->execute(['email' => $user_email]);
    return $searchUser->fetch(PDO::FETCH_ASSOC);
}

/* Update user data */
function update_user_data($conn, $user_email, $name, $current_name, $reset_avatar, $new_avatar, $current_avatar, $new_passwd, $current_passwd){
    $sql = "UPDATE users SET name=:name, avatar=:avatar, password=:passwd WHERE email=:email";
    $update_user = $conn->prepare($sql);
    $update_user->execute([
        'name' => $name ? $name : $current_name,
        'avatar' => $reset_avatar ? 'default-avatar.png' : ($new_avatar ? $new_avatar : $current_avatar),
        'email' => $user_email,
        'passwd' => $new_passwd ? $new_passwd : $current_passwd
    ]);
}

/* Create user */
function create_user($conn, $name, $email, $passwd, $can_upload_avatar, $avatar){
    $sql = "INSERT INTO users VALUES (:email, :passwd, :name, :avatar)";
    $insertUser = $conn->prepare($sql);
    $insertUser->execute([
        'name' => $name,
        'email' => $email,
        'passwd' => $passwd,
        'avatar' => $can_upload_avatar ? $avatar : "default-avatar.png"
    ]);
}

/* AJAX search */
function select_book_AJAX($conn, $title){
    $sql = "SELECT id, title, author from books WHERE title LIKE :title";
    $search_book = $conn->prepare($sql);
    $search_book->execute([ 'title' => "%" . $title . "%" ]);
    return $search_book->fetchAll(PDO::FETCH_ASSOC);
}

/* Books data and votes mean from owned books */
function my_books_data_and_votes_mean($conn, $user_email){
    $sql = "SELECT books.*, (AVG(users_votes.vote)*100) as mean FROM books LEFT JOIN users_votes ON books.id = users_votes.id_books WHERE books.user_email=:user_email GROUP BY id ORDER BY visits DESC";
    $stats = $conn->prepare($sql);
    $stats->execute([ 'user_email' => $user_email ]);
    return $stats->fetchAll(PDO::FETCH_ASSOC);
}

?>