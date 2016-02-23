<?php

	session_start();
	
	if(empty($_SESSION['id']))
	{
		header("Location: index.php");
	}
	
	include('connect.php');
	
	if($mysqli->query("DELETE FROM subcategories_new WHERE id = '".$_SESSION['sId']."'"))
	{
		$_SESSION['result'] = "delete_subcategory_success";
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
	}
	else
	{
		$_SESSION['result'] = "delete_subcategory_failure";
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
	}
	
?>