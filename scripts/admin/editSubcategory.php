<?php

	session_start();
	include('../connect.php');
	
	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['type']) and !empty($_SESSION['c']) and !empty($_SESSION['s']))
	{
		if(!empty($_POST['subcategoryName']))
		{
			$name = htmlspecialchars($_POST['subcategoryName'], ENT_QUOTES);

			$subcategoryResult = mysql_query("SELECT * FROM subcategories_new WHERE name = '".$name."' AND category = '".$_SESSION['c']."'");

			if(mysql_num_rows($subcategoryResult) == 0)
			{
				if(mysql_query("UPDATE subcategories_new SET name = '".$name."' WHERE id = '".$_SESSION['s']."'"))
				{
					$_SESSION['editSubcategory'] = "ok";

					header("Location: ../../admin/admin.php?section=categories&action=edit&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
				}
				else
				{
					$_SESSION['editSubcategory'] = "failed";
					$_SESSION['subcategoryName'] = $_POST['subcategoryName'];

					header("Location: ../../admin/admin.php?section=categories&action=edit&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
				}
			}
			else
			{
				$_SESSION['editSubcategory'] = "nameExists";
				$_SESSION['subcategoryName'] = $_POST['subcategoryName'];

				header("Location: ../../admin/admin.php?section=categories&action=edit&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
			}			
		}
		else
		{
			$_SESSION['editSubcategory'] = "name";
			$_SESSION['subcategoryName'] = $_POST['subcategoryName'];

			header("Location: ../../admin/admin.php?section=categories&action=edit&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
		}
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}

?>