<?php
	
	session_start();
	
	if(!empty($_SESSION['id']) and !empty($_SESSION['category']) and !empty($_SESSION['mode']) and !empty($_SESSION['goodsType']))
	{
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_POST['catalogueCategorySelect']);
	}
	else
	{
		header("Location: ../index.php");
	}
	
?>