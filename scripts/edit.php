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
	
	$title_result = mysql_query("SELECT * FROM catalogue WHERE id = '".$_SESSION['titleId']."'");
	$title = mysql_fetch_array($title_result);
	
	$pictures['big'] = 0;
	$pictures['small'] = 0;
	$pictures['sketch'] = 0;
	$text['name'] = "";
	$text['description'] = "";
	$edits = 0;
	
	if(!empty($_FILES['addFormBigPicture']['name']) and $_FILES['addFormBigPicture']['error'] == 0 and substr($_FILES['addFormBigPicture']['type'], 0, 5) == "image")
	{
		$pictures['big'] = 1;
	}
	
	if(!empty($_FILES['addFormSmallPicture']['name']) and $_FILES['addFormSmallPicture']['error'] == 0 and substr($_FILES['addFormSmallPicture']['type'], 0, 5) == "image")
	{
		$pictures['small'] = 1;
	}
	
	if(!empty($_FILES['addFormSketch']['name']) and $_FILES['addFormSketch']['error'] == 0 and substr($_FILES['addFormSketch']['type'], 0, 5) == "image")
	{
		$pictures['sketch'] = 1;
	}
	
	if(!empty($_POST['addFormNameArea']))
	{
		if($_POST['addFormNameArea'] != $title['name'])
		{
			$text['name'] = 1;
		}
	}
	
	if(!empty($_POST['addFormDescriptionArea']))
	{
		if($_POST['addFormDescriptionArea'] != str_replace("<br />", "", $title['description']))
		{
			$text['description'] = 1;
		}
	}
	
	if($pictures['big'] == 1 or $pictures['small'] == 1 or $pictures['sketch'] == 1 or $text['name'] == 1 or $text['description'] == 1)
	{
		if($pictures['big'] == 1)
		{
			//unlink('../pictures/catalogue/big/'.$title['picture']);
			
			$name = randomName();
			$uploadDir = '../pictures/catalogue/big/';
			$tmpName = $_FILES['addFormBigPicture']['tmp_name'];
			$upload = $uploadDir.$name.basename($_FILES['addFormBigPicture']['name']);
			$finalName = $name.basename($_FILES['addFormBigPicture']['name']);
			
			$bigPictureEditResult = mysql_query("UPDATE catalogue_new SET picture = '".$finalName."' WHERE id = '".$_SESSION['titleId']."'");
			$edits++;
			
			move_uploaded_file($tmpName, $upload);
			
			if($bigPictureEditResult)
			{
				$_SESSION['editBigPicture'] = "true";	
			}
			else
			{
				$_SESSION['editBigPicture'] = "false";
			}
		}
		
		if($pictures['small'] == 1)
		{
			//unlink('../pictures/catalogue/small/'.$title['small']);
			
			$name = randomName();
			$uploadDir = '../pictures/catalogue/small/';
			$tmpName = $_FILES['addFormSmallPicture']['tmp_name'];
			$upload = $uploadDir.$name.basename($_FILES['addFormSmallPicture']['name']);
			$finalName = $name.basename($_FILES['addFormSmallPicture']['name']);
			
			$smallPictureEditResult = mysql_query("UPDATE catalogue_new SET small = '".$finalName."' WHERE id = '".$_SESSION['titleId']."'");
			$edits++;
			
			move_uploaded_file($tmpName, $upload);
			
			if($smallPictureEditResult)
			{
				$_SESSION['editSmallPicture'] = "true";	
			}
			else
			{
				$_SESSION['editSmallPicture'] = "false";
			}	
		}
		
		if($pictures['sketch'] == 1)
		{
			if(!empty($title_result['sketch']))
			{
				//unlink('../pictures/catalogue/sketch/'.$title['sketch']);
			}
			
			$name = randomName();
			$uploadDir = '../pictures/catalogue/sketch/';
			$tmpName = $_FILES['addFormSketch']['tmp_name'];
			$upload = $uploadDir.$name.basename($_FILES['addFormSketch']['name']);
			$finalName = $name.basename($_FILES['addFormSketch']['name']);
			
			$sketchEditResult = mysql_query("UPDATE catalogue_new SET sketch = '".$finalName."' WHERE id = '".$_SESSION['titleId']."'");
			$edits++;
			
			move_uploaded_file($tmpName, $upload);
			
			if($sketchEditResult)
			{
				$_SESSION['editSketch'] = "true";	
			}
			else
			{
				$_SESSION['editSketch'] = "false";
			}
		}
		
		if($text['name'] == 1)
		{
			$nameEditResult = mysql_query("UPDATE catalogue_new SET name = '".$_POST['addFormNameArea']."' WHERE id = '".$_SESSION['titleId']."'");
			$edits++;
			
			if($nameEditResult)
			{
				$_SESSION['editName'] = "true";	
			}
			else
			{
				$_SESSION['editName'] = "false";
			}
		}
		
		if($text['description'] == 1)
		{
			$descriptionEditResult = mysql_query("UPDATE catalogue_new SET description = '".str_replace("\n", "<br />", $_POST['addFormDescriptionArea'])."' WHERE id = '".$_SESSION['titleId']."'");
			$edits++;
			
			if($descriptionEditResult)
			{
				$_SESSION['editDescription'] = "true";	
			}
			else
			{
				$_SESSION['editDescription'] = "false";
			}
		}
		
		if($edits != 0)
		{
			$correct = 0;
			$count = 0;
			
			if(!empty($_SESSION['editBigPicture']))
			{
				$count++;
				
				if($_SESSION['editBigPicture'] == "true")
				{
					$correct++;	
				}
			}
			
			if(!empty($_SESSION['editSmallPicture']))
			{
				$count++;
				
				if($_SESSION['editSmallPicture'] == "true")
				{
					$correct++;	
				}
			}
			
			if(!empty($_SESSION['editSketch']))
			{
				$count++;
				
				if($_SESSION['editSketch'] == "true")
				{
					$correct++;	
				}
			}
			
			if(!empty($_SESSION['editName']))
			{
				$count++;
				
				if($_SESSION['editName'] == "true")
				{
					$correct++;	
				}
			}
			
			if(!empty($_SESSION['editDescription']))
			{
				$count++;
				
				if($_SESSION['editDescription'] == "true")
				{
					$correct++;	
				}
			}
		}
		
		if($count != 0)
		{
			if($count == $correct)
			{
				$_SESSION['edited'] = "true";
				header("Location: ../admin/admin.php?mode=edit&edited=true&section=".$_SESSION['section']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);	
			}
			else
			{
				$_SESSION['edited'] = "false";
				header("Location: ../admin/admin.php?mode=edit&edited=false");
			}
		}
	}
	else
	{
		$_SESSION['edited'] = "empty";
		header("Location: ../admin/admin.php?mode=edit&section=".$_SESSION['section']."&c=".$_SESSION['category']."&s=".$_SESSION['subcategory']."&id=".$_SESSION['titleId']."&edited=false");	
	}
	
?>