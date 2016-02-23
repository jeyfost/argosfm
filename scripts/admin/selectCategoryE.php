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
				if($subcategoriesCount[0] == 1)
				{
					$subcategoryResult = $mysqli->query("SELECT id FROM subcategories_new WHERE category = '".$_POST['categorySelect']."'");
					$subcategory = $subcategoryResult->fetch_array(MYSQLI_NUM);

					$subcategories2CountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$subcategory[0]."'");
					$subcategories2Count = $subcategories2CountResult->fetch_array(MYSQLI_NUM);

					if($subcategories2Count[0] > 1)
					{
						header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_POST['categorySelect']."&s=".$subcategory[0]);
					}
					else
					{
						if($subcategories2Count[0] == 1)
						{
							$subcategory2Result = $mysqli->query("SELECT * FROM subcategories2 WHERE subcategory = '".$subcategory[0]."'");
							$subcategory2 = $subcategory2Result->fetch_array(MYSQLI_NUM);

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