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

	$subject = "�������������� ������ �� ����� �����-��";
	$message = "������������!<br /><br />�� ������ ����� �������� ������ �� ��������� ������ �� ����� <a href='http://argos-fm.by/'>argos-fm.by</a> .<br /><br />��� ��������� ������ ��������� �� ���������� ������: <a href='http://argos-fm.by/scripts/password.php?hash=".$hash."'>�������� ��� ������</a>.<br /><br />���� �� �� ���������� ������ �� ��������� ������, �� ������ ������ �� �����. ����� ������ ������� ��� ������.";

	$headers = "Content-type: text/html; charset=windows-1251 \r\n";
	$headers .= "From: ������������� ����� �����-�� <no-reply@argos-fm.by>\r\n";

	mail($to, $subject, $message, $headers);
}

?>