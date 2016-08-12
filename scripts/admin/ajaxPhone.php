<?php

session_start();
include('../connect.php');

if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1) {
    if($mysqli->query("UPDATE mail SET phone = '".iconv('UTF-8', 'CP1251', $_POST['phone'])."' WHERE id = '".$_POST['emailID']."'")) {
        echo "a";
    } else {
        echo "b";
    }
}