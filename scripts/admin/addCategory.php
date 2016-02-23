<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['type']))
	{
		if(!empty($_POST['categoryName']))
		{
			$name = htmlspecialchars($_POST['categoryName'], ENT_QUOTES);

			$categoryResult = mysql_query("SELECT * FROM categories_new WHERE name = '".$name."'");
			
			if(mysql_num_rows($categoryResult) == 0)
			{
				if(!empty($_FILES['categoryRedPicture']) and $_FILES['categoryRedPicture']['error'] == 0 and substr($_FILES['categoryRedPicture']['type'], 0, 5) == "image")
				{
					if(!empty($_FILES['categoryBlackPicture']) and $_FILES['categoryBlackPicture']['error'] == 0 and substr($_FILES['categoryBlackPicture']['type'], 0, 5) == "image")
					{
						$blackName = basename($_FILES['categoryBlackPicture']['name']);
						$redName = basename($_FILES['categoryRedPicture']['name']);
						$uploadDir = "../../pictures/icons/";
						$blackTmpName = $_FILES['categoryBlackPicture']['tmp_name'];
						$redTmpName = $_FILES['categoryRedPicture']['tmp_name'];
						$blackUpload = $uploadDir.$blackName;
						$redUpload = $uploadDir.$redName;

						if(mysql_query("INSERT INTO categories_new (type, name, picture, picture_red) VALUES ('".$_SESSION['type']."', '".$name."', '".$blackName."', '".$redName."')"))
						{
							if(isset($_POST['categoryCheckbox']) and $_POST['categoryCheckbox'] == '1')
							{
								$max1000IDResult = mysql_query("SELECT MAX(id) FROM subcategories_new WHERE id >= 1000");
								$max1000ID = mysql_fetch_array($max1000IDResult, MYSQL_NUM);

								$sID = $max1000ID[0] + 1;

								$idResult = mysql_query("SELECT id FROM categories_new WHERE name = '".$name."'");
								$id = mysql_fetch_array($idResult, MYSQL_NUM);

								if(mysql_query("INSERT INTO subcategories_new (id, type, category, name) VALUES('".$sID."', '".$_SESSION['type']."', '".$id[0]."', '".$name."')"))
								{
									move_uploaded_file($blackTmpName, $blackUpload);
									move_uploaded_file($redTmpName, $redUpload);

									$_SESSION['addCategory'] = "ok";

									header("Location: ../../admin/admin.php?section=categories&action=add&level=1&type=".$_SESSION['type']);
								}
								else
								{
									$_SESSION['addCategory'] = "subcategory";

									header("Location: ../../admin/admin.php?section=categories&action=add&level=1&type=".$_SESSION['type']);
								}
							}
							else
							{
								move_uploaded_file($blackTmpName, $blackUpload);
								move_uploaded_file($redTmpName, $redUpload);

								$_SESSION['addCategory'] = "ok";

								header("Location: ../../admin/admin.php?section=categories&action=add&level=1&type=".$_SESSION['type']);
							}
						}
						else
						{
							$_SESSION['addCategory'] = "failed";

							header("Location: ../../admin/admin.php?section=categories&action=add&level=1&type=".$_SESSION['type']);
						}			
					}
					else
					{
						$_SESSION['addCategory'] = "blackPicture";
						$_SESSION['categoryName'] = $_POST['categoryName'];

						header("Location: ../../admin/admin.php?section=categories&action=add&level=1&type=".$_SESSION['type']);
					}
				}
				else
				{
					$_SESSION['addCategory'] = "redPicture";
					$_SESSION['categoryName'] = $_POST['categoryName'];

					header("Location: ../../admin/admin.php?section=categories&action=add&level=1&type=".$_SESSION['type']);
				}
			}
			else
			{
				$_SESSION['addCategory'] = "nameExists";
				$_SESSION['categoryName'] = $_POST['categoryName'];

				header("Location: ../../admin/admin.php?section=categories&action=add&level=1&type=".$_SESSION['type']);
			}
		}
		else
		{
			$_SESSION['addCategory'] = "name";

			header("Location: ../../admin/admin.php?section=categories&action=add&level=1&type=".$_SESSION['type']);
		}
	}
	else
	{
		header("Location: ../../admin.php");
	}

?>