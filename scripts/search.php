<?php

	session_start();
	
	include('connect.php');
	
	if(!empty($_POST['searchQuery']) and $_POST['searchQuery'] != iconv('utf-8', 'windows-1251', '�����...'))
	{
		$quantity_result = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE name LIKE '%".$_POST['searchQuery']."%' AND code LIKE '%".$_POST['searchQuery']."%'");
		$quantity = $quantity_result->fetch_array(MYSQLI_NUM);
		
		$_SESSION['quantity'] = $quantity[0];
		$_SESSION['query'] = $_POST['searchQuery'];
		
		header("Location: ../search.php?p=1");
	}
	else
	{
		header("Location: ../index.php");
	}

?>