<?php

	session_start();
	
	if(empty($_SESSION['id']))
	{
		header("Location: ../index.php");
	}
	
	include('connect.php');
	
	$categoryResult = $mysqli->query("SELECT * FROM categories_new WHERE id = '".$_SESSION['cId']."'");
	$category = $categoryResult->fetch_assoc();
	
	$errors = 0;
	$actions = 0;
	
	if(!empty($_POST['categoryName']))
	{
		if($category['name'] != htmlspecialchars($_POST['categoryName'], ENT_QUOTES))
		{
			if(!$mysqli->query("UPDATE categories_new SET name = '".htmlspecialchars($_POST['categoryName'], ENT_QUOTES)."' WHERE id = '".$_SESSION['cId']."'"))
			{
				$errors++;
			}
			
			$actions++;
		}
	}
	
	if(!empty($_FILES['categoryBlackImage']) and $_FILES['categoryBlackImage']['error'] == 0 and substr($_FILES['categoryBlackImage']['type'], 0, 5) == "image")
	{
		$name = basename($_FILES['categoryBlackImage']['name']);
		$uploadDir = '../pictures/icons/';
		$tmpName = $_FILES['categoryBlackImage']['tmp_name'];
		$upload = $uploadDir.$name;
		
		if(!$mysqli->query("UPDATE categories_new SET picture = '".$name."' WHERE id = '".$_SESSION['cId']."'"))
		{
			$errors++;
		}
		else
		{
			move_uploaded_file($tmpName, $upload);
			$actions++;
		}
	}
	
	if(!empty($_FILES['categoryRedImage']) and $_FILES['categoryRedImage']['error'] == 0 and substr($_FILES['categoryRedImage']['type'], 0, 5) == "image")
	{
		$name = basename($_FILES['categoryRedImage']['name']);
		$uploadDir = '../pictures/icons/';
		$tmpName = $_FILES['categoryRedImage']['tmp_name'];
		$upload = $uploadDir.$name;
		
		if(!$mysqli->query("UPDATE categories_new SET picture_red = '".$name."' WHERE id = '".$_SESSION['cId']."'"))
		{
			$errors++;
		}
		else
		{
			move_uploaded_file($tmpName, $upload);
			$actions++;
		}
	}
	
	if($actions != 0)
	{
		if($errors == 0)
		{
			$_SESSION['result'] = "edit_category_success";
			
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
		}
		else
		{
			$_SESSION['result'] = "edit_category_failure";
			
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
		}
	}
	else
	{
		$_SESSION['result'] = "edit_category_actions";
			
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
	}


?>