<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['type']) and !empty($_SESSION['c']))
	{
		$subcategoryCheckResult = mysql_query("SELECT COUNT(id) FROM subcategories_new WHERE category = '".$_SESSION['c']."'");
		$subcategoryCheck = mysql_fetch_array($subcategoryCheckResult, MYSQL_NUM);

		if($subcategoryCheck[0] == 1)
		{
			$subcategoryIDResult = mysql_query("SELECT id FROM subcategories_new WHERE category = '".$_SESSION['c']."'");
			$subcategoryID = mysql_fetch_array($subcategoryIDResult, MYSQL_NUM);
		}
		else
		{

		}

		if(($subcategoryCheck[0] == 1 and $subcategoryID[0] < 1000) or $subcategoryCheck[0] > 1)
		{
			if(!empty($_POST['subcategoryName']))
			{
				$name = htmlspecialchars($_POST['subcategoryName'], ENT_QUOTES);

				$sMaxIDResult = mysql_query("SELECT MAX(id) FROM subcategories_new WHERE id < 1000");
				$sMaxID = mysql_fetch_array($sMaxIDResult, MYSQL_NUM);

				$id = 0;

				for($i = 1; $i < $sMaxID[0]; $i++)
				{
					$idCheckResult = mysql_query("SELECT * FROM subcategories_new WHERE id = '".$i."'");
					if(mysql_num_rows($idCheckResult) == 0)
					{
						$id = $i;
					}
				}

				if($id == 0)
				{
					$id = $sMaxID[0] + 1;
				}

				if(mysql_query("INSERT INTO subcategories_new (id, type, category, name) VALUES ('".$id."', '".$_SESSION['type']."', '".$_SESSION['c']."', '".$name."')"))
				{
					$_SESSION['addSubcategory'] = "ok";

					header("Location: ../../admin/admin.php?section=categories&action=add&level=2&type=".$_SESSION['type']."&c=".$_SESSION['c']);
				}
				else
				{
					$_SESSION['addSubcategory'] = "failed";

					header("Location: ../../admin/admin.php?section=categories&action=add&level=2&type=".$_SESSION['type']."&c=".$_SESSION['c']);
				}
			}
			else
			{
				$_SESSION['addSubcategory'] = "name";

				header("Location: ../../admin/admin.php?section=categories&action=add&level=2&type=".$_SESSION['type']."&c=".$_SESSION['c']);
			}
		}
		else
		{
			if(!empty($_POST['subcategoryName']))
			{
				$name = htmlspecialchars($_POST['subcategoryName'], ENT_QUOTES);

				$sMaxIDResult = mysql_query("SELECT MAX(id) FROM subcategories_new WHERE id < 1000");
				$sMaxID = mysql_fetch_array($sMaxIDResult, MYSQL_NUM);

				$id = 0;

				for($i = 1; $i < $sMaxID[0]; $i++)
				{
					$idCheckResult = mysql_query("SELECT * FROM subcategories_new WHERE id = '".$i."'");
					if(mysql_num_rows($idCheckResult) == 0)
					{
						$id = $i;
					}
				}

				if($id == 0)
				{
					$id = $sMaxID[0] + 1;
				}

				if(mysql_query("UPDATE subcategories_new SET id = '".$id."' WHERE id = '".$subcategoryID[0]."'"))
				{
					if(mysql_query("UPDATE catalogue_new SET subcategory = '".$id."' WHERE subcategory = '".$subcategoryID[0]."'"))
					{
						$sMaxIDResult = mysql_query("SELECT MAX(id) FROM subcategories_new WHERE id < 1000");
						$sMaxID = mysql_fetch_array($sMaxIDResult, MYSQL_NUM);

						$id = 0;

						for($i = 1; $i < $sMaxID[0]; $i++)
						{
							$idCheckResult = mysql_query("SELECT * FROM subcategories_new WHERE id = '".$i."'");
							if(mysql_num_rows($idCheckResult) == 0)
							{
								$id = $i;
							}
						}

						if($id == 0)
						{
							$id = $sMaxID[0] + 1;
						}

						if(mysql_query("INSERT INTO subcategories_new (id, type, category, name) VALUES ('".$id."', '".$_SESSION['type']."', '".$_SESSION['c']."', '".$name."')"))
						{
							$_SESSION['addSubcategory'] = "ok";

							header("Location: ../../admin/admin.php?section=categories&action=add&level=2&type=".$_SESSION['type']."&c=".$_SESSION['c']);
						}
						else
						{
							$_SESSION['addSubcategory'] = "failed";

							header("Location: ../../admin/admin.php?section=categories&action=add&level=2&type=".$_SESSION['type']."&c=".$_SESSION['c']);
						}
					}
					else
					{
						$_SESSION['addSubcategory'] = "goods";

							header("Location: ../../admin/admin.php?section=categories&action=add&level=2&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
				}
				else
				{
					$_SESSION['addSubcategory'] = "subcategoryChange";

					header("Location: ../../admin/admin.php?section=categories&action=add&level=2&type=".$_SESSION['type']."&c=".$_SESSION['c']);
				}
			}
			else
			{
				$_SESSION['addSubcategory'] = "name";

				header("Location: ../../admin/admin.php?section=categories&action=add&level=2&type=".$_SESSION['type']."&c=".$_SESSION['c']);
			}
		}	
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}

?>