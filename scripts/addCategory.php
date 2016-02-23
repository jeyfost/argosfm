<?php

	session_start();
	
	if(empty($_SESSION['id']))
	{
		header("Location: ../index.php");
	}
	
	include('connect.php');
	
	if(!empty($_POST['categoryName']))
	{
		if(!empty($_FILES['categoryBlackImage']['name']) and $_FILES['categoryBlackImage']['error'] == 0 and substr($_FILES['categoryBlackImage']['type'], 0, 5) == "image")
		{
			if(!empty($_FILES['categoryRedImage']['name']) and $_FILES['categoryRedImage']['error'] == 0 and substr($_FILES['categoryRedImage']['type'], 0, 5) == "image")
			{
				$blackName = basename($_FILES['categoryBlackImage']['name']);
				$redName = basename($_FILES['categoryRedImage']['name']);
				$uploadDir = '../pictures/icons/';
				$blackTmpName = $_FILES['categoryBlackImage']['tmp_name'];
				$redTmpName = $_FILES['categoryRedImage']['tmp_name'];
				$blackUpload = $uploadDir.$blackName;
				$redUpload = $uploadDir.$redName;
				
				$max = 0;
				
				$maxIdResult = $mysqli->query("SELECT id FROM categories_new");
				while($maxId = $maxIdResult->fetch_array(MYSQLI_NUM))
				{
					if($maxId[0] > $max)
					{
						$max = $maxId[0];
					}
				}
				
				$max++;
				
				if($addResult = $mysqli->query("INSERT INTO categories_new (id, type, name, picture, picture_red) VALUES ('".$max."', '".$_SESSION['goodsType']."', '".htmlspecialchars($_POST['categoryName'], ENT_QUOTES)."', '".$blackName."', '".$redName."')"))
				{
					move_uploaded_file($blackTmpName, $blackUpload);
					move_uploaded_file($redTmpName, $redUpload);
					
					if(isset($_POST['categoryCheckbox']) and $_POST['categoryCheckbox'] == 'Yes')
					{
						$max1000 = 1000;
						$maxId1000Result = $mysqli->query("SELECT id FROM subcategories_new WHERE id >= 1000");
						while($maxId1000 = $maxId1000Result->fetch_array(MYSQLI_NUM))
						{
							if($maxId1000[0] > $max1000)
							{
								$max1000 = $maxId1000[0];
							}
						}
						
						$max1000++;
						
						if($addSResult = $mysqli->query("INSERT INTO subcategories_new (id, type, category, name) VALUES ('".$max1000."', '".$_SESSION['goodsType']."', '".$_SESSION['cId']."', '".htmlspecialchars($_POST['categoryName'], ENT_QUOTES)."')"))
						{
							$_SESSION['result'] = "add_category_empty_success";
							
							header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']);
						}
						else
						{
							$_SESSION['result'] = "add_category_empty_failure";
							
							header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']);
						}
					}
					
					$_SESSION['result'] = "add_category_success";
					
					header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']);
				}
				else
				{
					$_SESSION['result'] = "add_category_failure";
					
					header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']);
				}
			}
			else
			{
				$_SESSION['result'] = "add_category_red";
					
				header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']);
			}
		}
		else
		{
			$_SESSION['result'] = "add_category_black";
					
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']);
		}
	}
	else
	{
		$_SESSION['result'] = "add_category_name";
					
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_SESSION['goodsType']);
	}


?>