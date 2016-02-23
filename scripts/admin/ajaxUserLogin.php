<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(!empty($_POST['userLogin']))
		{
			$loginResult = mysql_query("SELECT COUNT(id) FROM users WHERE login = '".$_POST['userLogin']."'");
			$login = mysql_fetch_array($loginResult, MYSQL_NUM);

			if($login[0] == 0)
			{
				echo "a";
			}
			else
			{
				$userResult = mysql_query("SELECT * FROM users WHERE id = '".$_SESSION['user']."'");
				$user = mysql_fetch_assoc($userResult);

				if($user['login'] == $_POST['userLogin'])
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