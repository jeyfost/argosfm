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
		$userResult = mysql_query("SELECT * FROM users WHERE hash = '".$_REQUEST['h']."'");
		$user = mysql_fetch_array($userResult, MYSQL_ASSOC);
		
		if(!empty($user))
		{
			if(mysql_query("DELETE FROM users WHERE hash = '".$_REQUEST['h']."'"))
			{
				$_SESSION['registration_cancel'] = 'ok';
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
				$_SESSION['registration_cancel'] = 'failed';
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
			$_SESSION['registration_cancel'] = 'hash';
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