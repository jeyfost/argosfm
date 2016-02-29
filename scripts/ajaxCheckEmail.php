<?php

include('connect.php');

if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $emailResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE email = '".$_POST['email']."'");
    $email = $emailResult->fetch_array(MYSQLI_NUM);

    if($email[0] == 0) {
        echo "a";
    } else {
        echo "b";
    }
} else {
    echo "b";
}