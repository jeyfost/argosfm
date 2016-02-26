<?php

	session_start();
	include('connect.php');
	
	if(!empty($_POST['recovery']))
	{
		$login = stripslashes(htmlspecialchars($_POST['recovery']));
		
		$symbols = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M');
		$password = '';
		
		for($i = 0; $i < 10; $i++)
		{
			$number = mt_rand(0, count($symbols) - 1);
			$password .= $symbols[$number];
		}
		
		$userResult = $mysqli->query("SELECT * FROM users WHERE login = '".$login."'");
		$user = $userResult->fetch_assoc();
		
		if(empty($user))
		{
			$userResult = $mysqli->query("SELECT * FROM users WHERE email = '".$login."'");
			$user = $userResult->fetch_assoc();
			
			if(!empty($user))
			{
				sendMail($user['email'], $password);
				$mysqli->query("UPDATE users SET password = '".md5($password)."' WHERE id = '".$user['id']."'");
				$_SESSION['recovery'] = 'sent';
				$_SESSION['recovery_email'] = $user['email'];
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
				$_SESSION['recovery'] = 'login';
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
			sendMail($user['email'], $password);
			$mysqli->query("UPDATE users SET password = '".md5($password)."' WHERE id = '".$user['id']."'");
			$_SESSION['recovery'] = 'sent';
			$_SESSION['recovery_email'] = $user['email'];
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
		$_SESSION['recovery'] = 'empty';
		if(isset($_SESSION['last_page']))
		{
			header("Location: ".$_SESSION['last_page']);
		}
		else
		{
			header("Location: ../index.php");
		}
	}

function sendMail($address, $new_password)
{
	$to = $address;

	$subject = "Восстановление пароля на сайте Аргос-ФМ";
	$message = "Ваш пароль на сайте <a href='http://argos-fm.by/'>argos-fm.by</a> был изменён.<br />Новый пароль: <b>".$new_password."</b><br /><br />Изменить пароль можно в <a href='http://argos-fm.by/settings.php'>личном кабинете</a>, предварительно авторизовавшись на сайте.";

	$headers = "Content-type: text/html; charset=windows-1251 \r\n";
	$headers .= "From: Администрация сайта Аргос-ФМ <no-reply@argos-fm.by>\r\n";

	mail($to, $subject, $message, $headers);
}

?>