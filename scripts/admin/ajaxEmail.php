<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		{
			$addressResult = mysql_query("SELECT COUNT(id) FROM mail WHERE email = '".$_POST['email']."'");
			$address = mysql_fetch_array($addressResult, MYSQL_NUM);

			if($address[0] == 0)
			{
				if(mysql_query("UPDATE mail SET email = '".$_POST['email']."' WHERE id = '".$_POST['emailID']."'"))
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
				$emailResult = mysql_query("SELECT * FROM mail WHERE id = '".$_POST['emailID']."'");
				$email = mysql_fetch_assoc($emailResult);

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