<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['level']) and !empty($_SESSION['type']))
	{
		if(!empty($_POST['categorySelect']))
		{
			header("Location: ../../admin/admin.php?section=categories&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_POST['categorySelect']);
		}
		else
		{
			header("Location: ../../admin/admin.php?section=categories&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']);
		}
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}

?>