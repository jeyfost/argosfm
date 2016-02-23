<?php

	session_start();
	include('connect.php');
	
	if(empty($_REQUEST['h']))
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
		$userResult = $mysqli->query("SELECT * FROM users WHERE hash = '".$_REQUEST['h']."'");
		$user = $userResult->fetch_assoc();
		
		if(!empty($user))
		{
			if($mysqli->query("UPDATE users SET activated = 1 WHERE hash = '".$_REQUEST['h']."'"))
			{
				$_SESSION['activation'] = 'ok';
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
		else
		{
			$_SESSION['activation'] = 'hash';
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

?>