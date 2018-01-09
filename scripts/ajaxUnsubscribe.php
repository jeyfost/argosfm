<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 09.01.2018
 * Time: 14:21
 */

include('connect.php');

$id = $mysqli->real_escape_string($_POST['id']);

if ($mysqli->query("UPDATE mail SET in_send = '0', disactivation_date = '" . date("Y-m-d") . "' WHERE id = '" . $id . "'")) {
	echo "ok";
} else {
	echo "failed";
}