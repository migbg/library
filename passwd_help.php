<?php
    $request = $_REQUEST['q'];

    if (strlen($request) < 10){
        echo "<div style='color:red'>10 characters or more</div>";
    } else {
        echo "<div style='color:green'>10 characters or more</div>";
    }

    if(preg_match('/[A-Z]/', $request)){
        echo "<div style='color:green'>Uppercase</div>";
    } else {
        echo "<div style='color:red'>Uppercase</div>";
    }

    if(preg_match('/[a-z]/', $request)){
        echo "<div style='color:green'>Lowercase</div>";
    } else {
        echo "<div style='color:red'>Lowercase</div>";
    }

    if(preg_match('/\d/', $request)){
        echo "<div style='color:green'>Number</div>";
    } else {
        echo "<div style='color:red'>Number</div>";
    }

    if(preg_match('/[!@#$%^&*()\-_=+\[\]{}|;:\'",.<>?\/]/' , $request)){
        echo "<div style='color:green'>Special character</div>";
    } else {
        echo "<div style='color:red'>Special character</div>";
    }
?>