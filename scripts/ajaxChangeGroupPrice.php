<?php

include('connect.php');

$rateResult = $mysqli->query("SELECT rate FROM currency WHERE code='usd'");
$rate = $rateResult->fetch_array(MYSQLI_NUM);

$goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$_POST['goodID']."'");
$good = $goodResult->fetch_assoc();

$orderResult = $mysqli->query("SELECT * FROM orders WHERE order_id = '".$_POST['orderID']."' AND good_id = '".$_POST['goodID']."'");
$order = $orderResult->fetch_assoc();

$newSum = $order['quantity'] * $good['price'] * $rate[0];

echo floor(round($newSum, 2))." руб. ".substr((round($newSum, 2) - floor(round($newSum, 2))), 2); if(strlen(substr((round($newSum, 2) - floor(round($newSum, 2))), 2)) == 0) {echo "00";} echo " коп.";