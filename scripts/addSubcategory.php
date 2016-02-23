<?php

	session_start();
	
	if(empty($_SESSION['id']))
	{
		header("Location: ../index.php");
	}
	
	include('connect.php');
	
	if(!empty($_POST['subcategoryName']))
	{
		$max = 0;
		
		$maxIdResult = $mysqli->query("SELECT id FROM subcategories_new WHERE id < 1000");
		while($maxId = $maxIdResult->fetch_array(MYSQLI_NUM))
		{
			if($maxId[0] > $max)
			{
				$max = $maxId[0];
			}
		}
		
		$max++;
		
		if($addResult = $mysqli->query("INSERT INTO subcategories_new (id, type, category, name) VALUES ('".$max."', '".$_SESSION['goodsType']."', '".$_SESSION['cId']."', '".htmlspecialchars($_POST['subcategoryName'], ENT_QUOTES)."')"))
		{
			$_SESSION['result'] = "add_subcategory_success";
			
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
		}
		else
		{
			$_SESSION['result'] = "add_subcategory_failure";
			
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
		}
	}
	else
	{
		$_SESSION['result'] = "add_subcategory_name";
			
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
	}
	
?>