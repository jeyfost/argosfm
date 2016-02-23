<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		{
			$addressResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE email = '".$_POST['email']."'");
			$address = $addressResult->fetch_array(MYSQLI_NUM);

			if($address[0] == 0)
			{
				if($mysqli->query("UPDATE mail SET email = '".$_POST['email']."' WHERE id = '".$_POST['emailID']."'"))
				{
					echo "a";
				}
				else
				{
					echo "b";
				}
			}
			else
			{
				$emailResult = $mysqli->query("SELECT * FROM mail WHERE id = '".$_POST['emailID']."'");
				$email = $emailResult->fetch_assoc();

				if($_POST['email'] == $email['email'])
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