<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(!empty($_POST['userEmail']) and filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL))
		{
			$addressResult = mysql_query("SELECT COUNT(id) FROM users WHERE email = '".$_POST['userEmail']."'");
			$address = mysql_fetch_array($addressResult, MYSQL_NUM);

			if($address[0] == 0)
			{
				echo "a";
			}
			else
			{
				$userResult = mysql_query("SELECT * FROM users WHERE id = '".$_SESSION['user']."'");
				$user = mysql_fetch_assoc($userResult);

				if($user['email'] == $_POST['userEmail'])
				{
					echo "a"
				}
				else
				{
					echo "b";
				}
			}
		}
		else
		{
			echo "b";
		}
	}

?>