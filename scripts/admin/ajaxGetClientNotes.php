<?php

session_start();

if($_SESSION['userID'] != 1) {
    header("Location: ../../index.php");
}

include('../connect.php');

$clientResult = $mysqli->query("SELECT notes FROM mail WHERE id = '".$_POST['clientID']."'");
$client = $clientResult->fetch_array(MYSQLI_NUM);

echo iconv("cp1251", "utf-8", $client[0]);