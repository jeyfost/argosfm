<?php

	session_start();
	include('connect.php');
	
	if(!empty($_POST['recovery']))
	{
		$login = stripslashes(htmlspecialchars($_POST['recovery']));
		
		$userResult = $mysqli->query("SELECT * FROM users WHERE login = '".$login."'");
		$user = $userResult->fetch_assoc();
		
		if(empty($user))
		{
			$userResult = $mysqli->query("SELECT * FROM users WHERE email = '".$login."'");
			$user = $userResult->fetch_assoc();
			
			if(!empty($user))
			{
				sendMail($user['email'], $user['hash']);

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
			sendMail($user['email'], $user['hash']);

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

function sendMail($address, $hash)
{
	$to = $address;

	$subject = "Восстановление пароля на сайте Аргос-ФМ";
	$message = "Здравствуйте!<br /><br />От вашего имени поступил запрос на изменение пароля на сайте <a href='http://argos-fm.by/'>argos-fm.by</a> .<br /><br />Для изменения пароля перейдите по следующему адресу: <a href='http://argos-fm.by/scripts/password.php?hash=".$hash."'>изменить ваш пароль</a>.<br /><br />Если вы не отправляли запрос на изменение пароля, то ничего делать не нужно. Можно просто удалить это письмо.";

	$headers = "Content-type: text/html; charset=windows-1251 \r\n";
	$headers .= "From: Администрация сайта Аргос-ФМ <no-reply@argos-fm.by>\r\n";

	mail($to, $subject, $message, $headers);
}

?>