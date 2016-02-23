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
			$goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE catalogue = '".$_SESSION['c']."'");
			while($goods = $goodsResult->fetch_assoc())
			{
				if(!empty($goods['sketch']))
				{
					unlink("../../pictures/catalogue/sketch/".$goods['sketch']);
				}

				unlink("../../pictures/catalogue/small/".$goods['small']);
				unlink("../../pictures/catalogue/big/".$goods['picture']);
			}

			if($mysqli->query("DELETE FROM catalogue_new WHERE category = '".$_SESSION['c']."'"))
			{
				$subArray = array();

				$subcategoriesResult = $mysqli->query("SELECT id FROM subcategories_new WHERE category = '".$_SESSION['c']."'");
				while($subcategories = $subcategoriesResult->fetch_array(MYSQLI_NUM))
				{
					array_push($subArray, $subcategories[0]);
				}

				$enters = 0;
				$results = 0;

				for($i = 0; $i < count($subArray); $i++)
				{
					$subcategories2CountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$subArray[$i]."'");
					$subcategories2Count = $subcategories2CountResult->fetch_array(MYSQLI_NUM);
					
					if($subcategory2Count[0] != 0)
					{
						$enters++;

						if($mysqli->query("DELETE FROM subcategories2 WHERE subcategory = '".$subArray[$i]."'"))
						{
							$results++;
						}
					}
				}

				if($results == $enters)
				{
					if($mysqli->query("DELETE FROM subcategories_new WHERE category = '".$_SESSION['c']."'"))
					{
						$categoryResult = $mysqli->query("SELECT * FROM categories_new WHERE id = '".$_SESSION['c']."'");
						$category = $categoryResult->fetch_assoc();

						unlink("../../pictures/icons/".$category['picture']);
						unlink("../../pictures/icons/".$category['picture_red']);

						if($mysqli->query("DELETE FROM catalogue_new WHERE id = '".$_SESSION['c']."'"))
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

			$subcategoriesResult = $mysqli->query("SELECT id FROM subcategories_new WHERE category = '".$_SESSION['c']."'");
			while($subcategories = $subcategoriesResult->fetch_array(MYSQLI_NUM))
			{
				array_push($subArray, $subcategories[0]);
			}

			$enters = 0;
			$results = 0;

			for($i = 0; $i < count($subArray); $i++)
			{
				$subcategories2CountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$subArray[$i]."'");
				$subcategories2Count = $subcategories2CountResult->fetch_array(MYSQLI_NUM);
					
				if($subcategory2Count[0] != 0)
				{
					$enters++;

					if($mysqli->query("DELETE FROM subcategories2 WHERE subcategory = '".$subArray[$i]."'"))
					{
						$results++;
					}
				}
			}

			if($results == $enters)
			{
				if($mysqli->query("DELETE FROM subcategories_new WHERE category = '".$_SESSION['c']."'"))
				{
					$categoryResult = $mysqli->query("SELECT * FROM categories_new WHERE id = '".$_SESSION['c']."'");
					$category = $categoryResultz->fetch_assoc();

					unlink("../../pictures/icons/".$category['picture']);
					unlink("../../pictures/icons/".$category['picture_red']);

					if($mysqli->query("DELETE FROM catalogue_new WHERE id = '".$_SESSION['c']."'"))
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
	}
	else
	{
		header("Location: ../../admin/admin/.php");
	}

?>