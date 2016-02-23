<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(!empty($_POST['userPerson']))
		{
			$personResult = mysql_query("SELECT COUNT(id) FROM users WHERE person = '".$_POST['userPerson']."'");
			$person = mysql_fetch_array($personResult, MYSQL_NUM);

			if($person[0] == 0)
			{
				echo "a";
			}
			else
			{
				$userResult = mysql_query("SELECT * FROM users WHERE id = '".$_SESSION['user']."'");
				$user = mysql_fetch_assoc($userResult);

				if($user['person'] == $_POST['userPerson'])
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