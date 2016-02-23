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
			if($mysqli->query("DELETE FROM users WHERE hash = '".$_REQUEST['h']."'"))
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