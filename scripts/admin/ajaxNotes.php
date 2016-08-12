<?php

session_start();
include('../connect.php');

if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1) {
    $notes = str_replace("\n", "<br>", $_POST['notes']);

    if($mysqli->query("UPDATE mail SET notes = '".iconv('UTF-8', 'CP1251', $notes)."' WHERE id = '".$_POST['emailID']."'")) {
        echo "a";
    } else {
        echo "b";
    }
}