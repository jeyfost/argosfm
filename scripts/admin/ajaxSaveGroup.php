<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 24.11.2017
 * Time: 10:10
 */

include("../connect.php");

$id = $mysqli->real_escape_string($_POST['clientID']);
$filter = $mysqli->real_escape_string($_POST['filterID']);

$clientCheckResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE id = '".$id."'");
$clientCheck = $clientCheckResult->fetch_array(MYSQLI_NUM);

if($clientCheck[0] > 0) {
	$filterCheckResult = $mysqli->query("SELECT COUNT(id) FROM filters WHERE id = '".$filter."'");
	$filterCheck = $filterCheckResult->fetch_array(MYSQLI_NUM);

	if($filterCheck[0] > 0) {
		if($mysqli->query("UPDATE mail SET filter = '".$filter."' WHERE id = '".$id."'")) {
			echo "a";
		} else {
			echo "b";
		}
	} else {
		echo "b";
	}
} else {
	echo "b";
}