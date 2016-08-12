<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(!empty($_POST['name']))
		{
			if($mysqli->query("UPDATE mail SET name = '".iconv('UTF-8', 'CP1251', $_POST['name'])."' WHERE id = '".$_POST['emailID']."'"))
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