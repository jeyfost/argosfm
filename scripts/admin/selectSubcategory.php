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
			$subcategories2CountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$_POST['subcategorySelect']."'");
			$subcategories2Count = $subcategories2CountResult->fetch_array(MYSQLI_NUM);

			if($subcategories2Count[0] > 1)
			{
				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_POST['subcategorySelect']);
			}
			else
			{
				if($subcategories2Count[0] == 1)
				{
					$subcategory2Result = $mysqli->query("SELECT id FROM subcategories2 WHERE subcategory = '".$_POST['subcategorySelect']."'");
					$subcategory2 = $subcategory2Result->fetch_array(MYSQLI_NUM);

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