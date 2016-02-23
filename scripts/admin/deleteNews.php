<?php

	session_start();
	
	include('../connect.php');
	
	if(empty($_SESSION['userID']))
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
		if($_SESSION['userID'] != 1)
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
	
	if(empty($_REQUEST['id']))
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
		$newsResult = mysql_query("SELECT * FROM news WHERE id = ".$_REQUEST['id']);
		if(mysql_num_rows($newsResult) == 0)
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
			$news = mysql_fetch_assoc($newsResult);
			
			if(mysql_query("DELETE FROM news WHERE id = ".$_REQUEST['id']))
			{
				$_SESSION['deleteNews'] = 'ok';
				header("Location: ../../admin/admin.php?section=users&action=news&p=".$_SESSION['p']);
			}
			else
			{
				$_SESSION['deleteNews'] = 'failed';
				header("Location: ../../admin/admin.php?section=users&action=news&p=".$_SESSION['p']);
			}
		}
	}

?>