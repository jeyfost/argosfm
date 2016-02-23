<?php

	session_start();
	
	if(empty($_SESSION['id']) or empty($_SESSION['gId']))
	{
		header("Location: ../index.php");	
	}
	
	include('connect.php');
	
	if($mysqli->query("DELETE FROM catalogue_new WHERE id = '".$_SESSION['gId']."'"))
	{
		$_SESSION['result'] = "delete_good_success";
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
	}
	else
	{
		$_SESSION['result'] = "delete_good_failure";
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
	}
	
?>