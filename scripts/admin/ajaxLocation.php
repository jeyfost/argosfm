<?php

include ('../connect.php');

if($mysqli->query("UPDATE mail SET location = '".$_POST['location']."' WHERE id = '".$_POST['locationID']."'")) {
    echo "a";
} else {
    echo "b";
}