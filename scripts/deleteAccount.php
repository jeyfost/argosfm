<?php

	session_start();
	include('connect.php');
	
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
		if($mysqli->query("DELETE FROM users WHERE id = '".$_SESSION['userID']."'"))
		{
			unset($_SESSION['userID']);
			$_SESSION['delete'] = 'ok';
			header("Location: ../index.php");
		}
		else
		{
			$_SESSION['delete'] = 'failed';
			header("Location: ../settings.php?s=3");	
		}
	}

?>