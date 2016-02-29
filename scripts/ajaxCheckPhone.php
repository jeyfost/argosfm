<?php

include('connect.php');

$phoneResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE phone  = '".$_POST['phone']."'");
$phone = $phoneResult->fetch_array(MYSQLI_NUM);

if($phone[0] == 0) {
    echo "a";
} else {
    echo "b";
}