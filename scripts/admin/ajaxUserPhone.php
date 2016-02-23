<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(!empty($_POST['userPhone']))
		{
			$phoneResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE phone = '".$_POST['userPhone']."'");
			$phone = $phoneResult->fetch_array(MYSQLI_NUM);

			if($phone[0] == 0)
			{
				echo "a";
			}
			else
			{
				$userResult = $mysqli->query("SELECT * FROM users WHERE id = '".$_SESSION['user']."'");
				$user = $userResult->fetch_assoc();

				if($user['phone'] == $_POST['userPhone'])
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