<?php

	session_start();

	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(empty($_POST['userLogin']) or empty($_POST['userEmail']) or empty($_POST['userPerson']) or empty($_POST['userPhone']))
		{
			if(!empty($_POST['userLogin']))
			{
				$_SESSION['userLogin'] = $_POST['userLogin'];
			}

			if(!empty($_POST['userPassword']))
			{
				$_SESSION['userPassword'] = $_POST['userPassword'];
			}

			if(!empty($_POST['userEmail']))
			{
				$_SESSION['userEmail'] = $_POST['userEmail'];
			}

			if(!empty($_POST['userPerson']))
			{
				$_SESSION['userPerson'] = $_POST['userPerson'];
			}

			if(!empty($_POST['userPhone']))
			{
				$_SESSION['userPhone'] = $_POST['userPhone'];
			}

			if(!empty($_POST['userLoginReason']))
			{
				$_SESSION['userLoginReason'] = $_POST['userLoginReason'];
			}

			if(!empty($_POST['userPasswordReason']))
			{
				$_SESSION['userPasswordReason'] = $_POST['userPasswordReason'];
			}

			if(!empty($_POST['userEmailReason']))
			{
				$_SESSION['userEmailReason'] = $_POST['userEmailReason'];
			}

			if(!empty($_POST['userPersonReason']))
			{
				$_SESSION['userPersonReason'] = $_POST['userPersonReason'];
			}

			if(!empty($_POST['userPhoneReason']))
			{
				$_SESSION['userPhoneReason'] = $_POST['userPhoneReason'];
			}

			$_SESSION['editUser'] = "empty";
			header("Location: ../../admin/admin.php?section=users&action=users&user=".$_SESSION['user']);
		}

		if(!empty($_POST['userEmail']) and !filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL))
		{
			if(!empty($_POST['userLogin']))
			{
				$_SESSION['userLogin'] = $_POST['userLogin'];
			}

			if(!empty($_POST['userPassword']))
			{
				$_SESSION['userPassword'] = $_POST['userPassword'];
			}

			if(!empty($_POST['userEmail']))
			{
				$_SESSION['userEmail'] = $_POST['userEmail'];
			}

			if(!empty($_POST['userPerson']))
			{
				$_SESSION['userPerson'] = $_POST['userPerson'];
			}

			if(!empty($_POST['userPhone']))
			{
				$_SESSION['userPhone'] = $_POST['userPhone'];
			}

			if(!empty($_POST['userLoginReason']))
			{
				$_SESSION['userLoginReason'] = $_POST['userLoginReason'];
			}

			if(!empty($_POST['userPasswordReason']))
			{
				$_SESSION['userPasswordReason'] = $_POST['userPasswordReason'];
			}

			if(!empty($_POST['userEmailReason']))
			{
				$_SESSION['userEmailReason'] = $_POST['userEmailReason'];
			}

			if(!empty($_POST['userPersonReason']))
			{
				$_SESSION['userPersonReason'] = $_POST['userPersonReason'];
			}

			if(!empty($_POST['userPhoneReason']))
			{
				$_SESSION['userPhoneReason'] = $_POST['userPhoneReason'];
			}

			$_SESSION['editUser'] = "emailValidate";
			header("Location: ../../admin/admin.php?section=users&action=users&user=".$_SESSION['user']);
		}

		$userResult = $mysqli->query("SELECT * FROM users WHERE id = '".$_SESSION['user']."'");
		$user = $userResult->fetch_assoc();

		$count = 0;
		$emailChanged = 0;

		$from = "ЧТУП Аргос-ФМ <mail@argos-fm.by>";
		$subject = "Изменение личных данных на сайте Аргос-ФМ";
		$reply = "mail@argos-fm.by";

		$text = "Здравствуйте!<br /><br />Ваши личные данные на сайте Аргос-ФМ были исправлены администратором. Какие именно данные были изменены и причину их изменения вы можете узнать ниже.<br /><br />";

		$hash = md5(date('r', time()));

		$headers = "From: ".$from."\nReply-To: ".$reply."\nMIME-Version: 1.0";
		$headers .= "\nContent-Type: multipart/mixed; boundary = \"PHP-mixed-".$hash."\"\n\n";

		$message = "--PHP-mixed-".$hash."\n";
		$message .= "Content-Type: text/html; charset=\"windows-1251\"\n";
		$message .= "Content-Transfer-Encoding: 8bit\n\n";
		$message .= $text."\n\n";

		if(!empty($_POST['userLogin']) and !empty($_POST['userLoginReason']))
		{
			$login = htmlspecialchars($_POST['userLogin'], ENT_QUOTES);

			if($mysqli->query("UPDATE users SET login = '".$login."' WHERE id = '".$_SESSION['user']."'"))
			{
				$message .= "<b>Ваш логин был изменён.<b/><br />Старый логин: <b>".$user['login']."</b><br />Новый логин: <b>".$login."</b><br /><br />Причина изменения: <b>".htmlspecialchars($_POST['userLoginReason'], ENT_QUOTES)."</b>\n\n";
				$count++;

			}
		}

		if(!empty($_POST['userPassword']) and !empty($_POST['userPasswordReason']))
		{
			if($mysqli->query("UPDATE users SET password = '".md5(md5($_POST['userPassword']))."' WHERE id = '".$_SESSION['user']."'"))
			{
				$message .= "<b>Ваш пароль был изменён.<b/><br />Новый пароль: <b>".$_POST['userPassword']."</b><br /><br />Причина изменения: <b>".htmlspecialchars($_POST['userPasswordReason'], ENT_QUOTES)."</b><br />Текущий пароль вы можете изменить в <a href='http://test1.ru/argosfm/settings.php?s=2'>личном кабинете</a>.\n\n";
				$count++;
			}
		}

		if(!empty($_POST['userEmail']) and !empty($_POST['userEmailReason']))
		{
			if($mysqli->query("UPDATE users SET email = '".$_POST['userEmail']."' WHERE id = '".$_SESSION['user']."'"))
			{
				$message .= "<b>Ваш e-mail был изменён.<b/><br />Новый e-mail: <b>".$_POST['userEmail']."</b><br /><br />Причина изменения: <b>".htmlspecialchars($_POST['userEmailReason'], ENT_QUOTES)."</b>\n\n";
				$count++;
				$emailChanged = 1;
			}
		}

		if(!empty($_POST['userPerson']) and !empty($_POST['userPersonReason']))
		{
			if($mysqli->query("UPDATE users SET person = '".$_POST['userPerson']."' WHERE id = '".$_SESSION['user']."'"))
			{
				$message .= "<b>Контактное лицо было изменено.<b/><br />Старое контактное лицо: <b>".$user['person']."</b><br />Новое контактное лицо: <b>".$_POST['userPerson']."</b><br /><br />Причина изменения: <b>".htmlspecialchars($_POST['userPersonReason'], ENT_QUOTES)."</b>\n\n";
				$count++;
			}
		}

		if(!empty($_POST['userPhone']) and !empty($_POST['userPhoneReason']))
		{
			if($mysqli->query("UPDATE users SET phone = '".$_POST['userPhone']."' WHERE id = '".$_SESSION['user']."'"))
			{
				$message .= "<b>Контактный телефон был изменён.<b/><br />Старый номер: <b>".$user['phone']."</b><br />Новый номер: <b>".$_POST['userPhone']."</b><br /><br />Причина изменения: <b>".htmlspecialchars($_POST['userPhoneReason'], ENT_QUOTES)."</b>\n\n";
				$count++;
			}
		}

		if($count != 0)
		{
			$message .= "--PHP-mixed-".$hash."\n";

			if($emailChanged == 0)
			{
				if(@mail($user['email'], $subject, $message, $headers))
				{
					$_SESSION['editUser'] = "ok";
					header("Location: ../../admin/admin.php?section=users&action=users&user=".$_SESSION['user']);
				}
				else
				{
					$_SESSION['editUser'] = "notification";
					header("Location: ../../admin/admin.php?section=users&action=users&user=".$_SESSION['user']);
				}
			}

			if($emailChanged == 1)
			{
				if(@mail($_POST['userEmail'], $subject, $message, $headers))
				{
					$_SESSION['editUser'] = "ok";
					header("Location: ../../admin/admin.php?section=users&action=users&user=".$_SESSION['user']);
				}
				else
				{
					$_SESSION['editUser'] = "notification";
					header("Location: ../../admin/admin.php?section=users&action=users&user=".$_SESSION['user']);
				}
			}
		}
		else
		{
			$_SESSION['editUser'] = "noChanges";
			header("Location: ../../admin/admin.php?section=users&action=users&user=".$_SESSION['user']);
		}
	}
	else
	{
		header("Location: ../../index.php");
	}