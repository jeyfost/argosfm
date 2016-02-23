<?php
	
	session_start();
	include('connect.php');
	
	if(empty($_SESSION['userID']) or $_SESSION['userID'] != '1')
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
		if(empty($_POST['exchangeRate']))
		{
			$_SESSION['exchange'] = 'empty';
			header("Location: ../settings.php?s=3");
		}
		else
		{
			if(is_numeric($_POST['exchangeRate']))
			{
				if($mysqli->query("UPDATE currency SET rate='".$_POST['exchangeRate']."' WHERE code = 'usd'"))
				{
					$_SESSION['exchange'] = 'ok';
					header("Location: ../settings.php?s=3");
				}
				else
				{
					$_SESSION['exchange'] = 'false';
					header("Location: ../settings.php?s=3");
				}
			}
			else
			{
				$_SESSION['exchange'] = 'format';
				header("Location: ../settings.php?s=3");
			}
		}
	}

?>