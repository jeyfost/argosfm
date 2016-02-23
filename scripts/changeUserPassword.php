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
		if(!empty($_POST['newPassword']) and !empty($_POST['newPasswordRepeat']))
		{
			if($_POST['newPassword'] == $_POST['newPasswordRepeat'])
			{
				if(strlen($_POST['newPassword']) >=5 and strlen($_POST['newPassword']) <= 25)
				{
					if(mysql_query("UPDATE users SET password = '".md5(md5($_POST['newPassword']))."' WHERE id = '".$_SESSION['userID']."'"))
					{
						$_SESSION['settingsChange'] = 'ok';
						header("Location: ../settings.php?s=2");
					}
					else
					{
						$_SESSION['settingsChange'] = 'failed';
						header("Location: ../settings.php?s=2");
					}
				}
				else
				{
					$_SESSION['settingsChange'] = 'length';
					header("Location: ../settings.php?s=2");
				}
			}
			else
			{
				$_SESSION['settingsChange'] = 'different';
				header("Location: ../settings.php?s=2");
			}
		}
		else
		{
			$_SESSION['settingsChange'] = 'empty';
			header("Location: ../settings.php?s=2");
		}
	}

?>