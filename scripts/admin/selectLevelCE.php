<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']))
	{
		if(!empty($_POST['levelSelect']))
		{
			header("Location: ../../admin/admin.php?section=categories&action=".$_SESSION['action']."&level=".$_POST['levelSelect']);
		}
		else
		{
			header("Location: ../../admin/admin.php?section=categories&action=".$_SESSION['action']);
		}
	}
	else
	{
		header("Location: ../../admin/admin.php?");
	}

?>