<nav>
    <div class="container-nav">
        <a class="title" href="books_list.php"><h2> Library </h2></a>
        <div class="container-nav-buttons">
            <a href="book_form.php"><button type="button"> New book </button></a>
            <a href="logout.php"><button class="delete" type="button"> Log out</button></a>
        </div>
    </div>
</nav>
<?php 
    if (isset($_SESSION['bookinfo'])){
        echo "<div class='container-info info'><b>NOTICE:</b>". $_SESSION['bookinfo'] . "</div>";
        unset($_SESSION['bookinfo']);
    }
?>