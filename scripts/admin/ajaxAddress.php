<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(filter_var($_POST['newAddress'], FILTER_VALIDATE_EMAIL))
		{
			$addressResult = mysql_query("SELECT COUNT(id) FROM mail WHERE email = '".$_POST['newAddress']."'");
			$address = mysql_fetch_array($addressResult, MYSQL_NUM);

			if($address[0] == 0)
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
			echo "b";
		}
	}

?>