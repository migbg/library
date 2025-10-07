<nav>
    <div class="container-nav">
        <a class="title" href="index.php"><h2> Library </h2></a>
        <div class="container-nav-buttons">
            <a href="book_form.php"><button type="button"> Add book </button></a>
            <a href="logout.php"><button class="delete" type="button"> Sign out </button></a>
        </div>
    </div>
</nav>
<?php 
    if (isset($_SESSION['bookinfo'])){
        echo "<div class='container-info info'><b><span style='color: rgb(194, 44, 44)'>NOTICE:</span></b>" . $_SESSION['bookinfo'] . "</div>";
        unset($_SESSION['bookinfo']);
    }
?>