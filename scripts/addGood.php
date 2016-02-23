<?php

	session_start();
	
	if(empty($_SESSION['id']))
	{
		header("Location: ../index.php");
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
	
	if(!empty($_POST['goodName']))
	{
		if(!empty($_FILES['bigImage']['name']) and $_FILES['bigImage']['error'] == 0 and substr($_FILES['bigImage']['type'], 0, 5) == "image")
		{
			if(!empty($_FILES['smallImage']['name']) and $_FILES['smallImage']['error'] == 0 and substr($_FILES['smallImage']['type'], 0, 5) == "image")
			{
				if(!empty($_POST['code']))
				{
					$codeError = 0;
					$codeResult = $mysqli->query("SELECT code FROM catalogue_new");
					while($code = $codeResult->fetch_array(MYSQLI_NUM))
					{
						if($code[0] == $_POST['code'])
						$codeError++;
					}
					if($codeError == 0)
					{										
						if(!empty($_POST['description']))
						{
							if(!empty($_FILES['sketch']['name']))
							{
								if($_FILES['sketch']['error'] == 0 and substr($_FILES['sketch']['type'], 0, 5) == "image")
								{
									$description = str_replace("\n", "<br />", htmlspecialchars($_POST['description'], ENT_QUOTES));
									$bigName = randomName();
									$smallName = randomName();
									$sketchName = randomName();
									
									$bigUploadDir = '../pictures/catalogue/big/';
									$bigTmpName = $_FILES['bigImage']['tmp_name'];
									$bigUpload = $bigUploadDir.$bigName.basename($_FILES['bigImage']['name']);
									$bigFinalName = $bigName.basename($_FILES['bigImage']['name']);
									
									$smallUploadDir = '../pictures/catalogue/small/';
									$smallTmpName = $_FILES['smallImage']['tmp_name'];
									$smallUpload = $smallUploadDir.$smallName.basename($_FILES['smallImage']['name']);
									$smallFinalName = $smallName.basename($_FILES['smallImage']['name']);
									
									$sketchUploadDir = '../pictures/catalogue/sketch/';
									$sketchTmpName = $_FILES['sketch']['tmp_name'];
									$sketchUpload = $sketchUploadDir.$sketchName.basename($_FILES['sketch']['name']);
									$sketchFinalName = $sketchName.basename($_FILES['sketch']['name']);
									
									if(isset($_SESSION['categorySingle']) and $_SESSION['categorySingle'] == '1')
									{
										$subcategoryResult = $mysqli->query("SELECT id FROM subcategories_new WHERE category = '".$_SESSION['cId']."'");
										$subcategory = $subcategoryResult->fetch_array(MYSQLI_NUM);
										
										if($mysqli->query("INSERT INTO catalogue_new (type, category, subcategory, subcategory2, name, picture, small, sketch, description, code) VALUES ('".$_SESSION['goodsType']."', '".$_SESSION['cId']."', '".$subcategory[0]."', '".$_SESSION['s2Id']."', '".htmlspecialchars($_POST['goodName'], ENT_QUOTES)."', '".$bigFinalName."', '".$smallFinalName."', '".$sketchFinalName."', '".$description."', '".$_POST['code']."')"))
										{
											$goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory = '".$subcategory[0]."'");
											$goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);
	
											if($goodsCount[0] == $_POST['positionSelect'])
											{
												if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE subcategory = '".$subcategory[0]."' AND small = '".$smallFinalName."'"))
												{
													move_uploaded_file($bigTmpName, $bigUpload);
													move_uploaded_file($smallTmpName, $smallUpload);
													move_uploaded_file($sketchTmpName, $sketchUpload);
													
													$_SESSION['result'] = "add_good_success";
													header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
												}
											}
											else
											{
												for($i = $goodsCount[0]; $i >= $_POST['positionSelect']; $i--)
												{
													$priorityNew = $i + 1;
													$goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$subcategory[0]."' AND priority = '".$i."'");
													$good = $goodResult->fetch_assoc();
													$mysqli->query("UPDATE catalogue_new SET priority = '".$priorityNew."' WHERE id = '".$good['id']."'");
												}
												
												if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE subcategory = '".$subcategory[0]."' AND small = '".$smallFinalName."'"))
												{
													move_uploaded_file($bigTmpName, $bigUpload);
													move_uploaded_file($smallTmpName, $smallUpload);
													move_uploaded_file($sketchTmpName, $sketchUpload);
													
													$_SESSION['result'] = "add_good_success";
													header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
												}
											}
										}
										else
										{
											$_SESSION['result'] = "add_good_failure";
											header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
										}
									}
									
									if(isset($_SESSION['subcategorySingle']) and $_SESSION['subcategorySingle'] == '1')
									{
										/* добавление без subcategory2 */
										if($mysqli->query("INSERT INTO catalogue_new (type, category, subcategory, subcategory2, name, picture, small, sketch, description, code) VALUES ('".$_SESSION['goodsType']."', '".$_SESSION['cId']."', '".$_SESSION['sId']."', '".$_SESSION['s2Id']."', '".htmlspecialchars($_POST['goodName'], ENT_QUOTES)."', '".$bigFinalName."', '".$smallFinalName."', '".$sketchFinalName."', '".$description."', '".$_POST['code']."')"))
										{
											$goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory = '".$_SESSION['sId']."'");
											$goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);
	
											if($goodsCount[0] == $_POST['positionSelect'])
											{
												if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE subcategory = '".$_SESSION['sId']."' AND small = '".$smallFinalName."'"))
												{
													move_uploaded_file($bigTmpName, $bigUpload);
													move_uploaded_file($smallTmpName, $smallUpload);
													move_uploaded_file($sketchTmpName, $sketchUpload);
													
													$_SESSION['result'] = "add_good_success";
													header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
												}
											}
											else
											{
												for($i = $goodsCount[0]; $i >= $_POST['positionSelect']; $i--)
												{
													$priorityNew = $i + 1;
													$goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_SESSION['sId']."' AND priority = '".$i."'");
													$good = $goodResult->fetch_array(MYSQLI_ASSOC);
													$mysqli->query("UPDATE catalogue_new SET priority = '".$priorityNew."' WHERE id = '".$good['id']."'");
												}
												
												if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE subcategory = '".$_SESSION['sId']."' AND small = '".$smallFinalName."'"))
												{
													move_uploaded_file($bigTmpName, $bigUpload);
													move_uploaded_file($smallTmpName, $smallUpload);
													move_uploaded_file($sketchTmpName, $sketchUpload);
													
													$_SESSION['result'] = "add_good_success";
													header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
												}
											}
										}
										else
										{
											$_SESSION['result'] = "add_good_failure";
											header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
										}
									}
									
									if(!isset($_SESSION['categorySingle']) and !isset($_SESSION['subcategorySingle']))
									{
										/* добавление с полным набором */
										if($mysqli->query("INSERT INTO catalogue_new (type, category, subcategory, subcategory2, name, picture, small, sketch, description, code) VALUES ('".$_SESSION['goodsType']."', '".$_SESSION['cId']."', '".$_SESSION['sId']."', '".$_SESSION['s2Id']."', '".htmlspecialchars($_POST['goodName'], ENT_QUOTES)."', '".$bigFinalName."', '".$smallFinalName."', '".$sketchFinalName."', '".$description."', '".$_POST['code']."')"))
										{
											$goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2Id']."'");
											$goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);
											
											if($goodsCount[0] == $_POST['positionSelect'])
											{
												if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE subcategory2 = '".$_SESSION['s2Id']."' AND small = '".$smallFinalName."'"))
												{
													move_uploaded_file($bigTmpName, $bigUpload);
													move_uploaded_file($smallTmpName, $smallUpload);
													move_uploaded_file($sketchTmpName, $sketchUpload);
													
													$_SESSION['result'] = "add_good_success";
													header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
												}
											}
											else
											{
												for($i = $goodsCount[0]; $i >= $_POST['positionSelect']; $i--)
												{
													$priorityNew = $i + 1;
													$goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2Id']."' AND priority = '".$i."'");
													$good = $goodResult->fetch_array(MYSQLI_ASSOC);
													$mysqli->query("UPDATE catalogue_new SET priority = '".$priorityNew."' WHERE id = '".$good['id']."'");
												}
												
												if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE subcategory2 = '".$_SESSION['s2Id']."' AND small = '".$smallFinalName."'"))
												{
													move_uploaded_file($bigTmpName, $bigUpload);
													move_uploaded_file($smallTmpName, $smallUpload);
													move_uploaded_file($sketchTmpName, $sketchUpload);
													
													$_SESSION['result'] = "add_good_success";
													header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
												}
											}
										}
										else
										{
											$_SESSION['result'] = "add_good_failure";
											header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
										}
									}
								}
								else
								{
									$_SESSION['result'] = "add_good_sketch";
									header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);								
								}
							}
							else
							{
								/* без чертежа */
								$description = str_replace("\n", "<br />", htmlspecialchars($_POST['description'], ENT_QUOTES));
								$bigName = randomName();
								$smallName = randomName();
	
								$bigUploadDir = '../pictures/catalogue/big/';
								$bigTmpName = $_FILES['bigImage']['tmp_name'];
								$bigUpload = $bigUploadDir.$bigName.basename($_FILES['bigImage']['name']);
								$bigFinalName = $bigName.basename($_FILES['bigImage']['name']);
									
								$smallUploadDir = '../pictures/catalogue/small/';
								$smallTmpName = $_FILES['smallImage']['tmp_name'];
								$smallUpload = $smallUploadDir.$smallName.basename($_FILES['smallImage']['name']);
								$smallFinalName = $smallName.basename($_FILES['smallImage']['name']);
									
								if(isset($_SESSION['categorySingle']) and $_SESSION['categorySingle'] == '1')
								{
									$subcategoryResult = $mysqli->query("SELECT id FROM subcategories_new WHERE category = '".$_SESSION['cId']."'");
									$subcategory = $subcategoryResult->fetch_array(MYSQLI_NUM);
										
									if($mysqli->query("INSERT INTO catalogue_new (type, category, subcategory, subcategory2, name, picture, small, description, code) VALUES ('".$_SESSION['goodsType']."', '".$_SESSION['cId']."', '".$subcategory[0]."', '".$_SESSION['s2Id']."', '".htmlspecialchars($_POST['goodName'], ENT_QUOTES)."', '".$bigFinalName."', '".$smallFinalName."', '".$description."', '".$_POST['code']."')"))
									{
										$goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory = '".$subcategory[0]."'");
										$goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);
	
										if($goodsCount[0] == $_POST['positionSelect'])
										{
											if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE subcategory = '".$subcategory[0]."' AND small = '".$smallFinalName."'"))
											{
												move_uploaded_file($bigTmpName, $bigUpload);
												move_uploaded_file($smallTmpName, $smallUpload);
													
												$_SESSION['result'] = "add_good_success";
												header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
											}
										}
										else
										{
											for($i = $goodsCount[0]; $i >= $_POST['positionSelect']; $i--)
											{
												$priorityNew = $i + 1;
												$goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$subcategory[0]."' AND priority = '".$i."'");
												$good = $goodResult->fetch_array(MYSQLI_ASSOC);
												$mysqli->query("UPDATE catalogue_new SET priority = '".$priorityNew."' WHERE id = '".$good['id']."'");
											}
												
											if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE subcategory = '".$subcategory[0]."' AND small = '".$smallFinalName."'"))
											{
												move_uploaded_file($bigTmpName, $bigUpload);
												move_uploaded_file($smallTmpName, $smallUpload);
													
												$_SESSION['result'] = "add_good_success";
												header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
											}
										}
									}
									else
									{
										$_SESSION['result'] = "add_good_failure";
										header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']);
									}
								}
									
								if(isset($_SESSION['subcategorySingle']) and $_SESSION['subcategorySingle'] == '1')
								{
									/* добавление без subcategory2 */
									if($mysqli->query("INSERT INTO catalogue_new (type, category, subcategory, subcategory2, name, picture, small, description, code) VALUES ('".$_SESSION['goodsType']."', '".$_SESSION['cId']."', '".$_SESSION['sId']."', '".$_SESSION['s2Id']."', '".htmlspecialchars($_POST['goodName'], ENT_QUOTES)."', '".$bigFinalName."', '".$smallFinalName."', '".$description."', '".$_POST['code']."')"))
									{
										$goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory = '".$_SESSION['sId']."'");
										$goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);
										
										if($goodsCount[0] == $_POST['positionSelect'])
										{
											if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE subcategory = '".$_SESSION['sId']."' AND small = '".$smallFinalName."'"))
											{
												move_uploaded_file($bigTmpName, $bigUpload);
												move_uploaded_file($smallTmpName, $smallUpload);
													
												$_SESSION['result'] = "add_good_success";
												header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
											}
										}
										else
										{
											for($i = $goodsCount[0]; $i >= $_POST['positionSelect']; $i--)
											{
												$priorityNew = $i + 1;
												$goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_SESSION['sId']."' AND priority = '".$i."'");
												$good = $goodResult->fetch_assoc();
													$mysqli->query("UPDATE catalogue_new SET priority = '".$priorityNew."' WHERE id = '".$good['id']."'");
											}
												
											if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE subcategory = '".$_SESSION['sId']."' AND small = '".$smallFinalName."'"))
											{
												move_uploaded_file($bigTmpName, $bigUpload);
												move_uploaded_file($smallTmpName, $smallUpload);
													
												$_SESSION['result'] = "add_good_success";
												header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
											}
										}
									}
									else
									{
										$_SESSION['result'] = "add_good_failure";
										header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']);
									}
								}
									
								if(!isset($_SESSION['categorySingle']) and !isset($_SESSION['subcategorySingle']))
								{
									/* добавление с полным набором */
									if($mysqli->query("INSERT INTO catalogue_new (type, category, subcategory, subcategory2, name, picture, small, description, code) VALUES ('".$_SESSION['goodsType']."', '".$_SESSION['cId']."', '".$_SESSION['sId']."', '".$_SESSION['s2Id']."', '".htmlspecialchars($_POST['goodName'], ENT_QUOTES)."', '".$bigFinalName."', '".$smallFinalName."', '".$description."', '".$_POST['code']."')"))
									{
										$goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2Id']."'");
										$goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);
											
										if($goodsCount[0] == $_POST['positionSelect'])
										{
											if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE subcategory2 = '".$_SESSION['s2Id']."' AND small = '".$smallFinalName."'"))
											{
												move_uploaded_file($bigTmpName, $bigUpload);
												move_uploaded_file($smallTmpName, $smallUpload);
													
												$_SESSION['result'] = "add_good_success";
												header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
											}
										}
										else
										{
											for($i = $goodsCount[0]; $i >= $_POST['positionSelect']; $i--)
											{
												$priorityNew = $i + 1;
												$goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2Id']."' AND priority = '".$i."'");
												$good = $goodResult->fetch_assoc();
												$mysqli->query("UPDATE catalogue_new SET priority = '".$priorityNew."' WHERE id = '".$good['id']."'");
											}
												
											if($mysqli->query("UPDATE catalogue_new SET priority = '".$_POST['positionSelect']."' WHERE subcategory2 = '".$_SESSION['s2Id']."' AND small = '".$smallFinalName."'"))
											{
												move_uploaded_file($bigTmpName, $bigUpload);
												move_uploaded_file($smallTmpName, $smallUpload);
													
												$_SESSION['result'] = "add_good_success";
												header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
											}
										}
									}
									else
									{
										$_SESSION['result'] = "add_good_failure";
										header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
									}
								}	
							}
						}
						else
						{
							$_SESSION['result'] = "add_good_description";
							header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
						}
					}
					else
					{
						$_SESSION['result'] = "add_good_code_duplicate";
						header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
					}
				}
				else
				{
					$_SESSION['result'] = "add_good_code";
					header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
				}
			}
			else
			{
				$_SESSION['result'] = "add_good_small";
				header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
			}
		}
		else
		{
			$_SESSION['result'] = "add_good_big";
			header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&s2Id=".$_SESSION['s2Id']);
		}
	}
	else
	{
		$_SESSION['result'] = "add_good_name";
	}

?>