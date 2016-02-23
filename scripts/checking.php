<?php
	session_start();
	
	if(empty($_POST['login']) or empty($_POST['password']))
	{
		$_SESSION['error'] = "empty";
		header("Location: ../admin/index.php?error=true");	
	}
	
	$_SESSION['login'] = $_POST['login'];
	
	include('connect.php');
	
	$count = 0;
	
	$user_result = $mysqli->query("SELECT * FROM users WHERE login = '".$_POST['login']."'");
	$user = $user_result->fetch_assoc();
	
	if(empty($user['login']))
	{
		$_SESSION['error'] = "password";
		$count++;
		header("Location: ../admin/index.php?error=true");
	}
	
	if($user['password'] != md5(md5($_POST['password'])))
	{
		$_SESSION['error'] = "password";
		$count++;
		header("Location: ../admin/index.php?error=true");	
	}
	
	if($count == 0)
	{
		$_SESSION['userID'] = $user['id'];
		header("Location: ../admin/admin.php");
	}
	
?>