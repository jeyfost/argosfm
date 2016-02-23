<?php

	session_start();
	
	include('../scripts/connect.php');
	
	if(empty($_SESSION['userID']))
	{
		header("Location: ../index.php");
	}
	
	if(!empty($_POST['newsHeader']) and !empty($_POST['newsText']) and !empty($_POST['newsDescription']))
	{
		$_SESSION['nHeader'] = $_POST['newsHeader'];
		$_SESSION['nText'] = $_POST['newsText'];
		$_SESSION['nDescription'] = $_POST['newsDescription'];
		
		if(mysql_query("INSERT INTO news (header, short, text, date) VALUES ('".htmlspecialchars($_POST['newsHeader'])."', '".htmlspecialchars($_POST['newsDescription'])."', '".$_POST['newsText']."', '".date('d-m-Y H:i')."')"))
		{
			$_SESSION['newsResult'] = 'success';
			
			unset($_SESSION['nHeader']);
			unset($_SESSION['nText']);
			unset($_SESSION['nDescription']);
			
			header("Location: ../admin/news.php");
		}
		else
		{
			$_SESSION['newsResult'] = 'failed';
			header("Location: ../admin/news.php");			
		}
	}
	else
	{
		$_SESSION['newsResult'] = 'empty';
		header("Location: ../admin/news.php");
	}

?>