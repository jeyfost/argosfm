<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['type']) and !empty($_SESSION['c']) and !empty($_SESSION['s']))
	{
		if($_POST['subcategory2Select'] != '')
		{
			header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_POST['subcategory2Select']);
		}
		else
		{
			header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
		}
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}

?>