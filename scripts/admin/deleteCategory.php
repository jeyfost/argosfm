<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['level']) and !empty($_SESSION['type']) and !empty($_SESSION['c']))
	{
		if(isset($_POST['subcategoryDeleteCheckbox']) and $_POST['subcategoryDeleteCheckbox'] == 1)
		{
			$goodsResult = mysql_query("SELECT * FROM catalogue_new WHERE catalogue = '".$_SESSION['c']."'");
			while($goods = mysql_fetch_assoc($goodsResult))
			{
				if(!empty($goods['sketch']))
				{
					unlink("../../pictures/catalogue/sketch/".$goods['sketch']);
				}

				unlink("../../pictures/catalogue/small/".$goods['small']);
				unlink("../../pictures/catalogue/big/".$goods['picture']);
			}

			if(mysql_query("DELETE FROM catalogue_new WHERE category = '".$_SESSION['c']."'"))
			{
				$subArray = array();

				$subcategoriesResult = mysql_query("SELECT id FROM subcategories_new WHERE category = '".$_SESSION['c']."'");
				while($subcategories = mysql_fetch_array($subcategoriesResult, MYSQL_NUM))
				{
					array_push($subArray, $subcategories[0]);
				}

				$enters = 0;
				$results = 0;

				for($i = 0; $i < count($subArray); $i++)
				{
					$subcategories2CountResult = mysql_query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$subArray[$i]."'");
					$subcategories2Count = mysql_fetch_array($subcategories2CountResult, MYSQL_NUM);
					
					if($subcategory2Count[0] != 0)
					{
						$enters++;

						if(mysql_query("DELETE FROM subcategories2 WHERE subcategory = '".$subArray[$i]."'"))
						{
							$results++;
						}
					}
				}

				if($results == $enters)
				{
					if(mysql_query("DELETE FROM subcategories_new WHERE category = '".$_SESSION['c']."'"))
					{
						$categoryResult = mysql_query("SELECT * FROM categories_new WHERE id = '".$_SESSION['c']."'");
						$category = mysql_fetch_assoc($categoryResult);

						unlink("../../pictures/icons/".$category['picture']);
						unlink("../../pictures/icons/".$category['picture_red']);

						if(mysql_query("DELETE FROM catalogue_new WHERE id = '".$_SESSION['c']."'"))
						{
							$_SESSION['categoryDelete'] = "ok";

							header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']);
						}
						else
						{
							$_SESSION['categoryDelete'] = "category";

							header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']);
						}
					}
					else
					{
						$_SESSION['categoryDelete'] = "subcategory";

						header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
				}
				else
				{
					$_SESSION['categoryDelete'] = "subcategory2";

					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']);
				}
			}
			else
			{
				$_SESSION['categoryDelete'] = "goods";

				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']);
			}
		}
		else
		{
			$subArray = array();

			$subcategoriesResult = mysql_query("SELECT id FROM subcategories_new WHERE category = '".$_SESSION['c']."'");
			while($subcategories = mysql_fetch_array($subcategoriesResult, MYSQL_NUM))
			{
				array_push($subArray, $subcategories[0]);
			}

			$enters = 0;
			$results = 0;

			for($i = 0; $i < count($subArray); $i++)
			{
				$subcategories2CountResult = mysql_query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$subArray[$i]."'");
				$subcategories2Count = mysql_fetch_array($subcategories2CountResult, MYSQL_NUM);
					
				if($subcategory2Count[0] != 0)
				{
					$enters++;

					if(mysql_query("DELETE FROM subcategories2 WHERE subcategory = '".$subArray[$i]."'"))
					{
						$results++;
					}
				}
			}

			if($results == $enters)
			{
				if(mysql_query("DELETE FROM subcategories_new WHERE category = '".$_SESSION['c']."'"))
				{
					$categoryResult = mysql_query("SELECT * FROM categories_new WHERE id = '".$_SESSION['c']."'");
					$category = mysql_fetch_assoc($categoryResult);

					unlink("../../pictures/icons/".$category['picture']);
					unlink("../../pictures/icons/".$category['picture_red']);

					if(mysql_query("DELETE FROM catalogue_new WHERE id = '".$_SESSION['c']."'"))
					{
						$_SESSION['categoryDelete'] = "ok";

						header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']);
					}
					else
					{
						$_SESSION['categoryDelete'] = "category";

						header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
				}
				else
				{
					$_SESSION['categoryDelete'] = "subcategory";

					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']);
				}
			}
			else
			{
				$_SESSION['categoryDelete'] = "subcategory2";

				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']);
			}
		}
		else
		{
			$_SESSION['categoryDelete'] = "goods";

			header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']);
		}
	}
	else
	{
		header("Location: ../../admin/admin/.php");
	}

?>