<script>
    function search(str) {
        if (str.length == 0) {
            document.getElementById("search-box").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("search-box").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", 'search.php?q=' + encodeURIComponent(str), true);
        xmlhttp.send();
        }
    }
</script>
<nav>
    <div class="container-nav">
        <a class="title" href="index.php"><h2> Library </h2></a>
        <div class="container-nav-buttons">
            <div class="search-container">
                <input type="text" name="search" id="search" onkeyup="search(this.value)" placeholder="Search...">
                <div id="search-box"></div>
            </div>
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