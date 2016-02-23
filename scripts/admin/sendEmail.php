<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == '1')
	{
		if(!empty($_POST['emailTheme']))
		{
			if(!empty($_POST['emailText']))
			{
				if($_POST['emailType'] == "all")
				{
					$from = "���� �����-�� <mail@argos-fm.by>";
					$subject = $_POST['emailTheme'];
					$reply = "mail@argos-fm.by";
					$text = $_POST['emailText'];

					$emailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail");
					$emailCount = $emailCountResult->fetch_array(MYSQLI_NUM);

					$hash = md5(date('r', time()));

					$headers = "From: ".$from."\nReply-To: ".$reply."\nMIME-Version: 1.0";
					$headers .= "\nContent-Type: multipart/mixed; boundary = \"PHP-mixed-".$hash."\"\n\n";

					$message = "--PHP-mixed-".$hash."\n";
					$message .= "Content-Type: text/html; charset=\"windows-1251\"\n";
					$message .= "Content-Transfer-Encoding: 8bit\n\n";
					$message .= $text."\n";
					$message .= "--PHP-mixed-".$hash."\n";

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
					
					$emailResult = $mysqli->query("SELECT * FROM mail");
					while($email = $emailResult->fetch_assoc())
					{
						$fullMessage = $baseMessage."--PHP-mixed-".$hash."\n\n"."--------------------\n\n���� ����������� ��������� ����� ���������� �� ����� �����: www.argos-fm.by\n� ����� �������� ������� �� ��������: +375 (222) 707-707 ��� �������� ��� �� ������: ���������� ��������, �. ������, ��. ���������, �.21\n\n�� ������ ���� �������������� � ����!\n\n���� �� �� ������ � ���������� �������� ��� ��������, �� ������ ����������, ������� �� ��������� ������: www.argos-fm.by/test/scripts/stopSending.php?hash=".$email['hash']."\n";

						$fullMessage .= "--PHP-mixed-".$hash."\n";

						if(@mail($email['email'], $subject, $fullMessage, $headers))
						{
							$count++;
						}
					}

					if($count == $emailCount[0])
					{
						$mysqli->query("INSERT INTO mail_result (subject, text, date) VALUES ('".htmlspecialchars($subject)."', '".str_replace("\n", "<br />", htmlspecialchars($text))."', '".date('Y-m-d H:i:s')."')");

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
				}
				else
				{
					if(!empty($_POST['emailAddress']))
					{
						if(filter_var($_POST['emailAddress'], FILTER_VALIDATE_EMAIL))
						{
							$from = "���� �����-�� <mail@argos-fm.by>";
							$subject = $_POST['emailTheme'];
							$reply = "mail@argos-fm.by";
							$text = $_POST['emailText'];
							$to = $_POST['emailAddress'];

							$emailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail");
							$emailCount = $emailCountResult->fetch_array(MYSQLI_NUM);

							$hash = md5(date('r', time()));

							$headers = "From: ".$from."\nReply-To: ".$reply."\nMIME-Version: 1.0";
							$headers .= "\nContent-Type: multipart/mixed; boundary = \"PHP-mixed-".$hash."\"\n\n";

							$message = "--PHP-mixed-".$hash."\n";
							$message .= "Content-Type: text/html; charset=\"windows-1251\"\n";
							$message .= "Content-Transfer-Encoding: 8bit\n\n";
							$message .= $text."\n";
							$message .= "--PHP-mixed-".$hash."\n";

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
							$fullMessage = $baseMessage."--PHP-mixed-".$hash."\n\n"."--------------------\n\n���� ����������� ��������� ����� ���������� �� ����� �����: www.argos-fm.by\n� ����� �������� ������� �� ��������: +375 (222) 707-707 ��� �������� ��� �� ������: ���������� ��������, �. ������, ��. ���������, �.21\n\n�� ������ ���� �������������� � ����!";
							
							if(@mail($to, $subject, $fullMessage, $headers))
							{
								$_SESSION['sendEmail'] = "ok";

								header("Location: ../../admin/admin.php?section=users&action=mail");
							}
							else
							{
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