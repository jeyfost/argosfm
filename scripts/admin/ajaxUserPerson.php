<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(!empty($_POST['userPerson']))
		{
			$personResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE person = '".$_POST['userPerson']."'");
			$person = $personResult->fetch_array(MYSQLI_NUM);

			if($person[0] == 0)
			{
				echo "a";
			}
			else
			{
				$userResult = $mysqli->query("SELECT * FROM users WHERE id = '".$_SESSION['user']."'");
				$user = $userResult->fetch_assoc();

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