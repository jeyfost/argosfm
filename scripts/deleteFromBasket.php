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
			$catalogueResult = mysql_query("SELECT * FROM catalogue_new WHERE id = '".$_REQUEST['id']."'");
			$basketResult = mysql_query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND good_id = '".$_REQUEST['id']."' and status = '0'");
			
			if(mysql_num_rows($catalogueResult) > 0)
			{
				$count++;
			}
			
			if(mysql_num_rows($basketResult) > 0)
			{
				$count++;
			}
			
			if($count == 2)
			{
				if(mysql_query("DELETE FROM basket WHERE user_id = '".$_SESSION['userID']."' AND good_id = '".$_REQUEST['id']."' and status = '0'"))
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