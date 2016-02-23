<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(!empty($_POST['userLogin']))
		{
			$loginResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE login = '".$_POST['userLogin']."'");
			$login = $loginResult->fetch_array(MYSQLI_NUM);

			if($login[0] == 0)
			{
				echo "a";
			}
			else
			{
				$userResult = $mysqli->query("SELECT * FROM users WHERE id = '".$_SESSION['user']."'");
				$user = $userResult->fetch_assoc();

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