<?php

include('connect.php');

$totalSum = 0;

$rateResult = $mysqli->query("SELECT rate FROM currency WHERE code = 'usd'");
$rate = $rateResult->fetch_array(MYSQLI_NUM);

$goodsResult = $mysqli->query("SELECT * FROM orders WHERE order_id = '".$_POST['orderID']."'");
while($goods = $goodsResult->fetch_assoc()) {
    $goodPriceResult = $mysqli->query("SELECT price FROM catalogue_new WHERE id = '".$goods['good_id']."'");
    $goodPrice = $goodPriceResult->fetch_array(MYSQLI_NUM);

    $totalSum += $goodPrice[0] * $rate[0] * $goods['quantity'];
}

echo floor(round($totalSum, 2))." ���. ".substr((round($totalSum, 2) - floor(round($totalSum, 2))), 2); if(strlen(substr((round($totalSum, 2) - floor(round($totalSum, 2))), 2)) == 0) {echo "00";} echo " ���.";