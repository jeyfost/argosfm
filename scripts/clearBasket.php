<?php

	session_start();
	
	if(empty($_SESSION['userID']))
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
		
		$basketResult = $mysqli->query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND status = '0'");
		if(MYSQLI_NUM_rows($basketResult) > 0)
		{
			if($mysqli->query("DELETE FROM basket WHERE user_id = '".$_SESSION['userID']."' AND status = '0'"))
			{
				$_SESSION['clearBasket'] = 'ok';
				header("Location: ../order.php?s=1");
			}
			else
			{
				$_SESSION['clearBasket'] = 'failed';
				header("Location: ../order.php?s=1");
			}
		}
		else
		{
			$_SESSION['clearBasket'] = 'empty';
			header("Location: ../order.php?s=1");
		}
	}

?>