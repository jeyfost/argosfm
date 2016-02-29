<?php

include('connect.php');

$organisationResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE organisation = '".iconv('UTF-8', 'CP1251', addslashes($_POST['organisation']))."'");
$organisation = $organisationResult->fetch_array(MYSQLI_NUM);

if($organisation[0] == 0) {
    echo "a";
} else {
    echo "b";
}