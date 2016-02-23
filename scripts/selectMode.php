<?php
	
	session_start();
	
	if(!empty($_SESSION['id']) and !empty($_SESSION['category']))
	{
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_POST['modeSelect']);
	}
	else
	{
		header("Location: ../index.php");
	}
	
?>