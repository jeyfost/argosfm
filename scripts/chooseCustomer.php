<?php

if($_SESSION['userID'] != 1) {
    header("Location: ../index.php");
}

if(!empty($_POST['customerSelect'])) {
    header("Location: ../order.php?s=2&customer=".$_POST['customerSelect']."&p=1");
} else {
    header("Location: ../order.php?s=2&customer=all&p=1");
}