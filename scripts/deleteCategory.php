<?php

	session_start();
	
	if(empty($_SESSION['id']))
	{
		header("Location: index.php");
	}
	
	include('connect.php');
	
	if($_POST['deleteCategoryCheckbox'] == 'Yes')
	{
		echo "функция пока не реализована.";
	}
	else
	{
		if($mysqli->query("DELETE FROM categories_new WHERE id = '".$_SESSION['cId']."'"))
		{
			$_SESSION['result'] = "delete_category_success";
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESION['type']."&goodsType=".$_SESSION['goodsType']);
		}
		else
		{
			$_SESSION['result'] = "delete_category_failure";
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESION['type']."&goodsType=".$_SESSION['goodsType']);
		}
	}


?>