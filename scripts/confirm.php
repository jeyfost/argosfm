<?php

	session_start();
	include('connect.php');
	
	if(empty($_REQUEST['h']))
	{
		if(isset($_SESSION['last_page']))
		{
			header("Location: ".$_SESSION['last_page']);
		}
		else
		{
			header("Location: ../index.php");
		}
	}
	else
	{
		$userResult = $mysqli->query("SELECT * FROM users WHERE hash = '".$_REQUEST['h']."'");
		$user = $userResult->fetch_assoc();
		
		if(!empty($user))
		{
			if($mysqli->query("UPDATE users SET activated = 1 WHERE hash = '".$_REQUEST['h']."'"))
			{
				$emailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE email = '".$user['email']."'");
				$emailCount = $emailCountResult->fetch_array(MYSQLI_NUM);

				if(empty($user['organisation'])) {
				$name = $user['person'];
				} else {
					$name = $user['organisation'];
				}

				if($emailCount[0] == 0) {
					$symbols = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'n', 'm', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M');

					$hash = "";

					for($i = 0; $i < 32; $i++)
					{
						$j = rand(0, count($symbols));
						$hash .= $symbols[$j];
					}

					$mysqli->query("INSERT INTO mail (email, name, hash, in_send) VALUES ('".$user['email']."', '".$name."', '".$hash."', '1')");
				} else {
					$contactResult = $mysqli->query("SELECT * FROM mail WHERE email = '".$user['email']."'");
					$contact = $contactResult->fetch_assoc();

					if(empty($contact['name'])) {
						$mysqli->query("UPDATE mail SET name = '".$name."' WHERE email = '".$user['email']."'");
					}
				}

				$_SESSION['activation'] = 'ok';
				if(isset($_SESSION['last_page']))
				{
					header("Location: ".$_SESSION['last_page']);
				}
				else
				{
					header("Location: ../index.php");
				}
			}
			else
			{
				if(isset($_SESSION['last_page']))
				{
					header("Location: ".$_SESSION['last_page']);
				}
				else
				{
					header("Location: ../index.php");
				}
			}
		}
		else
		{
			$_SESSION['activation'] = 'hash';
			if(isset($_SESSION['last_page']))
			{
				header("Location: ".$_SESSION['last_page']);
			}
			else
			{
				header("Location: ../index.php");
			}
		}
	}

?>