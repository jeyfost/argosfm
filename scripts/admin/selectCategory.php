<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['type']))
	{
		if($_POST['categorySelect'] != '')
		{
			$subcategoriesCountResult = mysql_query("SELECT COUNT(id) FROM subcategories_new WHERE category = '".$_POST['categorySelect']."'");
			$subcategoriesCount = mysql_fetch_array($subcategoriesCountResult, MYSQL_NUM);

			if($subcategoriesCount[0] > 1)
			{
				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_POST['categorySelect']);
			}
			else
			{
				$subcategoryResult = mysql_query("SELECT id FROM subcategories_new WHERE category = '".$_POST['categorySelect']."'");
				$subcategory = mysql_fetch_array($subcategoryResult, MYSQL_NUM);

				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_POST['categorySelect']."&s=".$subcategory[0]);
			}
		}
		else
		{
			header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']);
		}
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}

?>