<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(!empty($_REQUEST['id']))
		{
			$addressCountResult = mysql_query("SELECT COUNT(id) FROM mail WHERE id = '".$_REQUEST['id']."'");
			$addressCount = mysql_fetch_array($addressCountResult, MYSQL_NUM);

			if($addressCount[0] == 1)
			{
				if(mysql_query("DELETE FROM mail WHERE id = '".$_REQUEST['id']."'"))
				{
					$_SESSION['addressDelete'] = "ok";

					header("Location: ../../admin/admin.php?section=users&action=maillist&p=".$_SESSION['p']);
				}
				else
				{
					$_SESSION['addressDelete'] = "ok";

					header("Location: ../../admin/admin.php?section=users&action=maillist&p=".$_SESSION['p']);
				}
			}
			else
			{
				$_SESSION['addressDelete'] = "id";

				header("Location: ../../admin/admin.php?section=users&action=maillist&p=".$_SESSION['p']);
			}
		}
		else
		{
			$_SESSION['addressDelete'] = "empty";

			header("Location: ../../admin/admin.php?section=users&action=maillist&p=".$_SESSION['p']);
		}
	}
	else
	{
		header("Location: ../../index.php");
	}


?>