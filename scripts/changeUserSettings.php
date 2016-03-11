<?php

	session_start();
	include('connect.php');
	
	$userResult = $mysqli->query("SELECT * FROM users WHERE id = '".$_SESSION['userID']."'");
	$user = $userResult->fetch_assoc();
	
	if(!empty($user['organisation']))
	{
		if(!empty($_POST['settingsName']) and !empty($_POST['settingsOrganisation']) and !empty($_POST['settingsPhone']))
		{
			$organisation = mysqli_real_escape_string($mysqli, $_POST['settingsOrganisation']);
			$person = mysqli_real_escape_string($mysqli, $_POST['settingsName']);
			$phone = mysqli_real_escape_string($mysqli, $_POST['settingsPhone']);
			
			if($mysqli->query("UPDATE users SET organisation = '".$organisation."', person = '".$person."', phone = '".$person."' WHERE id = '".$_SESSION['userID']."'"))
			{
				$_SESSION['settingsChange'] = 'ok';
				header("Location: ../settings.php?s=1");
			}
			else
			{
				$_SESSION['settingsChange'] = 'failed';
				header("Location: ../settings.php?s=1");
			}
		}
		else
		{
			$_SESSION['settingsChange'] = 'empty';
			header("Location: ../settings.php?s=1");
		}
		
	}
	else
	{
		if(!empty($_POST['settingsName']) and !empty($_POST['settingsPhone']))
		{
			$person = mysqli_real_escape_string($mysqli, $_POST['settingsName']);
			$phone = mysqli_real_escape_string($_POST['settingsPhone']);
			
			if($mysqli->query("UPDATE users SET person = '".$person."', phone = '".$person."' WHERE id = '".$_SESSION['userID']."'"))
			{
				$_SESSION['settingsChange'] = 'ok';
				header("Location: ../settings.php?s=1");
			}
			else
			{
				$_SESSION['settingsChange'] = 'failed';
				header("Location: ../settings.php?s=1");
			}
		}
		else
		{
			$_SESSION['settingsChange'] = 'empty';
			header("Location: ../settings.php?s=1");
		}
	}

?>