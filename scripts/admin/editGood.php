<?php

	session_start();
	include('../connect.php');
	include('preview.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['id']))
	{
		$goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$_SESSION['id']."'");
		if(MYSQLI_NUM_rows($goodResult) > 0)
		{
			if(!empty($_POST['goodName']))
			{
				if(!empty($_POST['goodCode']) and $_POST['goodCode'] > 0)
				{
					if(!empty($_POST['goodPrice']) and $_POST['goodPrice'] > 0)
					{
						if(!empty($_POST['goodPosition']))
						{
							if(!empty($_POST['goodDescription']))
							{
								$codeCheckResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE code = '".$_POST['code']."'");
								$codeCheck = $codeCheckResult->fetch_array(MYSQLI_NUM);

								if($codeCheck[0] == 0)
								{
									$name = htmlspecialchars($_POST['goodName'], ENT_QUOTES);
									$description = str_replace("\n", "<br />", htmlspecialchars($_POST['goodDescription'], ENT_QUOTES));

									$gp = 0;
									$gs = 0;

									if(!empty($_FILES['goodPhoto']['name']))
									{
										$gp = 1;
									}

									if(!empty($_FILES['goodSketch']['name']))
									{
										$gs = 1;
									}

									if($mysqli->query("UPDATE catalogue_new SET name = '".$name."', description = '".$description."', priority = '".$_POST['goodPosition']."', price = '".$_POST['goodPrice']."', code = '".$_POST['goodCode']."' WHERE id = '".$_SESSION['id']."'"))
									{
										$good = $goodResult->fetch_array();

										if($good['priority'] != $_POST['goodPosition'])
										{
											if(!empty($_SESSION['s2']))
											{
												if($good['priority'] >= $_POST['goodPosition'])
												{
													$goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2']."' AND priority <= '".$good['priority']."' AND priority >= '".$_POST['goodPosition']."' ORDER BY priority");
												}
												else
												{
													$goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_SESSION['s2']."' AND priority <= '".$_POST['goodPosition']."' AND priority >= '".$good['priority']."' ORDER BY priority");
												}
												
											}
											else
											{
												if($good['priority'] >= $_POST['goodPosition'])
												{
													$goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_SESSION['s']."' AND priority <= '".$good['priority']."' AND priority >= '".$_POST['goodPosition']."' ORDER BY priority");
												}
												else
												{
													$goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_SESSION['s']."' AND priority <= '".$_POST['goodPosition']."' AND priority >= '".$good['priority']."' ORDER BY priority");
												}
											}

											while($goods = $goodsResult->fetch_assoc())
											{
												if($goods['id'] != $good['id'])
												{
													$newPriority = $goods['priority'] + 1;
													$mysqli->query("UPDATE catalogue_new SET priority = '".$newPriority."' WHERE id = '".$goods['id']."'");
												}
											}
										}

										if($gp == 0 and $gs == 0)
										{
											$_SESSION['editGood'] = 'ok';

											$_SESSION['goodName'] = $_POST['goodName'];
											$_SESSION['goodPrice'] = $_POST['goodPrice'];
											$_SESSION['goodPosition'] = $_POST['goodPosition'];
											$_SESSION['goodDescription'] = $_POST['goodDescription'];
											$_SESSION['goodCode'] = $_POST['goodCode'];

											$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

											if(!empty($_SESSION['s2']))
											{
												$page .= "&s2=".$_SESSION['s2'];
											}

											$page .= "&id=".$_SESSION['id'];

											header("Location: ../../admin/".$page);
										}
										else
										{
											if($gp == 1)
											{
												if($_FILES['goodPhoto']['error'] == 0 and substr($_FILES['goodPhoto']['type'], 0, 5) == "image")
												{
													$bigPhotoName = randomName();
													$bigPhotoUploadDir = '../../pictures/catalogue/big/';
													$bigPhotoTmpName = $_FILES['goodPhoto']['tmp_name'];
													$bigPhotoUpload = $bigPhotoUploadDir.$bigPhotoName.basename($_FILES['goodPhoto']['name']);
													$bigPhotoFinalName = $bigPhotoName.basename($_FILES['goodPhoto']['name']);

													$smallPhotoName = randomName();
													$smallPhootoUploadDir = '../../pictures/catalogue/small/';
													$smallPhotoTmpName = $_FILES['goodPhoto']['tmp_name'];
													$smallPhootoUpload = $smallPhootoUploadDir.$smallPhotoName.basename($_FILES['goodPhoto']['name']);
													$smallPhotoFinalName = $smallPhotoName.basename($_FILES['goodPhoto']['name']);

													if($mysqli->query("UPDATE catalogue_new SET picture = '".$bigPhotoFinalName."', small = '".$smallPhotoFinalName."' WHERE id = '".$_SESSION['id']."'"))
													{
														unlink("../../pictures/catalogue/big/".$good['picture']);
														unlink("../../pictures/catalogue/small/".$good['small']);

														image_resize($smallPhotoTmpName, $smallPhootoUpload, 100, 100);
														move_uploaded_file($bigPhotoTmpName, $bigPhotoUpload);
														move_uploaded_file($smallPhotoTmpName, $smallPhotoUpload);

														if($gs == 0)
														{
															$_SESSION['editGood'] = 'ok';

															$_SESSION['goodName'] = $_POST['goodName'];
															$_SESSION['goodPrice'] = $_POST['goodPrice'];
															$_SESSION['goodPosition'] = $_POST['goodPosition'];
															$_SESSION['goodDescription'] = $_POST['goodDescription'];
															$_SESSION['goodCode'] = $_POST['goodCode'];

															$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

															if(!empty($_SESSION['s2']))
															{
																$page .= "&s2=".$_SESSION['s2'];
															}

															$page .= "&id=".$_SESSION['id'];

															header("Location: ../../admin/".$page);
														}
													}
													else
													{
														$_SESSION['editGood'] = 'failed';

														$_SESSION['goodName'] = $_POST['goodName'];
														$_SESSION['goodPrice'] = $_POST['goodPrice'];
														$_SESSION['goodPosition'] = $_POST['goodPosition'];
														$_SESSION['goodDescription'] = $_POST['goodDescription'];
														$_SESSION['goodCode'] = $_POST['goodCode'];

														$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

														if(!empty($_SESSION['s2']))
														{
															$page .= "&s2=".$_SESSION['s2'];
														}

														$page .= "&id=".$_SESSION['id'];

														header("Location: ../../admin/".$page);
													}
												}
												else
												{
													$_SESSION['editGood'] = 'photo';

													$_SESSION['goodName'] = $_POST['goodName'];
													$_SESSION['goodPrice'] = $_POST['goodPrice'];
													$_SESSION['goodPosition'] = $_POST['goodPosition'];
													$_SESSION['goodDescription'] = $_POST['goodDescription'];
													$_SESSION['goodCode'] = $_POST['goodCode'];

													$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

													if(!empty($_SESSION['s2']))
													{
														$page .= "&s2=".$_SESSION['s2'];
													}

													$page .= "&id=".$_SESSION['id'];

													header("Location: ../../admin/".$page);
												}
											}

											if($gs == 1)
											{
												if($_FILES['goodSketch']['error'] == 0 and substr($_FILES['goodSketch']['type'], 0, 5) == "image")
												{
													$sketchName = randomName();
													$sketchUploadDir = '../../pictures/catalogue/sketch/';
													$sketchTmpName = $_FILES['goodSketch']['tmp_name'];
													$sketchUpload = $sketchUploadDir.$sketchName.basename($_FILES['goodSketch']['name']);
													$sketchFinalName = $sketchName.basename($_FILES['sketchName']['name']);

													if($mysqli->query("UPDATE catalogue_new SET sketch = '".$sketchFinalName."' WHERE id = '".$_SESSION['id']."'"))
													{
														if(!empty($good['sketch']))
														{
															unlink("../../pictures/catalogue/sketch/".$good['sketch']);
														}

														move_uploaded_file($sketchTmpName, $sketchUpload);

														$_SESSION['editGood'] = 'ok';

														$_SESSION['goodName'] = $_POST['goodName'];
														$_SESSION['goodPrice'] = $_POST['goodPrice'];
														$_SESSION['goodPosition'] = $_POST['goodPosition'];
														$_SESSION['goodDescription'] = $_POST['goodDescription'];
														$_SESSION['goodCode'] = $_POST['goodCode'];

														$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

														if(!empty($_SESSION['s2']))
														{
															$page .= "&s2=".$_SESSION['s2'];
														}

														$page .= "&id=".$_SESSION['id'];

														header("Location: ../../admin/".$page);
													}
													else
													{
														$_SESSION['editGood'] = 'failed';

														$_SESSION['goodName'] = $_POST['goodName'];
														$_SESSION['goodPrice'] = $_POST['goodPrice'];
														$_SESSION['goodPosition'] = $_POST['goodPosition'];
														$_SESSION['goodDescription'] = $_POST['goodDescription'];
														$_SESSION['goodCode'] = $_POST['goodCode'];

														$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

														if(!empty($_SESSION['s2']))
														{
															$page .= "&s2=".$_SESSION['s2'];
														}

														$page .= "&id=".$_SESSION['id'];

														header("Location: ../../admin/".$page);
													}
												}
												else
												{
													$_SESSION['editGood'] = 'sketch';

													$_SESSION['goodName'] = $_POST['goodName'];
													$_SESSION['goodPrice'] = $_POST['goodPrice'];
													$_SESSION['goodPosition'] = $_POST['goodPosition'];
													$_SESSION['goodDescription'] = $_POST['goodDescription'];
													$_SESSION['goodCode'] = $_POST['goodCode'];

													$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

													if(!empty($_SESSION['s2']))
													{
														$page .= "&s2=".$_SESSION['s2'];
													}

													$page .= "&id=".$_SESSION['id'];

													header("Location: ../../admin/".$page);
												}
											}
										}
									}
									else
									{
										$_SESSION['editGood'] = 'failed';

										$_SESSION['goodName'] = $_POST['goodName'];
										$_SESSION['goodPrice'] = $_POST['goodPrice'];
										$_SESSION['goodPosition'] = $_POST['goodPosition'];
										$_SESSION['goodDescription'] = $_POST['goodDescription'];
										$_SESSION['goodCode'] = $_POST['goodCode'];

										$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

										if(!empty($_SESSION['s2']))
										{
											$page .= "&s2=".$_SESSION['s2'];
										}

										$page .= "&id=".$_SESSION['id'];

										header("Location: ../../admin/".$page);
									}
								}
								else
								{
									$_SESSION['editGood'] = 'codeExists';

									$_SESSION['goodName'] = $_POST['goodName'];
									$_SESSION['goodPrice'] = $_POST['goodPrice'];
									$_SESSION['goodPosition'] = $_POST['goodPosition'];
									$_SESSION['goodDescription'] = $_POST['goodDescription'];
									$_SESSION['goodCode'] = $_POST['goodCode'];

									$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

									if(!empty($_SESSION['s2']))
									{
										$page .= "&s2=".$_SESSION['s2'];
									}

									$page .= "&id=".$_SESSION['id'];

									header("Location: ../../admin/".$page);
								}
							}
							else
							{
								$_SESSION['editGood'] = 'description';

								$_SESSION['goodName'] = $_POST['goodName'];
								$_SESSION['goodPrice'] = $_POST['goodPrice'];
								$_SESSION['goodPosition'] = $_POST['goodPosition'];
								$_SESSION['goodCode'] = $_POST['goodCode'];

								$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

								if(!empty($_SESSION['s2']))
								{
									$page .= "&s2=".$_SESSION['s2'];
								}

								$page .= "&id=".$_SESSION['id'];

								header("Location: ../../admin/".$page);
							}
						}
						else
						{
							$_SESSION['editGood'] = 'position';

							$_SESSION['goodName'] = $_POST['goodName'];
							$_SESSION['goodPrice'] = $_POST['goodPrice'];
							$_SESSION['goodCode'] = $_POST['goodCode'];
							$_SESSION['goodDescription'] = $_POST['goodDescription'];

							$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

							if(!empty($_SESSION['s2']))
							{
								$page .= "&s2=".$_SESSION['s2'];
							}

							$page .= "&id=".$_SESSION['id'];

							header("Location: ../../admin/".$page);
						}
					}
					else
					{
						$_SESSION['editGood'] = 'price';

						$_SESSION['goodName'] = $_POST['goodName'];
						$_SESSION['goodPCode'] = $_POST['goodCode'];
						$_SESSION['goodPosition'] = $_POST['goodPosition'];
						$_SESSION['goodDescription'] = $_POST['goodDescription'];

						$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

						if(!empty($_SESSION['s2']))
						{
							$page .= "&s2=".$_SESSION['s2'];
						}

						$page .= "&id=".$_SESSION['id'];

						header("Location: ../../admin/".$page);
					}
				}
				else
				{
					$_SESSION['editGood'] = 'code';

					$_SESSION['goodName'] = $_POST['goodName'];
					$_SESSION['goodPrice'] = $_POST['goodPrice'];
					$_SESSION['goodPosition'] = $_POST['goodPosition'];
					$_SESSION['goodDescription'] = $_POST['goodDescription'];

					$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

					if(!empty($_SESSION['s2']))
					{
						$page .= "&s2=".$_SESSION['s2'];
					}

					$page .= "&id=".$_SESSION['id'];

					header("Location: ../../admin/".$page);
				}
			}
			else
			{
				$_SESSION['editGood'] = 'name';

				$_SESSION['goodCode'] = $_POST['goodCode'];
				$_SESSION['goodPrice'] = $_POST['goodPrice'];
				$_SESSION['goodPosition'] = $_POST['goodPosition'];
				$_SESSION['goodDescription'] = $_POST['goodDescription'];

				$page = "admin.php?section=goods&action=edit&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s'];

				if(!empty($_SESSION['s2']))
				{
					$page .= "&s2=".$_SESSION['s2'];
				}

				$page .= "&id=".$_SESSION['id'];

				header("Location: ../../admin/".$page);
			}
		}
		else
		{
			header("Location: ../../admin/admin.php?section=goods&action=edit");
		}
	}
	else
	{
		header("Location: ../../admin/admin.php?section=goods&action=edit");
	}

?>