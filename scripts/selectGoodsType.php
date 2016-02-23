<?php
	
	session_start();
	
	if(!empty($_SESSION['id']) and !empty($_SESSION['category']) and !empty($_SESSION['mode']) and !empty($_SESSION['type']))
	{
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&type=".$_SESSION['type']."&goodsType=".$_POST['goodsTypeSelect']);
	}
	else
	{
		header("Location: ../index.php");
	}
	
?>