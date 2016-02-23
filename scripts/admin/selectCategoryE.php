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
				if($subcategoriesCount[0] == 1)
				{
					$subcategoryResult = mysql_query("SELECT id FROM subcategories_new WHERE category = '".$_POST['categorySelect']."'");
					$subcategory = mysql_fetch_array($subcategoryResult, MYSQL_NUM);

					$subcategories2CountResult = mysql_query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$subcategory[0]."'");
					$subcategories2Count = mysql_fetch_array($subcategories2CountResult, MYSQL_NUM);

					if($subcategories2Count[0] > 1)
					{
						header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_POST['categorySelect']."&s=".$subcategory[0]);
					}
					else
					{
						if($subcategories2Count[0] == 1)
						{
							$subcategory2Result = mysql_query("SELECT * FROM subcategories2 WHERE subcategory = '".$subcategory[0]."'");
							$subcategory2 = mysql_fetch_array($subcategory2Result, MYSQL_NUM);

							header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_POST['categorySelect']."&s=".$subcategory[0]."&s2=".$subcategory2[0]);
						}

						if($subcategories2Count[0] == 0)
						{
							header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_POST['categorySelect']."&s=".$subcategory[0]);
						}
					}
				}

				if($subcategoriesCount[0] == 0)
				{
					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_POST['categorySelect']."&s=".$subcategory[0]);
				}
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