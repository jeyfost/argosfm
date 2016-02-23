<?php

	session_start();
	
	include('connect.php');
	
	if(empty($_SESSION['userID']))
	{
		if(isset($_SESSION['last_page']))
		{
			header("Location: ".$_SESSION['last_page']);
		}
		else
		{
			header("Location: ../index.php");
		}
	}
	else
	{
		if($_SESSION['userID'] != 1)
		{
			if(isset($_SESSION['last_page']))
			{
				header("Location: ".$_SESSION['last_page']);
			}
			else
			{
				header("Location: ../index.php");
			}
		}
	}
	
	if(empty($_REQUEST['id']))
	{
		if(isset($_SESSION['last_page']))
		{
			header("Location: ".$_SESSION['last_page']);
		}
		else
		{
			header("Location: ../index.php");
		}
	}
	else
	{
		$newsResult = $mysqli->query("SELECT * FROM news WHERE id = ".$_REQUEST['id']);
		if($newsResult->num_rows == 0)
		{
			if(isset($_SESSION['last_page']))
			{
				header("Location: ".$_SESSION['last_page']);
			}
			else
			{
				header("Location: ../index.php");
			}
		}
		else
		{
			$news = $newsResult->fetch_assoc();
			
			if(!empty($_POST['newsHeader']) and !empty($_POST['newsText']) and !empty($_POST['newsDescription']))
			{
				$_SESSION['nHeader'] = $_POST['nHeader'];
				$_SESSION['nText'] = $_POST['newsText'];
				$_SESSION['nDescription'] = $_POST['newsDescription'];
				
				if($mysqli->query())
				{
					$_SESSION['newsResult'] = 'success';
					
					unset($_SESSION['nHeader']);
					unset($_SESSION['nText']);
					unset($_SESSION['nDescription']);
					
					header("Location: ../admin/news.php");
				}
				else
				{
					$_SESSION['newsResult'] = 'failed';
					header("Location: ../admin/news.php");			
				}
			}
		}
	}

?>