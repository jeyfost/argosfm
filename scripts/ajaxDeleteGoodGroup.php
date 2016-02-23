<?php

	include('connect.php');

	if(!empty($_POST['orderID']) and !empty($_POST['goodID']))
	{
		if($mysqli->query("DELETE FROM orders WHERE order_id = '".$_POST['orderID']."' AND good_id = '".$_POST['goodID']."'"))
		{
			$goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM orders WHERE order_id = '".$_POST['orderID']."'");
			$goodsCount = $mysqli->$goodsCountResult(MYSQLI_NUM);

			if($goodsCount[0] == 0)
			{
				$mysqli->query("DELETE FROM orders_date WHERE id = '".$_POST['orderID']."'");
			}

			echo "a";
		}
	}

?>