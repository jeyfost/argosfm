<?php

	session_start();
	include('../connect.php');
	
	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['type']) and !empty($_SESSION['c']) and !empty($_SESSION['s']) and !empty($_SESSION['s2']))
	{
		if(!empty($_POST['subcategory2Name']))
		{
			$name = htmlspecialchars($_POST['subcategory2Name'], ENT_QUOTES);

			$subcategory2Result = mysql_query("SELECT * FROM subcategories2 WHERE name = '".$name."' AND subcategory = '".$_SESSION['s']."'");

			if(mysql_num_rows($subcategory2Result) == 0)
			{
				if(mysql_query("UPDATE subcategories2 SET name = '".$name."' WHERE id = '".$_SESSION['s2']."'"))
				{
					$_SESSION['editSubcategory2'] = "ok";

					header("Location: ../../admin/admin.php?section=categories&action=edit&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
				}
				else
				{
					$_SESSION['editSubcategory2'] = "failed";
					$_SESSION['subcategory2Name'] = $_POST['subcategory2Name'];

					header("Location: ../../admin/admin.php?section=categories&action=edit&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
				}
			}
			else
			{
				$_SESSION['editSubcategory2'] = "nameExists";
				$_SESSION['subcategory2Name'] = $_POST['subcategory2Name'];

				header("Location: ../../admin/admin.php?section=categories&action=edit&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
			}			
		}
		else
		{
			$_SESSION['editSubcategory2'] = "name";
			$_SESSION['subcategory2Name'] = $_POST['subcategory2Name'];

			header("Location: ../../admin/admin.php?section=categories&action=edit&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
		}
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}

?>