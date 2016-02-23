<?php

	session_start();
	
	if(empty($_SESSION['id']))
	{
		header("Location: ../index.php");
	}
	
	include('connect.php');
	
	$subcategoryResult = $mysqli->query("SELECT * FROM subcategories_new WERE id = '".$_SESSION['sId']."'");
	$subcategory = $subcategoryResult->fetch_array();
	
	if(!empty($_POST['subcategoryName']))
	{
		if(htmlspecialchars($_POST['subcategoryName'], ENT_QUOTES) != $subcategory['name'])
		{
			if($mysqli->query("UPDATE subcategories_new SET name = '".htmlspecialchars($_POST['subcategoryName'], ENT_QUOTES)."' WHERE id = '".$_SESSION['sId']."'"))
			{
				$_SESSION['result'] = "edit_subcategory_success";
				
				header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
			}
			else
			{
				$_SESSION['result'] = "edit_subcategory_failure";
				
				header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
			}
		}
		else
		{
			$_SESSION['result'] = "edit_subcategory_actions";
				
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
		}
	}
	else
	{
		$_SESSION['result'] = "edit_subcategory_empty";
				
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
	}

?>