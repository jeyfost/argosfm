<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['level']) and !empty($_SESSION['type']) and !empty($_SESSION['c']) and !empty($_SESSION['s']))
	{
		if(isset($_POST['subcategoryDeleteCheckbox']) and $_POST['subcategoryDeleteCheckbox'] == 1)
		{
			$goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_SESSION['s']."'");
			while($goods = $goodsResult->fetch_assoc())
			{
				if(!empty($goods['sketch']))
				{
					unlink("../../pictures/catalogue/sketch/".$goods['sketch']);
				}

				unlink("../../pictures/catalogue/small/".$goods['small']);
				unlink("../../pictures/catalogue/big/".$goods['picture']);
			}

			if($mysqli->query("DELETE FROM catalogue_new WHERE subcategory = '".$_SESSION['s']."'"))
			{
				$subcategories2Result = $mysqli->query("SELECT * FROM subcategories2 WHERE subcategory = '".$_SESSION['s']."'");

				if($subcategories2Result->num_rows != 0)
				{
					if($mysqli->query("DELETE FROM subcategories2 WHERE subcategory = '".$_SESSION['s']."'"))
					{
						if($mysqli->query("DELETE FROM subcategories_new WHERE id = '".$_SESSION['s']."'"))
						{
							$_SESSION['subcategoryDelete'] = "ok";

							header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']);
						}
						else
						{
							$_SESSION['subcategoryDelete'] = "subcategory";

							header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
						}
					}
					else
					{
						$_SESSION['subcategory2Delete'] = "subcategory2";

						header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c'])."&s=".$_SESSION['s'];
					}
				}
				else
				{
					if($mysqli->query("DELETE FROM subcategories_new WHERE id = '".$_SESSION['s']."'"))
					{
						$_SESSION['subcategoryDelete'] = "ok";

						header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
					}
					else
					{
						$_SESSION['subcategoryDelete'] = "subcategory";

						header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
					}
				}
			}
			else
			{
				$_SESSION['subcategoryDelete'] = "goods";

				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
			}
		}
		else
		{
			$subcategories2Result = $mysqli->query("SELECT * FROM subcategories2 WHERE subcategory = '".$_SESSION['s']."'");

			if($subcategories2Result->num_rows != 0)
			{
				if($mysqli->query("DELETE FROM subcategories2 WHERE subcategory = '".$_SESSION['s']."'"))
				{
					if($mysqli->query("DELETE FROM subcategories_new WHERE id = '".$_SESSION['s']."'"))
					{
						$_SESSION['subcategoryDelete'] = "ok";

						header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
					else
					{
						$_SESSION['subcategoryDelete'] = "subcategory";

						header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
					}
				}
				else
				{
					$_SESSION['subcategoryDelete'] = "subcategory2";

					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
				}
			}
			else
			{
				if($mysqli->query("DELETE FROM subcategories_new WHERE id = '".$_SESSION['s']."'"))
				{
					$_SESSION['subcategoryDelete'] = "ok";

					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
				}
				else
				{
					$_SESSION['subcategoryDelete'] = "subcategory";

					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
				}
			}
		}
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}


?>