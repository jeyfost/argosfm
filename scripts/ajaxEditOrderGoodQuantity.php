<?php

    include('connect.php');

    if($mysqli->query("UPDATE orders SET quantity = \"".$_POST['quantity']."\" WHERE good_id = \"".$_POST['goodID']."\" AND order_id = \"".$_POST['orderID']."\"")) {
        echo "a";
    }