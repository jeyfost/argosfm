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
	
	if(!empty($_SESSION['gId']))
	{
		$goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$_SESSION['gId']."'");
		$good = $goodResult->fetch_array();
		$changes = 0;
		$errors = 0;
						
		if(!empty($_POST['goodName']))
		{
			if(htmlspecialchars($_POST['goodName'], ENT_QUOTES) != $good['name'])
			{
				if($mysqli->query("UPDATE catalogue_new SET name = '".htmlspecialchars($_POST['goodName'], ENT_QUOTES)."' WHERE id = '".$good['id']."'"))
				{
					$changes++;
				}
				else
				{
					$errors++;
				}
			}
		}
		else
		{
			$_SESSION['result'] = "edit_good_name";
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']."&gId=".$_SESSION['gId']);
		}
		
		if(!empty($_POST['code']))
		{	
			if($_POST['code'] != $good['code'])
			{
				$codeErrors = 0;
				$codeResult = $mysqli->query("SELECT code FROM catalogue_new");
				while($code = $codeResult->fetch_array(MYSQLI_NUM))
				{
					if($code[0] == $_POST['code'])
					{
						$codeErrors++;
					}
				}
				if($codeErrors == 0)
				{
					if($mysqli->query("UPDATE catalogue_new SET code = '".$_POST['code']."' WHERE id = '".$good['id']."'"))
					{
						$changes++;
					}
					else
					{
						$errors++;
					}
				}
				else
				{
					$_SESSION['result'] = "edit_good_code_duplicate";
					header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']."&gId=".$_SESSION['gId']);
				}
			}
		}
		else
		{
			$_SESSION['result'] = "edit_good_code";
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']."&gId=".$_SESSION['gId']);
		}
		
		if(!empty($_POST['description']))
		{	
			if($_POST['description'] != $good['description'])
			{
				$description = str_replace("\n", "<br />", htmlspecialchars($_POST['description'], ENT_QUOTES));
				if($mysqli->query("UPDATE catalogue_new SET description = '".$description."' WHERE id = '".$good['id']."'"))
				{
					$changes++;
				}
				else
				{
					$errors++;
				}
			}
		}
		else
		{
			$_SESSION['result'] = "edit_good_description";
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']."&gId=".$_SESSION['gId']);
		}
			
		if(!empty($_FILES['bigImage']['name']) and $_FILES['bigImage']['error'] == 0 and substr($_FILES['bigImage']['type'], 0, 5) == "image")
		{
			$bigName = randomName();
			$bigUploadDir = '../pictures/catalogue/big/';
			$bigTmpName = $_FILES['bigImage']['tmp_name'];
			$bigUpload = $bigUploadDir.$bigName.basename($_FILES['bigImage']['name']);
			$bigFinalName = $bigName.basename($_FILES['bigImage']['name']);
				
			if($mysqli->query("UPDATE catalogue_new SET picture = '".$bigFinalName."' WHERE id = '".$good['id']."'"))
			{
				move_uploaded_file($bigTmpName, $bigUpload);
				$changes++;
			}
			else
			{
				$errors++;
			}
		}
			
		if(!empty($_FILES['smallImage']['name']) and $_FILES['smallImage']['error'] == 0 and substr($_FILES['smallImage']['type'], 0, 5) == "image")
		{
			$smallName = randomName();
			$smallUploadDir = '../pictures/catalogue/small/';
			$smallTmpName = $_FILES['smallImage']['tmp_name'];
			$smallUpload = $smallUploadDir.$smallName.basename($_FILES['smallImage']['name']);
			$smallFinalName = $smallName.basename($_FILES['smallImage']['name']);
				
			if($mysqli->query("UPDATE catalogue_new SET small = '".$smallFinalName."' WHERE id = '".$good['id']."'"))
			{
				move_uploaded_file($smallTmpName, $smallUpload);
				$changes++;
			}
			else
			{
				$errors++;
			}
		}
			
		if(!empty($_FILES['sketch']['name']) and $_FILES['sketch']['error'] == 0 and substr($_FILES['sketch']['type'], 0, 5) == "image")
		{
			$sketchName = randomName();
			$sketchUploadDir = '../pictures/catalogue/sketch/';
			$sketchTmpName = $_FILES['sketch']['tmp_name'];
			$sketchUpload = $sketchUploadDir.$sketchName.basename($_FILES['sketch']['name']);
			$sketchFinalName = $sketchName.basename($_FILES['sketch']['name']);
				
			if($mysqli->query("UPDATE catalogue_new SET sketch = '".$sketchFinalName."' WHERE id = '".$good['id']."'"))
			{
				move_uploaded_file($sketchTmpName, $sketchUpload);
				$changes++;
			}
			else
			{
				$errors++;
			}
		}
			
		if($_POST['positionSelect'] != $good['priority'])
		{
			if(!empty($good['subcategory2']))
			{
				$goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory2 = '".$good['subcategory2']."'");
				$goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);
				
				if($good['priority'] < $_POST['positionSelect'])
				{
					for($i = $good['priority'] + 1; $i <= $_POST['positionSelect']; $i++)
					{
						$gResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$good['subcategory2']."' AND priority = '".$i."'");
						$g = $gResult->fetch_assoc();
							
						$mysqli->query("UPDATE catalogue_new SET priority = '".($i - 1)."' WHERE id = '".$g['id']."'");
					}

					if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE id = '".$good['id']."'"))
					{
						$changes++;
					}
					else
					{
						$errors++;
					}
				}
				else
				{
					for($i = $good['priority'] - 1; $i >= $_POST['positionSelect']; $i--)
					{
						$gResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$good['subcategory2']."' AND priority = '".$i."'");
						$g = $gResult->fetch_assoc();
							
						$mysqli->query("UPDATE catalogue_new SET priority = '".($i + 1)."' WHERE id = '".$g['id']."'");
					}

					if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE id = '".$good['id']."'"))
					{
						$changes++;
					}
					else
					{
						$errors++;
					}
				}
			}
			else
			{
				$goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory = '".$good['subcategory']."'");
				$goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);
				
				if($good['priority'] < $_POST['positionSelect'])
				{
					for($i = $good['priority'] + 1; $i <= $_POST['positionSelect']; $i++)
					{
						$gResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$good['subcategory']."' AND priority = '".$i."'");
						$g = $gResult->fetch_assooc();
							
						$mysqli->query("UPDATE catalogue_new SET priority = '".($i - 1)."' WHERE id = '".$g['id']."'");
					}

					if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE id = '".$good['id']."'"))
					{
						$changes++;
					}
					else
					{
						$errors++;
					}
				}
				else
				{
					for($i = $good['priority'] - 1; $i >= $_POST['positionSelect']; $i--)
					{
						$gResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$good['subcategory']."' AND priority = '".$i."'");
						$g = $gResult->fetch_assoc();
							
						$mysqli->query("UPDATE catalogue_new SET priority = '".($i + 1)."' WHERE id = '".$g['id']."'");
					}

					if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE id = '".$good['id']."'"))
					{
						$changes++;
					}
					else
					{
						$errors++;
					}
				}
			}
		}
		
		if($changes != 0 and $errors = 0)
		{
			$_SESSION['result'] = "edit_good_success";
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']."&gId=".$_SESSION['gId']);
		}
		
		if($changes == 0)
		{
			$_SESSION['result'] = "edit_good_actions";
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']."&gId=".$_SESSION['gId']);
		}
		
		if($errors != 0)
		{
			$_SESSION['result'] = "edit_good_failure";
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']."&gId=".$_SESSION['gId']);
		}
		
	}
	else
	{
		$_SESSION['result'] = "edit_good_id";
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
	}
	
?>