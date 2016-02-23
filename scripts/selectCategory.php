<?php
	
	session_start();
	
	if(!empty($_SESSION['id']))
	{
		header("Location: ../admin/admin.php?category=".$_POST['categorySelect']);
	}
	else
	{
		header("Location: ../index.php");
	}
	
?>