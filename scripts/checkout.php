<?php

	session_start();
	
	if(empty($_SESSION['userID']) or $_SESSION['userID'] == '1')
	{
		if(isset($_SESSION['last_page']))
		{
			header("Location: ".$_SESSION['last_page']);
		}
		else
		{
			header("Location: ../index.php");
		}
	}
	else
	{
		include('connect.php');
		
		$ordersResult = mysql_query("SELECT * FROM orders_date");
		if(mysql_num_rows($ordersResult) > 0)
		{
			$orderIDResult = mysql_query("SELECT MAX(id) FROM orders_date");
			$orderID = mysql_fetch_array($orderIDResult, MYSQL_NUM);
			$oID = $orderID[0] + 1;
		}
		else
		{
			$oID = 1;
		}
		
		$sum = 0;

		$rateResult = mysql_query("SELECT rate FROM currency WHERE code = 'usd'");
		$rate = mysql_fetch_array($rateResult, MYSQL_NUM);

		$goodResult = mysql_query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND status = '0'");
		if(mysql_num_rows($goodResult) != 0)
		{
			$count = 0;
			while($good = mysql_fetch_array($goodResult, MYSQL_ASSOC))
			{
				$goodPriceResult = mysql_query("SELECT price FROM catalogue_new WHERE id = '".$good['good_id']."'");
				$goodPrice = mysql_fetch_array($goodPriceResult, MYSQL_NUM);

				$sum += $goodPrice[0] * $rate[0];

				if(mysql_query("INSERT INTO orders (order_id, user_id, good_id, quantity) VALUES ('".$oID."', '".$_SESSION['userID']."', '".$good['good_id']."', '".$good['quantity']."')"))
				{
					$count++;
				}
			}
			if(mysql_num_rows($goodResult) == $count)
			{
				if(mysql_query("INSERT INTO orders_date (id, user_id, date, sum, status) VALUES ('".$oID."', '".$_SESSION['userID']."', '".date('Y-m-d H:i:s')."', '".$sum."', '0')"))
				{
					if(mysql_query("DELETE FROM basket WHERE user_id = '".$_SESSION['userID']."' AND status = '0'"))
					{
						$_SESSION['orderComplete'] = 'ok';
						header("Location: ../order.php?s=1");
					}
					else
					{
							$_SESSION['orderComplete'] = 'failed';
							header("Location: ../order.php?s=1");
					}
				}
				else
				{
					$_SESSION['orderComplete'] = 'failed';
					header("Location: ../order.php?s=1");
				}
			}
			else
			{
				$_SESSION['orderComplete'] = 'failed';
				header("Location: ../order.php?s=1");
			}
		}
		else
		{
			if(isset($_SESSION['last_page']))
			{
				header("Location: ".$_SESSION['last_page']);
			}
			else
			{
				header("Location: ../index.php");
			}
		}
	}