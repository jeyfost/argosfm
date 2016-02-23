<?php

	session_start();
	
	if(empty($_SESSION['id']))
	{
		header("Location: index.php");
	}
	
	include('connect.php');
	
	if($mysqli->query("DELETE FROM subcategories2 WHERE id = '".$_SESSION['s2Id']."'"))
	{
		$_SESSION['result'] = "delete_subcategory2_success";
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
	}
	else
	{
		$_SESSION['result'] = "delete_subcategory2_failure";
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
	}
	
?>