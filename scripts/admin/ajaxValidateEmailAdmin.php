<?php

include ('../connect.php');

if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $clientResult = $mysqli->query("SELECT * FROM mail WHERE email = '".$_POST['email']."'");
    if($clientResult->num_rows == 0) {
        echo "a";
    } else {
        echo "b";
    }
} else {
    echo "b";
}