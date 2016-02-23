<?php

	include('connect.php');

	if(!empty($_POST['orderID']) and !empty($_POST['goodID']))
	{
		if(mysql_query("DELETE FROM orders WHERE order_id = '".$_POST['orderID']."' AND good_id = '".$_POST['goodID']."'"))
		{
			$goodsCountResult = mysql_query("SELECT COUNT(id) FROM orders WHERE order_id = '".$_POST['orderID']."'");
			$goodsCount = mysql_fetch_array($goodsCountResult, MYSQL_NUM);

			if($goodsCount[0] == 0)
			{
				mysql_query("DELETE FROM orders_date WHERE id = '".$_POST['orderID']."'");
			}

			echo "a";
		}
	}

?>