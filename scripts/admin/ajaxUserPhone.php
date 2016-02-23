<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(!empty($_POST['userPhone']))
		{
			$phoneResult = mysql_query("SELECT COUNT(id) FROM users WHERE phone = '".$_POST['userPhone']."'");
			$phone = mysql_fetch_array($phoneResult, MYSQL_NUM);

			if($phone[0] == 0)
			{
				echo "a";
			}
			else
			{
				$userResult = mysql_query("SELECT * FROM users WHERE id = '".$_SESSION['user']."'");
				$user = mysql_fetch_assoc($userResult);

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