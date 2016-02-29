<?php

include('connect.php');

$loginResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE login = '".$_POST['login']."'");
$login = $loginResult->fetch_array(MYSQLI_NUM);
if($login[0] == 0) {
    echo "a";
} else {
    echo "b";
}