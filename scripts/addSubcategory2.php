<?php

	session_start();
	
	if(empty($_SESSION['id']))
	{
		header("Location: ../index.php");
	}
	
	include('connect.php');
	
	if(!empty($_POST['subcategory2Name']))
	{
		$max = 0;
		
		$maxIdResult = $mysqli->query("SELECT id FROM subcategories2");
		while($maxId = $maxIdResult->fetch_array(MYSQLI_NUM))
		{
			if($maxId[0] > $max)
			{
				$max = $maxId[0];
			}
		}
		
		$max++;
		
		if($addResult = $mysqli->query("INSERT INTO subcategories2 (id, subcategory, name) VALUES ('".$max."', '".$_SESSION['sId']."', '".htmlspecialchars($_POST['subcategory2Name'], ENT_QUOTES)."')"))
		{
			$_SESSION['result'] = "add_subcategory2_success";
			
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
		}
		else
		{
			$_SESSION['result'] = "add_subcategory2_failure";
			
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
		}
	}
	else
	{
		$_SESSION['result'] = "add_subcategory2_name";
			
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
	}
	
?>