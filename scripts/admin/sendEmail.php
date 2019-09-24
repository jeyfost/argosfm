<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == '1')
	{
		if(!empty($_POST['emailTheme']))
		{
			if(!empty($_POST['emailText']))
			{
				$from = "ЧТУП Аргос-ФМ <mail@argos-fm.by>";
				$subject = stripslashes($_POST['emailTheme']);
				$reply = "mail@argos-fm.by";
				$text = stripslashes($_POST['emailText']);
				$to = $_POST['emailAddress'];

				$hash = md5(date('r', time()));

				$headers = "From: ".$from."\nReply-To: ".$reply."\nMIME-Version: 1.0";
				$headers .= "\nContent-Type: multipart/mixed; boundary = \"PHP-mixed-".$hash."\"\n\n";

				$message = "--PHP-mixed-".$hash."\n";
				$message .= "Content-Type: text/html; charset=\"windows-1251\"\n";
				$message .= "Content-Transfer-Encoding: 8bit\n\n";
				$message .= $text."\n";
				$message .= "--PHP-mixed-".$hash."\n";

				switch($_POST['emailType']) {
					case "all":
						$emailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE in_send = '1'");
						$emailCount = $emailCountResult->fetch_array(MYSQLI_NUM);

						if(!empty($_FILES['emailFile']['name']))
						{
							for($i = 0; $i < count($_FILES['emailFile']['name']); $i++)
							{
								if(!empty($_FILES['emailFile']['name'][$i]))
								{
									$attachment = chunk_split(base64_encode(file_get_contents($_FILES['emailFile']['tmp_name'][$i])));

									$message .= "Content-Type: application/octet-stream; name=".$_FILES['emailFile']['name'][$i]."\n";
									$message .= "Content-Transfer-Encoding: base64\n";
									$message .= "Content-Disposition: attachment\n\n";
									$message .= $attachment."\n";
									$message .= "--PHP-mixed-".$hash."\n";
								}
							}
						}

						$baseMessage = $message;

						$count = 0;

						$emailResult = $mysqli->query("SELECT * FROM mail WHERE in_send = '1'");
						while($email = $emailResult->fetch_assoc())
						{
							$fullMessage = $baseMessage."--PHP-mixed-".$hash."\n\n"."--------------------\n\nВесь ассортимент продукции можно посмотреть на нашем сайте: www.argos-fm.by\nА также уточнить наличие по телефону: +375 (222) 707-707 или посетить нас по адресу: Республика Беларусь, г. Могилёв, ул. Залуцкого, д.21\n\nМы всегда рады сотрудничеству с Вами!\n\nЕсли вы не хотите в дальнейшем получать эту рассылку, вы можете отписаться, перейдя по следующей ссылке: www.argos-fm.by/test/scripts/stopSending.php?hash=".$email['hash']."\n\nВНИМАНИЕ! Отписка от информационной рассылки повлечёт за собой аннулирование скидки для вашего предприятия!\n";

							$fullMessage .= "--PHP-mixed-".$hash."\n";

							if(filter_var($email['email'], FILTER_VALIDATE_EMAIL) and @mail($email['email'], $subject, $fullMessage, $headers))
							{
								$count++;
							} else {
								$mailIDResult = $mysqli->query("SELECT MAX(id) FROM mail_result");
								$mailID = $mailIDResult->fetch_array(MYSQLI_NUM);

								$failedMailID = $mailID[0] + 1;

								$mysqli->query("INSERT INTO mail_failed (mail_id, client_id) VALUES ('".$failedMailID."', '".$email['id']."')");
							}
						}

						$mysqli->query("INSERT INTO mail_result (subject, text, send_to, date, count, send) VALUES ('".$subject."', '".str_replace("\n", "<br />", htmlspecialchars($text))."', 'all', '".date('Y-m-d H:i:s')."', '".$emailCount[0]."', '".$count."')");

						if($count == $emailCount[0])
						{
							$_SESSION['sendEmail'] = "ok";

							header("Location: ../../admin/admin.php?section=users&action=mail");
						}
						else
						{
							if($count == 0)
							{
								$_SESSION['sendEmail'] = "count";

								header("Location: ../../admin/admin.php?section=users&action=mail");
							}
							else
							{
								$_SESSION['sendEmail'] = "failed";

								header("Location: ../../admin/admin.php?section=users&action=mail");
							}
						}
						break;
					case "group":
						$emailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE location = '".$_POST['addressGroupSelect']."' AND in_send = '1'");
						$emailCount = $emailCountResult->fetch_array(MYSQLI_NUM);

						if(!empty($_FILES['emailFile']['name']))
						{
							for($i = 0; $i < count($_FILES['emailFile']['name']); $i++)
							{
								if(!empty($_FILES['emailFile']['name'][$i]))
								{
									$attachment = chunk_split(base64_encode(file_get_contents($_FILES['emailFile']['tmp_name'][$i])));

									$message .= "Content-Type: application/octet-stream; name=".$_FILES['emailFile']['name'][$i]."\n";
									$message .= "Content-Transfer-Encoding: base64\n";
									$message .= "Content-Disposition: attachment\n\n";
									$message .= $attachment."\n";
									$message .= "--PHP-mixed-".$hash."\n";
								}
							}
						}

						$baseMessage = $message;

						$count = 0;

						$emailResult = $mysqli->query("SELECT * FROM mail WHERE location = '".$_POST['addressGroupSelect']."' AND in_send = '1'");
						while($email = $emailResult->fetch_assoc())
						{
							$fullMessage = $baseMessage."--PHP-mixed-".$hash."\n\n"."--------------------\n\nВесь ассортимент продукции можно посмотреть на нашем сайте: www.argos-fm.by\nА также уточнить наличие по телефону: +375 (222) 707-707 или посетить нас по адресу: Республика Беларусь, г. Могилёв, ул. Залуцкого, д.21\n\nМы всегда рады сотрудничеству с Вами!\n\nЕсли вы не хотите в дальнейшем получать эту рассылку, вы можете отписаться, перейдя по следующей ссылке: www.argos-fm.by/test/scripts/stopSending.php?hash=".$email['hash']."\n\nВНИМАНИЕ! Отписка от информационной рассылки повлечёт за собой аннулирование скидки для вашего предприятия!\n";

							$fullMessage .= "--PHP-mixed-".$hash."\n";

							if(filter_var($email['email'], FILTER_VALIDATE_EMAIL) and @mail($email['email'], $subject, $fullMessage, $headers))
							{
								$count++;
							} else {
								$mailIDResult = $mysqli->query("SELECT MAX(id) FROM mail_result");
								$mailID = $mailIDResult->fetch_array(MYSQLI_NUM);

								$failedMailID = $mailID[0] + 1;

								$mysqli->query("INSERT INTO mail_failed (mail_id, client_id) VALUES ('".$failedMailID."', '".$email['id']."')");
							}
						}

						$mysqli->query("INSERT INTO mail_result (subject, text, send_to, date, count, send) VALUES ('".$subject."', '".str_replace("\n", "<br />", htmlspecialchars($text))."', '".$_POST['addressGroupSelect']."', '".date('Y-m-d H:i:s')."', '".$emailCount[0]."', '".$count."')");

						if($count == $emailCount[0])
						{
							$_SESSION['sendEmail'] = "ok";

							header("Location: ../../admin/admin.php?section=users&action=mail");
						}
						else
						{
							if($count == 0)
							{
								$_SESSION['sendEmail'] = "count";

								header("Location: ../../admin/admin.php?section=users&action=mail");
							}
							else
							{
								$_SESSION['sendEmail'] = "failed";

								header("Location: ../../admin/admin.php?section=users&action=mail");
							}
						}
						break;
					case "one":
						if(!empty($_POST['emailAddress']))
						{
							if(filter_var($_POST['emailAddress'], FILTER_VALIDATE_EMAIL))
							{
								if(!empty($_FILES['emailFile']['name']))
								{
									for($i = 0; $i < count($_FILES['emailFile']['name']); $i++)
									{
										if(!empty($_FILES['emailFile']['name'][$i]))
										{
											$attachment = chunk_split(base64_encode(file_get_contents($_FILES['emailFile']['tmp_name'][$i])));

											$message .= "Content-Type: application/octet-stream; name=".$_FILES['emailFile']['name'][$i]."\n";
											$message .= "Content-Transfer-Encoding: base64\n";
											$message .= "Content-Disposition: attachment\n\n";
											$message .= $attachment."\n";
											$message .= "--PHP-mixed-".$hash."\n";
										}
									}
								}

								$baseMessage = $message;
								$fullMessage = $baseMessage."--PHP-mixed-".$hash."\n\n"."--------------------\n\nВесь ассортимент продукции можно посмотреть на нашем сайте: www.argos-fm.by\nА также уточнить наличие по телефону: +375 (222) 707-707 или посетить нас по адресу: Республика Беларусь, г. Могилёв, ул. Залуцкого, д.21\n\nМы всегда рады сотрудничеству с Вами!";

								if(@mail($to, $subject, $fullMessage, $headers))
								{
									$mysqli->query("INSERT INTO mail_result (subject, text, to, date, count, send) VALUES ('".htmlspecialchars($subject)."', '".str_replace("\n", "<br />", htmlspecialchars($text))."', '".htmlspecialchars($to)."', '".date('Y-m-d H:i:s')."', '1', '1')");

									$_SESSION['sendEmail'] = "ok";

									header("Location: ../../admin/admin.php?section=users&action=mail");
								}
								else
								{
									$mysqli->query("INSERT INTO mail_result (subject, text, send_to, date, count, send) VALUES ('".$subject."', '".str_replace("\n", "<br />", htmlspecialchars($text))."', '".htmlspecialchars($to)."', '".date('Y-m-d H:i:s')."', '1', '0')");

									$mailIDResult = $mysqli->query("SELECT MAX(id) FROM mail_result");
									$mailID = $mailIDResult->fetch_array(MYSQLI_NUM);

									$mysqli->query("INSERT INTO mail_failed (mail_id, client_id) VALUES ('".$mailID[0]."', '".$to."')");

									$_SESSION['sendEmail'] = "failed";

									header("Location: ../../admin/admin.php?section=users&action=mail");
								}
							}
							else
							{
								$_SESSION['sendEmail'] = "addressFormat";
								$_SESSION['emailTheme'] = $_POST['emailTheme'];
								$_SESSION['emailType'] = $_POST['emailType'];
								$_SESSION['emailText'] = $_POST['emailText'];
								$_SESSION['emailAddress'] = $_POST['emailAddress'];

								header("Location: ../../admin/admin.php?section=users&action=mail");
							}
						}
						else
						{
							$_SESSION['sendEmail'] = "address";
							$_SESSION['emailTheme'] = $_POST['emailTheme'];
							$_SESSION['emailType'] = $_POST['emailType'];
							$_SESSION['emailText'] = $_POST['emailText'];

							header("Location: ../../admin/admin.php?section=users&action=mail");
						}
						break;
					default:
						break;
				}
			}
			else
			{
				$_SESSION['sendEmail'] = "text";
				$_SESSION['emailTheme'] = $_POST['emailTheme'];
				$_SESSION['emailType'] = $_POST['emailType'];

				header("Location: ../../admin/admin.php?section=users&action=mail");
			}
		}
		else
		{
			$_SESSION['sendEmail'] = "theme";
			$_SESSION['emailText'] = $_POST['emailText'];
			$_SESSION['emailType'] = $_POST['emailType'];

			header("Location: ../../admin/admin.php?section=users&action=mail");
		}
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}