<?php

	session_start();
	include('connect.php');
	
	if(empty($_SESSION['userID']) or ($_SESSION['userID'] == 1))
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
		$userResult = $mysqli->query("SELECT * FROM users WHERE id = '".$_SESSION['userID']."'");
		$user = $userResult->fetch_assoc();

		$mysqli->query("INSERT INTO users_deleted (id, email, organisation, person, phone) VALUES ('".$user['id']."', '".$user['email']."', '".$user['organisation']."', '".$user['phone']."')");

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