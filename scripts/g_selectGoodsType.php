<?php
	
	session_start();
	
	if(!empty($_SESSION['id']) and !empty($_SESSION['category']) and !empty($_SESSION['mode']))
	{
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_POST['goodsTypeSelect']);
	}
	else
	{
		header("Location: ../index.php");
	}
	
?>