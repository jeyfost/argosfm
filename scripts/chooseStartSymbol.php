<?php

	session_start();
	include('connect.php');

	if(!isset($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../index.php");
	}

	if(!empty($_POST['startSymbolSelect']))
	{
		header("Location: ../admin/admin.php?section=users&action=maillist&active=".$_SESSION['active']."&start=".$_POST['startSymbolSelect']);
	}
	else
	{
		header("Location: ../admin/admin.php?section=users&action=maillist&active=".$_SESSION['active']."&p=1");
	}

?>