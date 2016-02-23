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
			$subcategoriesCountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories_new WHERE category = '".$_POST['categorySelect']."'");
			$subcategoriesCount = $subcategoriesCountResult->fetch_array(MYSQLI_NUM);

			if($subcategoriesCount[0] > 1)
			{
				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_POST['categorySelect']);
			}
			else
			{
				$subcategoryResult = $mysqli->query("SELECT id FROM subcategories_new WHERE category = '".$_POST['categorySelect']."'");
				$subcategory = $subcategoryResult->fetch_array(MYSQLI_NUM);

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