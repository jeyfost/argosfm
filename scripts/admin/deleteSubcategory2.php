<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['level']) and !empty($_SESSION['type']) and !empty($_SESSION['c']) and !empty($_SESSION['s']) and !empty($_SESSION['s2']))
	{
		if(isset($_POST['subcategory2DeleteCheckbox']) and $_POST['subcategory2DeleteCheckbox'] == 1)
		{
			$goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2']."'");
			while($goods = $goodsResult->fetch_assoc())
			{
				if(!empty($goods['sketch']))
				{
					unlink("../../pictures/catalogue/sketch/".$goods['sketch']);
				}

				unlink("../../pictures/catalogue/small/".$goods['small']);
				unlink("../../pictures/catalogue/big/".$goods['picture']);
			}

			if($mysqli->query("DELETE FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2']."'"))
			{
				if($mysqli->query("DELETE FROM subcategories2 WHERE id = '".$_SESSION['s2']."'"))
				{
					$_SESSION['subcategory2Delete'] = "ok";

					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
				}
				else
				{
					$_SESSION['subcategory2Delete'] = "subcategory2";

					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
				}
			}
			else
			{
				$_SESSION['subcategory2Delete'] = "goods";

				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
			}
		}
		else
		{
			if($mysqli->query("DELETE FROM subcategories2 WHERE id = '".$_SESSION['s2']."'"))
			{
				$_SESSION['subcategory2Delete'] = "ok";

				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
			}
			else
			{
				$_SESSION['subcategory2Delete'] = "subcategory2";

				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
			}
		}
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}

?>