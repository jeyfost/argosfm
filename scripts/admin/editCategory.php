<?php

	session_start();
	include('../connect.php');
	
	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['type']) and !empty($_SESSION['c']))
	{
		if(!empty($_POST['categoryName']))
		{
			if(!empty($_FILES['categoryRedPicture']['name']) and ($_FILES['categoryRedPicture']['error'] != 0 or substr($_FILES['categoryRedPicture']['type'], 0, 5) != "image"))
			{
				$_SESSION['editCategory'] = "red";
				$_SESSION['categoryName'] = $_POST['categoryName'];

				header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
			}

			if(!empty($_FILES['categoryBlackPicture']['name']) and ($_FILES['categoryBlackPicture']['error'] != 0 or substr($_FILES['categoryBlackPicture']['type'], 0, 5) != "image"))
			{
				$_SESSION['editCategory'] = "black";
				$_SESSION['categoryName'] = $_POST['categoryName'];

				header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
			}

			$name = htmlspecialchars($_POST['categoryName'], ENT_QUOTES);
			$categoryResult = $mysqli->query("SELECT * FROM categories_new WHERE name = '".$name."'");

			$status = "";
			$state = "noChanges";

			if(MYSQLI_NUM_rows($categoryResult) == 0)
			{
				$state = "name";

				if($mysqli->query("UPDATE categories_new SET name = '".$name."' WHERE id = '".$_SESSION['c']."'"))
				{
					$status .= "a";

					$subcategoriesCountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories_new WHERE category = '".$_SESSION['c']."'");
					$subcategoriesCount = $subcategoriesCountResult->fetch_array(MYSQLI_NUM);

					if($subcategoriesCount[0] == 1)
					{
						$subcategoryResult = $mysqli->query("SELECT * FROM subcategories_new WHERE category = '".$_SESSION['c']."'");
						$subcategory = $subcategoryResult->fetch_assoc();

						if($subcategory['id'] >= 1000)
						{
							$state = "subcategory";

							if($mysqli->query("UPDATE subcategories_new SET name = '".$name."' WHERE category = '".$_SESSION['c']."'"))
							{
								$status .= "b";
							}
						}
					}
				}
			}
			else
			{
				if(empty($_FILES['categoryBlackPicture']['name']) and empty($_FILES['categoryRedPicture']['name']))
				{
					$_SESSION['editCategory'] = "nameExists";
					$_SESSION['categoryName'] = $_POST['categoryName'];

					header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
				}	
			}

			if(!empty($_FILES['categoryRedPicture']['name']) and $_FILES['categoryRedPicture']['error'] == 0 and substr($_FILES['categoryRedPicture']['type'], 0, 5) == "image")
			{
				if($state == "name")
				{
					$state = "red";
				}

				if($state == "subcategory")
				{
					$state = "subcategory+red";
				}

				$pictureResult = $mysqli->query("SELECT picture_red FROM categories_new WHERE id = '".$_SESSION['c']."'");
				$picture = $pictureResult->fetch_array(MYSQLI_NUM);

				$redName = basename($_FILES['categoryRedPicture']['name']);
				$uploadDir = "../../pictures/icons/";
				$redTmpName = $_FILES['categoryRedPicture']['tmp_name'];
				$redUpload = $uploadDir.$redName;

				if($mysqli->query("UPDATE categories_new SET picture_red = '".$redName."' WHERE id = '".$_SESSION['c']."'"))
				{
					if($picture[0] != $redName)
					{
						unlink("../../pictures/icons/".$picture[0]);
					}

					move_uploaded_file($redTmpName, $redUpload);

					$status .= "c";
				}
			}

			if(!empty($_FILES['categoryBlackPicture']['name']) and $_FILES['categoryBlackPicture']['error'] == 0 and substr($_FILES['categoryBlackPicture']['type'], 0, 5) == "image")
			{
				if($state == "name")
				{
					$state = "red";
				}

				if($state == "subcategory")
				{
					$state = "subcategory+black";
				}

				if($state == "subcategory+red")
				{
					$state == "subcategory+images";
				}

				$pictureResult = $mysqli->query("SELECT picture FROM categories_new WHERE id = '".$_SESSION['c']."'");
				$picture = $pictureResult->fetch_array(MYSQLI_NUM);

				$blackName = basename($_FILES['categoryBlackPicture']['name']);
				$uploadDir = "../../pictures/icons/";
				$blackTmpName = $_FILES['categoryBlackPicture']['tmp_name'];
				$blackUpload = $uploadDir.$blackName;

				if($mysqli->query("UPDATE categories_new SET picture = '".$blackName."' WHERE id = '".$_SESSION['c']."'"))
				{
					if($picture[0] != $blackName)
					{
						unlink("../../pictures/icons/".$picture[0]);
					}

					move_uploaded_file($blackTmpName, $blackUpload);

					$status .= "d";
				}
			}

			switch($state)
			{
				case "name":
					if($status == "a")
					{
						$_SESSION['editCategory'] = "ok";

						header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
					else
					{
						$_SESSION['editCategory'] = "failed";
						$_SESSION['categoryName'] = $_POST['categoryName'];

						header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);	
					}
					break;
				case "subcategory":
					if($status == "ab")
					{
						$_SESSION['editCategory'] = "ok";

						header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
					else
					{
						$_SESSION['editCategory'] = "subcategory";
						$_SESSION['categoryName'] = $_POST['categoryName'];

						header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
					break;
				case "subcategory+red":
					if($status == "abc")
					{
						$_SESSION['editCategory'] = "ok";

						header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
					else
					{
						switch($status)
						{
							case "a":
								$_SESSION['editCategory'] = "n+s-r-";
								$_SESSION['categoryName'] = $_POST['categoryName'];

								header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
								break;
							case "ab":
								$_SESSION['editCategory'] = "n+s+r-";
								$_SESSION['categoryName'] = $_POST['categoryName'];

								header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
								break;
							case "ac":
								$_SESSION['editCategory'] = "n+s-r+";
								$_SESSION['categoryName'] = $_POST['categoryName'];

								header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
								break;
							default:
								break;

						}
					}
					break;
				case "subcategory+black":
					if($status == "abd")
					{
						$_SESSION['editCategory'] = "ok";

						header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
					else
					{
						switch($status)
						{
							case "a":
								$_SESSION['editCategory'] = "n+s-b-";
								$_SESSION['categoryName'] = $_POST['categoryName'];

								header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
								break;
							case "ab":
								$_SESSION['editCategory'] = "n+s+b-";
								$_SESSION['categoryName'] = $_POST['categoryName'];

								header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
								break;
							case "ad":
								$_SESSION['editCategory'] = "n+s-b+";
								$_SESSION['categoryName'] = $_POST['categoryName'];

								header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
								break;
							default:
								break;

						}
					}
					break;
				case "subcategory+images":
					if($status == "abcd")
					{
						$_SESSION['editCategory'] = "ok";

						header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
					else
					{
						switch($status)
						{
							case "a":
								$_SESSION['editCategory'] = "n+s-r-b-";
								$_SESSION['categoryName'] = $_POST['categoryName'];

								header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
								break;
							case "ab":
								$_SESSION['editCategory'] = "n+s+r-b-";
								$_SESSION['categoryName'] = $_POST['categoryName'];

								header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
								break;
							case "abc":
								$_SESSION['editCategory'] = "n+s+r+b-";
								$_SESSION['categoryName'] = $_POST['categoryName'];

								header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
								break;
							case "abd":
								$_SESSION['editCategory'] = "n+s+r-b+";
								$_SESSION['categoryName'] = $_POST['categoryName'];

								header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
								break;
							case "ac":
								$_SESSION['editCategory'] = "n+s-r+b-";
								$_SESSION['categoryName'] = $_POST['categoryName'];

								header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
								break;
							case "acd":
								$_SESSION['editCategory'] = "n+s-r+b+";
								$_SESSION['categoryName'] = $_POST['categoryName'];

								header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
								break;
							case "ad":
								$_SESSION['editCategory'] = "n+s-r-b+";
								$_SESSION['categoryName'] = $_POST['categoryName'];

								header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
								break;
							default:
								break;

						}
					}
					break;
				case "red":
					if($status == "ac")
					{
						$_SESSION['editCategory'] = "ok";

						header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
					else
					{
						$_SESSION['editCategory'] = "red";
						$_SESSION['categoryName'] = $_POST['categoryName'];

						header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
					break;
				case "black":
					if($status == "ad")
					{
						$_SESSION['editCategory'] = "ok";

						header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
					else
					{
						$_SESSION['editCategory'] = "black";
						$_SESSION['categoryName'] = $_POST['categoryName'];

						header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					}
					break;
				case "noChanges":
						$_SESSION['editCategory'] = "empty";
						$_SESSION['categoryName'] = $_POST['categoryName'];

						header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
					break;
				default:
					break;
			}
		}
		else
		{
			$_SESSION['editCategory'] = "name";

			header("Location: ../../admin/admin.php?section=categories&action=edit&level=1&type=".$_SESSION['type']."&c=".$_SESSION['c']);
		}
	}
	else
	{
		header("Location: ../../admin.php");
	}

?>