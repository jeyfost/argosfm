<?php

	session_start();
	
	include('connect.php');
	
	if(!empty($_POST['searchQuery']) and $_POST['searchQuery'] != iconv ('utf-8', 'windows-1251', '�����...'))
	{
		$quantity_result = mysql_query("SELECT COUNT(id) FROM catalogue_new WHERE name LIKE '%".trim(stripslashes(htmlspecialchars($_POST['searchQuery'])))."%' AND code LIKE '%".trim(stripslashes(htmlspecialchars($_POST['searchQuery'])))."%'");
		$quantity = mysql_fetch_array($quantity_result, MYSQL_NUM);
		
		$_SESSION['quantity'] = $quantity[0];
		$_SESSION['query'] = trim(stripslashes(htmlspecialchars($_POST['searchQuery'])));
		
		header("Location: ../search.php?p=1");
	}
	else
	{
		header("Location: ../index.php");
	}

?>