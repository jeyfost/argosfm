<?php

session_start();
include('connect.php');

$rateResult = $mysqli->query("SELECT rate FROM currency WHERE code = 'usd'");
$rate = $rateResult->fetch_array(MYSQLI_NUM);

$newPrice = 0;

$goodResult = $mysqli->query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."'");
while($good = $goodResult->fetch_assoc()) {
    $goodPriceResult = $mysqli->query("SELECT price FROM catalogue_new WHERE id = '".$good['good_id']."'");
    $goodPrice = $goodPriceResult->fetch_array(MYSQLI_NUM);

    $newPrice += $good['quantity'] * $goodPrice[0] * $rate[0];
}

echo $newPrice;