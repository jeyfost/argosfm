<?php

    include('connect.php');

    if($mysqli->query("UPDATE orders SET quantity = '".$_POST['quantity']."' WHERE order_id = '".$_POST['orderID']."' AND good_id = '".$_POST['goodID']."'") === TRUE) {
        echo "a";
    }