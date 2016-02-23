<?php

	session_start();
	include('../connect.php');

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['type']) and !empty($_SESSION['c']) and !empty($_SESSION['s']) and !empty($_SESSION['s2']))
	{
		if(!empty($_POST['goodSelect']))
		{
			header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']."&id=".$_POST['goodSelect']);
		}
		else
		{
			header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&s2=".$_SESSION['s2']);
		}
	}
	else
	{
		if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['type']) and !empty($_SESSION['c']) and !empty($_SESSION['s']))
		{
			if(!empty($_POST['goodSelect']))
			{
				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']."&id=".$_POST['goodSelect']);
			}
			else
			{
				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&s=".$_SESSION['s']);
			}
		}
		else
		{
			if(!empty($_SESSION['section']) and !empty($_SESSION['action']) and !empty($_SESSION['type']) and !empty($_SESSION['c']))
			{
				if(!empty($_POST['goodSelect']))
				{
					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']."&id=".$_POST['goodSelect']);
				}
				else
				{
					header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_SESSION['type']."&c=".$_SESSION['c']);
				}
			}
			else
			{
				header("Location: ../../admin.php");
			}
		}
	}

?>