<?php

	session_start();
	include('../connect.php');

	if(isset($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(!empty($_POST['newsHeader']))
		{
			if(!empty($_POST['newsShort']))
			{
				if(!empty($_POST['emailText']))
				{
					$newsResult = $mysqli->query("SELECT * FROM news WHERE id = '".$_SESSION['news']."'");
					$news = $newsResult->fetch_assoc();
					$count = 0;
					$steps = 0;

					if($_POST['newsHeader'] != $news['header'])
					{
						$steps++;

						if($mysqli->query("UPDATE news SET header = '".addslashes($_POST['newsHeader'])."' WHERE id = '".$_SESSION['news']."'"))
						{
							$count++;
						}
					}

					if($_POST['newsShort'] != $news['short'])
					{
						$steps++;

						if($mysqli->query("UPDATE news SET short = '".addslashes($_POST['newsShort'])."' WHERE id = '".$_SESSION['news']."'"))
						{
							$count++;
						}
					}


					if($_POST['emailText'] != $header['text'])
					{
						$steps++;

						if($mysqli->query("UPDATE news SET text = '".$_POST['emailText']."' WHERE id = '".$_SESSION['news']."'"))
						{
							$count++;
						}
					}

					$_SESSION['newsHeader'] = $_POST['newsHeader'];
					$_SESSION['newsShort'] = $_POST['newsShort'];
					$_SESSION['newsText'] = $_POST['emailText'];

					if($steps != 0)
					{
						if($steps == $count)
						{
							$_SESSION['editNews'] = "ok";
							header("Location: ../../admin/admin.php?section=users&action=news&news=".$_SESSION['news']);
						}
						else
						{
							if($count == 0)
							{
								$_SESSION['editNews'] = "failed";
								header("Location: ../../admin/admin.php?section=users&action=news&news=".$_SESSION['news']);
							}
							else
							{
								$_SESSION['editNews'] = "partly";
								header("Location: ../../admin/admin.php?section=users&action=news&news=".$_SESSION['news']);
							}
						}
					}
					else
					{
						$_SESSION['editNews'] = "nothing";
						header("Location: ../../admin/admin.php?section=users&action=news&news=".$_SESSION['news']);
					}
				}
				else
				{
					$_SESSION['newsHeader'] = $_POST['newsHeader'];
					$_SESSION['newsShort'] = $_POST['newsShort'];

					$_SESSION['editNews'] = "text";
					header("Location: ../../admin/admin.php?section=users&action=news&news=".$_SESSION['news']);
				}
			}
			else
			{
				$_SESSION['newsHeader'] = $_POST['newsHeader'];
				$_SESSION['newsText'] = $_POST['emailText'];

				$_SESSION['editNews'] = "short";
				header("Location: ../../admin/admin.php?section=users&action=news&news=".$_SESSION['news']);
			}
		}
		else
		{
			$_SESSION['newsShort'] = $_POST['newsShort'];
			$_SESSION['newsText'] = $_POST['emailText'];

			$_SESSION['editNews'] = "header";
			header("Location: ../../admin/admin.php?section=users&action=news&news=".$_SESSION['news']);
		}
	}
	else
	{
		header("Location: ../../index.php");
	}

?>