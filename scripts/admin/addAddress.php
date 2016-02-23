<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == '1')
	{
		if(!empty($_POST['newAddress']))
		{
			if(filter_var($_POST['newAddress'], FILTER_VALIDATE_EMAIL))
			{
				if(!empty($_POST['newName']))
				{
					$addressResult = mysql_query("SELECT * FROM mail WHERE email = '".$_POST['newAddress']."'");
					if(mysql_num_rows($addressResult) == 0)
					{
						$hash = "";

						$symbols = array('q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');

						for($i = 0; $i < 32; $i++)
						{
							$number = rand(0, count($symbols) - 1);
							$hash .= $symbols[$number];
						}

						$name = strtolower($_POST['newAddress']);

						if(mysql_query("INSERT INTO mail (email, name, hash, in_send) VALUES ('".$name."', '".htmlspecialchars($_POST['newName'])."', '".$hash."', '1')"))
						{
							$_SESSION['addAddress'] = "ok";
							
							header("Location: ../../admin/admin.php?section=users&action=".$_SESSION['action']."&active=".$_SESSION['active']."&p=".$_SESSION['p']);
						}
						else
						{
							$_SESSION['addAddress'] = "failed";
							$_SESSION['newAddress'] = $_POST['newAddress'];
							$_SESSION['newName'] = $_POST['newName'];

							header("Location: ../../admin/admin.php?section=users&action=".$_SESSION['action']."&active=".$_SESSION['active']."&p=".$_SESSION['p']);
						}
					}
					else
					{
						$_SESSION['addAddress'] = "emailExists";
						$_SESSION['newAddress'] = $_POST['newAddress'];
						$_SESSION['newName'] = $_POST['newName'];
							
						header("Location: ../../admin/admin.php?section=users&action=".$_SESSION['action']."&active=".$_SESSION['active']."&p=".$_SESSION['p']);
					}
				}
				else
				{
					$_SESSION['addAddress'] = "name";
					$_SESSION['newAddress'] = $_POST['newAddress'];

					header("Location: ../../admin/admin.php?section=users&action=".$_SESSION['action']."&active=".$_SESSION['active']."&p=".$_SESSION['p']);
				}
			}
			else
			{
				$_SESSION['addAddress'] = "email";
				$_SESSION['newAddress'] = $_POST['newAddress'];
				$_SESSION['newName'] = $_POST['newName'];
					
				header("Location: ../../admin/admin.php?section=users&action=".$_SESSION['action']."&active=".$_SESSION['active']."&p=".$_SESSION['p']);
			}
		}
		else
		{
			$_SESSION['addAddress'] = "empty";
			$_SESSION['newAddress'] = $_POST['newAddress'];
			$_SESSION['newName'] = $_POST['newName'];
					
			header("Location: ../../admin/admin.php?section=users&action=".$_SESSION['action']."&active=".$_SESSION['active']."&p=".$_SESSION['p']);
		}
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}

?>