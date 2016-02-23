<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(!empty($_POST['userEmail']) and filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL))
		{
			$addressResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE email = '".$_POST['userEmail']."'");
			$address = $addressResult->fetch_array(MYSQLI_NUM);

			if($address[0] == 0)
			{
				echo "a";
			}
			else
			{
				$userResult = $mysqli->query("SELECT * FROM users WHERE id = '".$_SESSION['user']."'");
				$user = $userResult->fetch_assoc();

				if($user['email'] == $_POST['userEmail'])
				{
					echo "a";
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