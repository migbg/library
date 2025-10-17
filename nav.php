<script>
    function search(str) {
        if (str.length == 0) {
            document.getElementById("search-box").innerHTML = "";
            document.getElementById("search-box").style = "border: none;";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("search-box").innerHTML = this.responseText;
                document.getElementById("search-box").style = "border: 2px solid rgb(48, 104, 150);";
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
                <input type="text" name="search" id="search" onkeyup="search(this.value)" placeholder="Search a book title...">
                <div id="search-box"></div>
            </div>
            <a href="book_form.php"><button type="button"> Add book </button></a>
<!--             <a href="logout.php"><button class="delete" type="button"> Sign out </button></a>
            <a href="profile.php"><img class="nav-avatar" src="uploads/<?php echo htmlspecialchars($_SESSION['loggedAvatar']); ?>" alt="Avatar" height="40px" width="40px"></a> -->
            <details>
                <summary><img class="nav-avatar" src="uploads/<?php echo htmlspecialchars($_SESSION['loggedAvatar']); ?>" alt="Avatar" height="40px" width="40px"></summary>
                <div>
                    <a href="profile.php"><button class="back" type="button"> View profile </button></a>
                    <a href="logout.php"><button class="delete" type="button"> Sign out </button></a>  
                </div>
            </details>
        </div>
    </div>
</nav>
<?php 
    if (isset($_SESSION['bookinfo'])){
        echo "<div class='container-info info'><b><span style='color: rgb(194, 44, 44)'>NOTICE:</span></b>" . $_SESSION['bookinfo'] . "</div>";
        unset($_SESSION['bookinfo']);
    }
?>