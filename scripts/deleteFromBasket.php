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
		if(!empty($_REQUEST['id']) and is_int((int)$_REQUEST['id']))
		{
			include('connect.php');
			
			$count = 0;
			$catalogueResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$_REQUEST['id']."'");
			$basketResult = $mysqli->query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND good_id = '".$_REQUEST['id']."' and status = '0'");
			
			if(MYSQLI_NUM_rows($catalogueResult) > 0)
			{
				$count++;
			}
			
			if(MYSQLI_NUM_rows($basketResult) > 0)
			{
				$count++;
			}
			
			if($count == 2)
			{
				if($mysqli->query("DELETE FROM basket WHERE user_id = '".$_SESSION['userID']."' AND good_id = '".$_REQUEST['id']."' and status = '0'"))
				{
					$_SESSION['deleteFromBasket'] = 'ok';
					header("Location: ../order.php?s=1");
				}
				else
				{
					$_SESSION['deleteFromBasket'] = 'failed';
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
	}
?>