<?php

	session_start();
	include('../connect.php');

	if(isset($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(!empty($_POST['newsHeader']))
		{
			if(!empty($_POST['newsShort']))
			{
				if(!empty($_POST['emailText']))
				{
					if(mysql_query("INSERT INTO news (header, short, text, date, date_num, date_dmy) VALUES ('".$_POST['newsHeader']."', '".$_POST['newsShort']."', '".$_POST['emailText']."', '".date('d-m-Y H:i')."', '".date('Y-m-d H:i:s')."', '".date('d-m-Y')."')"))
					{
						$_SESSION['addNews'] = "ok";
						header("Location: ../../admin/admin.php?section=users&action=news&p=1");
					}
					else
					{
						$_SESSION['newsHeader'] = $_POST['newsHeader'];
						$_SESSION['newsShort'] = $_POST['newsShort'];
						$_SESSION['newsText'] = $_POST['emailText'];

						$_SESSION['addNews'] = "failed";
						header("Location: ../../admin/admin.php?section=users&action=addNews");
					}
				}
				else
				{
					$_SESSION['newsHeader'] = $_POST['newsHeader'];
					$_SESSION['newsShort'] = $_POST['newsShort'];

					$_SESSION['addNews'] = "text";
					header("Location: ../../admin/admin.php?section=users&action=addNews");
				}
			}
			else
			{
				$_SESSION['newsHeader'] = $_POST['newsHeader'];
				$_SESSION['newsText'] = $_POST['emailText'];

				$_SESSION['addNews'] = "short";
				header("Location: ../../admin/admin.php?section=users&action=addNews");
			}
		}
		else
		{
			$_SESSION['newsShort'] = $_POST['newsShort'];
			$_SESSION['newsText'] = $_POST['emailText'];

			$_SESSION['addNews'] = "header";
			header("Location: ../../admin/admin.php?section=users&action=addNews");
		}
	}
	else
	{
		header("Location: ../index.php");
	}

?>