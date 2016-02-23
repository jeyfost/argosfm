<?php

	session_start();
	
	if(empty($_SESSION['id']))
	{
		$_SESSION['error'] = "empty";
		header("Location: ../admin/index.php?error=true");	
	}
	
	include('connect.php');

	$delete_result = mysql_query("DELETE FROM catalogue_new WHERE id = '".$_SESSION['titleId']."'");
	
	if($delete_result)
	{
		$_SESSION['deleted'] = "true";
		header("Location: ../admin/admin.php?mode=delete&deleted=true&section=".$_SESSION['section']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
	}
	else
	{
		$_SESSION['deleted'] = "false";
		header("Location: ../admin/admin.php?mode=delete&deleted=false");
	}

?>