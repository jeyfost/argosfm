<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['id']))
	{
		$goodResult = mysql_query("SELECT * FROM catalogue_new WHERE id = '".$_SESSION['id']."'");
		$good = mysql_fetch_assoc($goodResult);

		if(!empty($good['sketch']))
		{
			unlink('../../pictures/sketch/'.$good['sketch']);
		}

		unlink('../../pictures/big/'.$good['picture']);
		unlink('../../pictures/small/'.$good['small']);

		if(mysql_query("DELETE FROM catalogue_new WHERE id = '".$_SESSION['id']."'"))
		{
			$_SESSION['deleteGood'] = 'ok';

			$page = "admin.php?section=goods&action=delete&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

			if(!empty($_SESSION['s2']))
			{
				$page .= "&s2=".$_SESSION['s2'];
			}

			header("Location: ../../admin/".$page);
		}
		else
		{
			$_SESSION['deleteGood'] = 'failed';

			$page = "admin.php?section=goods&action=delete&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

			if(!empty($_SESSION['s2']))
			{
				$page .= "&s2=".$_SESSION['s2'];
			}

			$page .= "&id=".$_SESSION['id'];

			header("Location: ../../admin/".$page);
		}
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}

?>