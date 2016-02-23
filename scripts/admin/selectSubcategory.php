<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['type']) and !empty($_SESSION['c']))
	{
		if($_POST['subcategorySelect'] != '')
		{
			$subcategories2CountResult = mysql_query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$_POST['subcategorySelect']."'");
			$subcategories2Count = mysql_fetch_array($subcategories2CountResult, MYSQL_NUM);

			if($subcategories2Count[0] > 1)
			{
				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_POST['subcategorySelect']);
			}
			else
			{
				if($subcategories2Count[0] == 1)
				{
					$subcategory2Result = mysql_query("SELECT id FROM subcategories2 WHERE subcategory = '".$_POST['subcategorySelect']."'");
					$subcategory2 = mysql_fetch_array($subcategory2Result, MYSQL_NUM);

					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_POST['subcategorySelect']."&s2=".$subcategory2[0]);
				}

				if($subcategories2Count[0] == 0)
				{
					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_POST['subcategorySelect']);
				}
				
			}
		}
		else
		{
			header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']);
		}
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}

?>