<?php

include('connect.php');

$userNameResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE person = '".iconv('UTF-8', 'CP1251', $_POST['name'])."'");
$userName = $userNameResult->fetch_array(MYSQLI_NUM);

if($userName[0] == 0) {
    echo "a";
} else {
    echo "b";
}