<?php

	session_start();
	
	include('connect.php');
	
	if(!empty($_POST['searchQuery']) and $_POST['searchQuery'] != iconv('utf-8', 'windows-1251', 'Поиск...') and $_POST['searchQuery'] != "Поиск...")
	{
		$quantityNameResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE name LIKE '%".$_POST['searchQuery']."%'");
		$quantityName = $quantityNameResult->fetch_array(MYSQLI_NUM);

		$quantityCodeResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE code LIKE '%".$_POST['searchQuery']."%'");
		$quantityCode = $quantityCodeResult->fetch_array(MYSQLI_NUM);
		
		$_SESSION['quantity'] = $quantityName[0] + $quantityCode[0];
		$_SESSION['query'] = $_POST['searchQuery'];

		header("Location: ../search.php?p=1");
	}
	else
	{
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}