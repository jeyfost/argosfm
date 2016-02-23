<?php
	
	session_start();
	
	if(empty($_SESSION['id']))
	{
		$_SESSION['error'] = "empty";
		header("Location: ../admin/index.php?error=true");	
	}
	
	include('connect.php');
	
	function randomName()
	{
		$symbols = array('q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
									
		$name = "";
									
		for($i = 0; $i < 10; $i++)
		{
			$index = rand(0, 61);
			$name .= $symbols[$index];
		}
									
		$name .= time();
		
		return $name;
	}
	
	$errorsCount = 0;
	
	if(empty($_FILES['addFormBigPicture']['name']) or $_FILES['addFormBigPicture']['error'] != 0 or substr($_FILES['addFormBigPicture']['type'], 0, 5) != "image")
	{
		$errorsCount++;	
	}
	
	if(empty($_FILES['addFormSmallPicture']['name']) or $_FILES['addFormSmallPicture']['error'] != 0 or substr($_FILES['addFormSmallPicture']['type'], 0, 5) != "image")
	{
		$errorsCount++;	
	}
	
	if(!empty($_FILES['addFormSketch']['name']) and ($_FILES['addFormSketch']['error'] != 0 or substr($_FILES['addFormSketch']['type'], 0, 5) != "image"))
	{
		$errorsCount++;	
	}
	
	if($errorsCount == 0)
	{
		if(!empty($_POST['addFormNameArea']))
		{
			if(!empty($_POST['addFormDescriptionArea']))
			{
				$description = str_replace("\n", "<br />", $_POST['addFormDescriptionArea']);
			}
			else
			{
				$description = "";	
			}
			
			$bigName = randomName();
			$smallName = randomName();
			$sketchName = randomName();
			
			if(!empty($_FILES['addFormSketch']['name']))
			{
				$sketchUploadDir = '../pictures/catalogue/sketch/';
				$sketchTmpName = $_FILES['addFormSketch']['tmp_name'];
				$sketchUpload = $sketchUploadDir.$sketchName.basename($_FILES['addFormSketch']['name']);
				$sketchFinalName = $sketchName.basename($_FILES['addFormSketch']['name']);
			}
			else
			{
				$sketchFinalName = "";	
			}

			$bigUploadDir = '../pictures/catalogue/big/';
			$smallUploadDir = '../pictures/catalogue/small/';
			
			$bigTmpName = $_FILES['addFormBigPicture']['tmp_name'];
			$smallTmpName = $_FILES['addFormSmallPicture']['tmp_name'];
			
			$bigUpload = $bigUploadDir.$bigName.basename($_FILES['addFormBigPicture']['name']);
			$smallUpload = $smallUploadDir.$smallName.basename($_FILES['addFormSmallPicture']['name']);
			
			$bigFinalName = $bigName.basename($_FILES['addFormBigPicture']['name']);
			$smallFinalName = $smallName.basename($_FILES['addFormSmallPicture']['name']);
			
			$add_result = mysql_query("INSERT INTO catalogue_new (type, category, subcategory, name, description, picture, small, sketch) VALUES ('".$_SESSION['section']."', '".$_SESSION['category']."', '".$_SESSION['subcategory']."', '".$_POST['addFormNameArea']."', '".$description."', '".$bigFinalName."', '".$smallFinalName."', '".$sketchFinalName."')");
			
			move_uploaded_file($bigTmpName, $bigUpload);
			move_uploaded_file($smallTmpName, $smallUpload);
			
			if(!empty($_FILES['addFormSketch']['name']))
			{
				move_uploaded_file($sketchTmpName, $sketchUpload);
			}
			
			if($add_result)
			{
				$_SESSION['added'] = "true";
				header("Location: ../admin/admin.php?mode=add&added=true&section=".$_SESSION['section']."&c=".$_SESSION['c']);
			}
			else
			{
				$_SESSION['added'] = "false";
				header("Location: ../admin/admin.php?mode=add&added=false");
			}
		}
		else
		{
			$_SESSION['added'] = "false";
			header("Location: ../admin/admin.php?mode=add&added=false");
		}
	}
	else
	{
		$_SESSION['added'] = "false";
		header("Location: ../admin/admin.php?mode=add&added=false");	
	}
?>