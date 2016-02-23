<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['type']) & !empty($_SESSION['c']) and !empty($_SESSION['s']))
	{
		if(!empty($_POST['subcategory2Name']))
		{
			$name = htmlspecialchars($_POST['subcategory2Name'], ENT_QUOTES);

			$sMaxIDResult = mysql_query("SELECT MAX(id) FROM subcategories2");
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

			if(mysql_query("INSERT INTO subcategories2 (id, subcategory, name) VALUES ('".$id."', '".$_SESSION['s']."', '".$name."')"))
			{
				$_SESSION['addSubcategory2'] = "ok";

				header("Location: ../../admin/admin.php?section=categories&action=add&level=2&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
			}
			else
			{
				$_SESSION['addSubcategory2'] = "failed";

				header("Location: ../../admin/admin.php?section=categories&action=add&level=2&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
			}
		}
		else
		{
			$_SESSION['addSubcategory2'] = "name";

			header("Location: ../../admin/admin.php?section=categories&action=add&level=2&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
		}
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}

?>