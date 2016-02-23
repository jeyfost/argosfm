<?php

	session_start();
	include('../connect.php');
	include('preview.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_POST['goodName']))
	{
		if(!empty($_FILES['goodPhoto']['name']) and $_FILES['goodPhoto']['error'] == 0 and substr($_FILES['goodPhoto']['type'], 0, 5) == 'image')
		{
			if(!empty($_POST['goodCode']) and $_POST['goodCode'] > 0)
			{
				if(!empty($_POST['goodPrice']) and $_POST['goodPrice'] > 0)
				{
					if(!empty($_POST['goodPosition']) and $_POST['goodPosition'] > 0)
					{
						if(!empty($_POST['goodDescription']))
						{

							$name = htmlspecialchars($_POST['goodName'], ENT_QUOTES);
							$description = str_replace("\n", "<br />", htmlspecialchars($_POST['goodDescription'], ENT_QUOTES));

							$bigPhotoName = randomName();
							$smallPhotoName = randomName();

							$bigPhotoUploadDir = '../../pictures/catalogue/big/';
							$bigPhotoTmpName = $_FILES['goodPhoto']['tmp_name'];
							$bigPhotoUpload = $bigPhotoUploadDir.$bigPhotoName.basename($_FILES['goodPhoto']['name']);
							$bigPhotoFinalName = $bigPhotoName.basename($_FILES['goodPhoto']['name']);

							$smallPhootoUploadDir = '../../pictures/catalogue/small/';
							$smallPhotoTmpName = $_FILES['goodPhoto']['tmp_name'];
							$smallPhootoUpload = $smallPhootoUploadDir.$smallPhotoName.basename($_FILES['goodPhoto']['name']);
							$smallPhotoFinalName = $smallPhotoName.basename($_FILES['goodPhoto']['name']);

							if(!empty($_FILES['goodSketch']['name']))
							{
								///////////////////////////////////////////
								////         add good with sketch       ///
								///////////////////////////////////////////

								if($_FILES['goodSketch']['error'] == 0 and substr($_FILES['goodSketch']['type'], 0, 5) == 'image')
								{
									$sketchName = randomName();

									$sketchUploadDir = '../../pictures/catalogue/sketch/';
									$sketchTmpName = $_FILES['goodSketch']['tmp_name'];
									$sketchUpload = $sketchUploadDir.$sketchName.basename($_FILES['goodSketch']['name']);
									$sketchFinalName = $sketchName.basename($_FILES['sketchName']['name']);

									if(isset($_SESSION['s2']))
									{
										$goodCodeResult = mysql_query("SELECT COUNT(id) FROM catalogue_new WHERE code = '".$_POST['goodCode']."'");
										$goodCode = mysql_fetch_array($goodCodeResult, MYSQL_NUM);

										if($goodCode[0] != 0)
										{
											$_SESSION['addGood'] = 'codeExists';

											$_SESSION['goodName'] = $_POST['goodName'];
											$_SESSION['goodPrice'] = $_POST['goodPrice'];
											$_SESSION['goodPosition'] = $_POST['goodPosition'];
											$_SESSION['goodDescription'] = $_POST['goodDescription'];

											header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
										}
										else
										{
											if(mysql_query("INSERT INTO catalogue_new (type, category, subcategory, subcategory2, name, picture, small, sketch, description, priority, price, code) VALUES ('".$_SESSION['type']."', '".$_SESSION['c']."', '".$_SESSION['s']."', '".$_SESSION['s2']."', '".$name."', '".$bigPhotoFinalName."', '".$smallPhotoFinalName."', '".$sketchFinalName."', '".$description."', '".$_POST['goodPosition']."', '".$_POST['goodPrice']."', '".$_POST['goodCode']."')"))
											{
												image_resize($smallPhotoTmpName, $smallPhootoUpload, 100, 100);
												move_uploaded_file($bigPhotoTmpName, $bigPhotoUpload);
												move_uploaded_file($smallPhotoTmpName, $smallPhotoUpload);
												move_uploaded_file($sketchTmpName, $sketchUpload);

												$maxPriorityResult = mysql_query("SELECT MAX(priority) FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2']."'");
												$maxPriority = mysql_fetch_array($maxPriorityResult, MYSQL_NUM);

												$goodsCountResult = mysql_query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2']."'");
												$goodsCount = mysql_fetch_array($goodsCountResult, MYSQL_NUM);

												$goodsResult = mysql_query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2']."' ORDER BY priority");
												while($goods = mysql_fetch_assoc($goodsResult))
												{
													if($goodsCount[0] > $maxPriority[0])
													{
														$lastGoodIDResult = mysql_query("SELECT MAX(id) FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2']."'");
														$lastGoodID = mysql_fetch_array($lastGoodIDResult, MYSQL_NUM);

														$lastGoodResult = mysql_query("SELECT * FROM catalogue_new WHERE id = '".$lastGoodID[0]."'");
														$lastGood = mysql_fetch_assoc($lastGoodResult);

														if($goods['priority'] >= $lastGood['priority'] and $goods['id'] != $lastGood['id'])
														{
															$newPriority = $goods['priority'] + 1;
															mysql_query("UPDATE catalogue_new SET priority = '".$newPriority."' WHERE id = '".$goods['id']."'");
														}
													}
												}

												$_SESSION['addGood'] = 'ok';

												header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
											}
											else
											{
												$_SESSION['addGood'] = 'failed';

												$_SESSION['goodName'] = $_POST['goodName'];
												$_SESSION['goodCode'] = $_POST['goodCode'];
												$_SESSION['goodPrice'] = $_POST['goodPrice'];
												$_SESSION['goodPosition'] = $_POST['goodPosition'];
												$_SESSION['goodDescription'] = $_POST['goodDescription'];

												header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
											}
										}
									}
									else
									{
										$goodCodeResult = mysql_query("SELECT COUNT(id) FROM catalogue_new WHERE code = '".$_POST['goodCode']."'");
										$goodCode = mysql_fetch_array($goodCodeResult, MYSQL_NUM);

										if($goodCode[0] != 0)
										{
											$_SESSION['addGood'] = 'codeExists';

											$_SESSION['goodName'] = $_POST['goodName'];
											$_SESSION['goodPrice'] = $_POST['goodPrice'];
											$_SESSION['goodPosition'] = $_POST['goodPosition'];
											$_SESSION['goodDescription'] = $_POST['goodDescription'];

											header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
										}
										else
										{
											if(mysql_query("INSERT INTO catalogue_new (type, category, subcategory, name, picture, small, sketch, description, priority, price, code) VALUES ('".$_SESSION['type']."', '".$_SESSION['c']."', '".$_SESSION['s']."', '".$name."', '".$bigPhotoFinalName."', '".$smallPhotoFinalName."', '".$sketchFinalName."', '".$description."', '".$_POST['goodPosition']."', '".$_POST['goodPrice']."', '".$_POST['goodCode']."')"))
											{
												image_resize($smallPhotoTmpName, $smallPhootoUpload, 100, 100);
												move_uploaded_file($bigPhotoTmpName, $bigPhotoUpload);
												move_uploaded_file($smallPhotoTmpName, $smallPhotoUpload);
												move_uploaded_file($sketchTmpName, $sketchUpload);

												$maxPriorityResult = mysql_query("SELECT MAX(priority) FROM catalogue_new WHERE subcategory = '".$_SESSION['s']."'");
												$maxPriority = mysql_fetch_array($maxPriorityResult, MYSQL_NUM);

												$goodsCountResult = mysql_query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory = '".$_SESSION['s']."'");
												$goodsCount = mysql_fetch_array($goodsCountResult, MYSQL_NUM);

												$goodsResult = mysql_query("SELECT * FROM catalogue_new WHERE subcategory = '".$_SESSION['s']."' ORDER BY priority");
												while($goods = mysql_fetch_assoc($goodsResult))
												{
													if($goodsCount[0] > $maxPriority[0])
													{
														$lastGoodIDResult = mysql_query("SELECT MAX(id) FROM catalogue_new WHERE subcategory = '".$_SESSION['s']."'");
														$lastGoodID = mysql_fetch_array($lastGoodIDResult, MYSQL_NUM);

														$lastGoodResult = mysql_query("SELECT * FROM catalogue_new WHERE id = '".$lastGoodID[0]."'");
														$lastGood = mysql_fetch_assoc($lastGoodResult);

														if($goods['priority'] >= $lastGood['priority'] and $goods['id'] != $lastGood['id'])
														{
															$newPriority = $goods['priority'] + 1;
															mysql_query("UPDATE catalogue_new SET priority = '".$newPriority."' WHERE id = '".$goods['id']."'");
														}
													}
												}

												$_SESSION['addGood'] = 'ok';
												
												header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
											}
											else
											{
												$_SESSION['addGood'] = 'failed';

												$_SESSION['goodName'] = $_POST['goodName'];
												$_SESSION['goodCode'] = $_POST['goodCode'];
												$_SESSION['goodPrice'] = $_POST['goodPrice'];
												$_SESSION['goodPosition'] = $_POST['goodPosition'];
												$_SESSION['goodDescription'] = $_POST['goodDescription'];

												header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
											}
										}
									}
								}
								else
								{
									$_SESSION['addGood'] = 'sketch';

									$_SESSION['goodName'] = $_POST['goodName'];
									$_SESSION['goodCode'] = $_POST['goodCode'];
									$_SESSION['goodPrice'] = $_POST['goodPrice'];
									$_SESSION['goodPosition'] = $_POST['goodPosition'];
									$_SESSION['goodDescription'] = $_POST['goodDescription'];

									if(isset($_SESSION['s2']))
									{
										header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
									}
									else
									{
										header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
									}
								}
							}
							else
							{
								///////////////////////////////////////////
								////       add good without sketch      ///
								///////////////////////////////////////////

								if(isset($_SESSION['s2']))
								{
									$goodCodeResult = mysql_query("SELECT COUNT(id) FROM catalogue_new WHERE code = '".$_POST['goodCode']."'");
									$goodCode = mysql_fetch_array($goodCodeResult, MYSQL_NUM);

									if($goodCode[0] != 0)
									{
										$_SESSION['addGood'] = 'codeExists';

										$_SESSION['goodName'] = $_POST['goodName'];
										$_SESSION['goodPrice'] = $_POST['goodPrice'];
										$_SESSION['goodPosition'] = $_POST['goodPosition'];
										$_SESSION['goodDescription'] = $_POST['goodDescription'];

										header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
									}
									else
									{
										if(mysql_query("INSERT INTO catalogue_new (type, category, subcategory, subcategory2, name, picture, small, description, priority, price, code) VALUES ('".$_SESSION['type']."', '".$_SESSION['c']."', '".$_SESSION['s']."', '".$_SESSION['s2']."', '".$name."', '".$bigPhotoFinalName."', '".$smallPhotoFinalName."', '".$description."', '".$_POST['goodPosition']."', '".$_POST['goodPrice']."', '".$_POST['goodCode']."')"))
										{
											image_resize($smallPhotoTmpName, $smallPhootoUpload, 100, 100);
											move_uploaded_file($bigPhotoTmpName, $bigPhotoUpload);
											move_uploaded_file($smallPhotoTmpName, $smallPhotoUpload);

											$maxPriorityResult = mysql_query("SELECT MAX(priority) FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2']."'");
											$maxPriority = mysql_fetch_array($maxPriorityResult, MYSQL_NUM);

											$goodsCountResult = mysql_query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2']."'");
											$goodsCount = mysql_fetch_array($goodsCountResult, MYSQL_NUM);

											$goodsResult = mysql_query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2']."' ORDER BY priority");
											while($goods = mysql_fetch_assoc($goodsResult))
											{
												if($goodsCount[0] > $maxPriority[0])
												{
													$lastGoodIDResult = mysql_query("SELECT MAX(id) FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2']."'");
													$lastGoodID = mysql_fetch_array($lastGoodIDResult, MYSQL_NUM);

													$lastGoodResult = mysql_query("SELECT * FROM catalogue_new WHERE id = '".$lastGoodID[0]."'");
													$lastGood = mysql_fetch_assoc($lastGoodResult);

													if($goods['priority'] >= $lastGood['priority'] and $goods['id'] != $lastGood['id'])
													{
														$newPriority = $goods['priority'] + 1;
														mysql_query("UPDATE catalogue_new SET priority = '".$newPriority."' WHERE id = '".$goods['id']."'");
													}
												}
											}

											$_SESSION['addGood'] = 'ok';

											header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
										}
										else
										{
											$_SESSION['addGood'] = 'failed';

											$_SESSION['goodName'] = $_POST['goodName'];
											$_SESSION['goodCode'] = $_POST['goodCode'];
											$_SESSION['goodPrice'] = $_POST['goodPrice'];
											$_SESSION['goodPosition'] = $_POST['goodPosition'];
											$_SESSION['goodDescription'] = $_POST['goodDescription'];

											header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
										}
									}
								}
								else
								{
									$goodCodeResult = mysql_query("SELECT COUNT(id) FROM catalogue_new WHERE code = '".$_POST['goodCode']."'");
									$goodCode = mysql_fetch_array($goodCodeResult, MYSQL_NUM);

									if($goodCode[0] != 0)
									{
										$_SESSION['addGood'] = 'codeExists';

										$_SESSION['goodName'] = $_POST['goodName'];
										$_SESSION['goodPrice'] = $_POST['goodPrice'];
										$_SESSION['goodPosition'] = $_POST['goodPosition'];
										$_SESSION['goodDescription'] = $_POST['goodDescription'];

										header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
									}
									else
									{
										if(mysql_query("INSERT INTO catalogue_new (type, category, subcategory, name, picture, small, description, priority, price, code) VALUES ('".$_SESSION['type']."', '".$_SESSION['c']."', '".$_SESSION['s']."', '".$name."', '".$bigPhotoFinalName."', '".$smallPhotoFinalName."', '".$description."', '".$_POST['goodPosition']."', '".$_POST['goodPrice']."', '".$_POST['goodCode']."')"))
										{
											image_resize($smallPhotoTmpName, $smallPhootoUpload, 100, 100);
											move_uploaded_file($bigPhotoTmpName, $bigPhotoUpload);
											move_uploaded_file($smallPhotoTmpName, $smallPhotoUpload);

											$maxPriorityResult = mysql_query("SELECT MAX(priority) FROM catalogue_new WHERE subcategory = '".$_SESSION['s']."'");
											$maxPriority = mysql_fetch_array($maxPriorityResult, MYSQL_NUM);

											$goodsCountResult = mysql_query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory = '".$_SESSION['s']."'");
											$goodsCount = mysql_fetch_array($goodsCountResult, MYSQL_NUM);

											$goodsResult = mysql_query("SELECT * FROM catalogue_new WHERE subcategory = '".$_SESSION['s']."' ORDER BY priority");
											while($goods = mysql_fetch_assoc($goodsResult))
											{
												if($goodsCount[0] > $maxPriority[0])
												{
													$lastGoodIDResult = mysql_query("SELECT MAX(id) FROM catalogue_new WHERE subcategory = '".$_SESSION['s']."'");
													$lastGoodID = mysql_fetch_array($lastGoodIDResult, MYSQL_NUM);

													$lastGoodResult = mysql_query("SELECT * FROM catalogue_new WHERE id = '".$lastGoodID[0]."'");
													$lastGood = mysql_fetch_assoc($lastGoodResult);

													if($goods['priority'] >= $lastGood['priority'] and $goods['id'] != $lastGood['id'])
													{
														$newPriority = $goods['priority'] + 1;
														mysql_query("UPDATE catalogue_new SET priority = '".$newPriority."' WHERE id = '".$goods['id']."'");
													}
												}
											}

											$_SESSION['addGood'] = 'ok';
											
											header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
										}
										else
										{
											$_SESSION['addGood'] = 'failed';

											$_SESSION['goodName'] = $_POST['goodName'];
											$_SESSION['goodCode'] = $_POST['goodCode'];
											$_SESSION['goodPrice'] = $_POST['goodPrice'];
											$_SESSION['goodPosition'] = $_POST['goodPosition'];
											$_SESSION['goodDescription'] = $_POST['goodDescription'];

											header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
										}
									}
								}
							}						
						}
						else
						{
							$_SESSION['addGood'] = 'description';

							$_SESSION['goodName'] = $_POST['goodName'];
							$_SESSION['goodCode'] = $_POST['goodCode'];
							$_SESSION['goodPrice'] = $_POST['goodPrice'];
							$_SESSION['goodPosition'] = $_POST['goodPosition'];

							if(isset($_SESSION['s2']))
							{
								header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
							}
							else
							{
								header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
							}
						}
					}
					else
					{
						$_SESSION['addGood'] = 'position';

						$_SESSION['goodName'] = $_POST['goodName'];
						$_SESSION['goodCode'] = $_POST['goodCode'];
						$_SESSION['goodPrice'] = $_POST['goodPrice'];
						$_SESSION['goodDescription'] = $_POST['goodDescription'];

						if(isset($_SESSION['s2']))
						{
							header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
						}
						else
						{
							header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
						}
					}
				}
				else
				{
					$_SESSION['addGood'] = 'price';

					$_SESSION['goodName'] = $_POST['goodName'];
					$_SESSION['goodCode'] = $_POST['goodCode'];
					$_SESSION['goodPosition'] = $_POST['goodPosition'];
					$_SESSION['goodDescription'] = $_POST['goodDescription'];

					if(isset($_SESSION['s2']))
					{
						header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
					}
					else
					{
						header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
					}
				}
			}
			else
			{
				$_SESSION['addGood'] = 'code';

				$_SESSION['goodName'] = $_POST['goodName'];
				$_SESSION['goodPrice'] = $_POST['goodPrice'];
				$_SESSION['goodPosition'] = $_POST['goodPosition'];
				$_SESSION['goodDescription'] = $_POST['goodDescription'];

				if(isset($_SESSION['s2']))
				{
					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
				}
				else
				{
					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
				}
			}
		}
		else
		{
			$_SESSION['addGood'] = 'photo';

			$_SESSION['goodName'] = $_POST['goodName'];
			$_SESSION['goodCode'] = $_POST['goodCode'];
			$_SESSION['goodPrice'] = $_POST['goodPrice'];
			$_SESSION['goodPosition'] = $_POST['goodPosition'];
			$_SESSION['goodDescription'] = $_POST['goodDescription'];

			if(isset($_SESSION['s2']))
			{
				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
			}
			else
			{
				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
			}
		}
	}
	else
	{
		$_SESSION['addGood'] = 'name';

		$_SESSION['goodCode'] = $_POST['goodCode'];
		$_SESSION['goodPrice'] = $_POST['goodPrice'];
		$_SESSION['goodPosition'] = $_POST['goodPosition'];
		$_SESSION['goodDescription'] = $_POST['goodDescription'];

		if(isset($_SESSION['s2']))
		{
			header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
		}
		else
		{
			header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
		}
		
	}

?>