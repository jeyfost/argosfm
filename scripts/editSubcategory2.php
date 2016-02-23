<?php

	session_start();
	
	if(empty($_SESSION['id']))
	{
		header("Location: ../index.php");
	}
	
	include('connect.php');
	
	$subcategory2Result = mysql_query("SELECT * FROM subcategories2 WHERE id = '".$_SESSION['s2Id']."'");
	$subcategory2 = mysql_fetch_array($subcategory2Result, MYSQL_ASSOC);
	
	if(!empty($_POST['subcategory2Name']))
	{
		if(htmlspecialchars($_POST['subcategory2Name'], ENT_QUOTES) != $subcategory2['name'])
		{
			if(mysql_query("UPDATE subcategories2 SET name = '".htmlspecialchars($_POST['subcategory2Name'], ENT_QUOTES)."' WHERE id = '".$_SESSION['s2Id']."'"))
			{
				$_SESSION['result'] = "edit_subcategory2_success";
				
				header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
			}
			else
			{
				$_SESSION['result'] = "edit_subcategory2_failure";
				
				header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
			}
		}
		else
		{
			$_SESSION['result'] = "edit_subcategory2_actions";
				
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
		}
	}
	else
	{
		$_SESSION['result'] = "edit_subcategory2_empty";
				
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
	}
	
?>