<?php

	session_start();

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../../index.php");
	}

	if(!empty($_SESSION['section']) and !empty($_SESSION['action']))
	{
		if($_POST['typeSelect'] != '')
		{
			if(!empty($_SESSION['level']))
			{
				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']."&type=".$_POST['typeSelect']);
			}
			else
			{
				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&type=".$_POST['typeSelect']);
			}
			
		}
		else
		{
			if(!empty($_SESSION['level']))
			{
				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&level=".$_SESSION['level']);
			}
			else
			{
				header("Location: ../../admin/admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']);
			}
			
		}
	}
	else
	{
		header("Location: ../../admin/admin.php");
	}

?>