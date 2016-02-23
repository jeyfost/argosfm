<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['type']) and !empty($_SESSION['c']))
	{
		$subcategoryCheckResult = $mysqli->query("SELECT COUNT(id) FROM subcategories_new WHERE category = '".$_SESSION['c']."'");
		$subcategoryCheck = $subcategoryCheckResult->fetch_array(MYSQLI_NUM);

		if($subcategoryCheck[0] == 1)
		{
			$subcategoryIDResult = $mysqli->query("SELECT id FROM subcategories_new WHERE category = '".$_SESSION['c']."'");
			$subcategoryID = $subcategoryIDResult->fetch_array(MYSQLI_NUM);
		}
		else
		{

		}

		if(($subcategoryCheck[0] == 1 and $subcategoryID[0] < 1000) or $subcategoryCheck[0] > 1)
		{
			if(!empty($_POST['subcategoryName']))
			{
				$name = htmlspecialchars($_POST['subcategoryName'], ENT_QUOTES);

				$sMaxIDResult = $mysqli->query("SELECT MAX(id) FROM subcategories_new WHERE id < 1000");
				$sMaxID = $sMaxIDResult->fetch_array(MYSQLI_NUM);

				$id = 0;

				for($i = 1; $i < $sMaxID[0]; $i++)
				{
					$idCheckResult = $mysqli->query("SELECT * FROM subcategories_new WHERE id = '".$i."'");
					if($idCheckResult->num_rows == 0)
					{
						$id = $i;
					}
				}

				if($id == 0)
				{
					$id = $sMaxID[0] + 1;
				}

				if($mysqli->query("INSERT INTO subcategories_new (id, type, category, name) VALUES ('".$id."', '".$_SESSION['type']."', '".$_SESSION['c']."', '".$name."')"))
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

				$sMaxIDResult = $mysqli->query("SELECT MAX(id) FROM subcategories_new WHERE id < 1000");
				$sMaxID = $sMaxIDResult->fetch_array(MYSQLI_NUM);

				$id = 0;

				for($i = 1; $i < $sMaxID[0]; $i++)
				{
					$idCheckResult = $mysqli->query("SELECT * FROM subcategories_new WHERE id = '".$i."'");
					if($idCheckResult->num_rows == 0)
					{
						$id = $i;
					}
				}

				if($id == 0)
				{
					$id = $sMaxID[0] + 1;
				}

				if($mysqli->query("UPDATE subcategories_new SET id = '".$id."' WHERE id = '".$subcategoryID[0]."'"))
				{
					if($mysqli->query("UPDATE catalogue_new SET subcategory = '".$id."' WHERE subcategory = '".$subcategoryID[0]."'"))
					{
						$sMaxIDResult = $mysqli->query("SELECT MAX(id) FROM subcategories_new WHERE id < 1000");
						$sMaxID = $sMaxIDResult->fetch_array(MYSQLI_NUM);

						$id = 0;

						for($i = 1; $i < $sMaxID[0]; $i++)
						{
							$idCheckResult = $mysqli->query("SELECT * FROM subcategories_new WHERE id = '".$i."'");
							if($idCheckResult->num_rows == 0)
							{
								$id = $i;
							}
						}

						if($id == 0)
						{
							$id = $sMaxID[0] + 1;
						}

						if($mysqli->query("INSERT INTO subcategories_new (id, type, category, name) VALUES ('".$id."', '".$_SESSION['type']."', '".$_SESSION['c']."', '".$name."')"))
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