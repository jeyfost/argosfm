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
		
		$ordersResult = $mysqli->query("SELECT * FROM orders_date");
		if($ordersResult->num_rows > 0)
		{
			$orderIDResult = $mysqli->query("SELECT MAX(id) FROM orders_date");
			$orderID = $orderIDResult->fetch_array(MYSQLI_NUM);
			$oID = $orderID[0] + 1;
		}
		else
		{
			$oID = 1;
		}
		
		$sum = 0;

		$rateResult = $mysqli->query("SELECT rate FROM currency WHERE code = 'usd'");
		$rate = $rateResult->fetch_array(MYSQLI_NUM);

		$goodResult = $mysqli->query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND status = '0'");
		if($goodResult->num_rows != 0)
		{
			$count = 0;
			while($good = $goodResult->fetch_assoc())
			{
				$goodPriceResult = $mysqli->query("SELECT price FROM catalogue_new WHERE id = '".$good['good_id']."'");
				$goodPrice = $goodPriceResult->fetch_array(MYSQLI_NUM);

				$sum += $goodPrice[0] * $rate[0] * $good['quantity'];

				if($mysqli->query("INSERT INTO orders (order_id, user_id, good_id, quantity) VALUES ('".$oID."', '".$_SESSION['userID']."', '".$good['good_id']."', '".$good['quantity']."')"))
				{
					$count++;
				}
			}
			if($goodResult->num_rows == $count)
			{
				if($mysqli->query("INSERT INTO orders_date (id, user_id, date, sum, status) VALUES ('".$oID."', '".$_SESSION['userID']."', '".date('Y-m-d H:i:s')."', '".$sum."', '0')"))
				{
					if($mysqli->query("DELETE FROM basket WHERE user_id = '".$_SESSION['userID']."' AND status = '0'"))
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