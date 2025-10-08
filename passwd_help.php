<?php
    $request = $_REQUEST['q'];

    if (strlen($request) < 10){
        echo "<div class='passwd-hint-bad'>10 characters or more</div>";
    } else {
        echo "<div class='passwd-hint-good'>10 characters or more</div>";
    }

    if(preg_match('/[A-Z]/', $request)){
        echo "<div class='passwd-hint-good'>Uppercase</div>";
    } else {
        echo "<div class='passwd-hint-bad'>Uppercase</div>";
    }

    if(preg_match('/[a-z]/', $request)){
        echo "<div class='passwd-hint-good'>Lowercase</div>";
    } else {
        echo "<div class='passwd-hint-bad'>Lowercase</div>";
    }

    if(preg_match('/\d/', $request)){
        echo "<div class='passwd-hint-good'>Number</div>";
    } else {
        echo "<div class='passwd-hint-bad'>Number</div>";
    }

    if(preg_match('/[!@#$%^&*()\-_=+\[\]{}|;:\'",.<>?\/]/' , $request)){
        echo "<div class='passwd-hint-good'>Special character</div>";
    } else {
        echo "<div class='passwd-hint-bad'>Special character</div>";
    }
?>