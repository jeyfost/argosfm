<?php

	session_start();
	
	if(!empty($_SESSION['id']) and !empty($_SESSION['category']) and !empty($_SESSION['mode']) and !empty($_SESSION['goodsType']) and !empty($_SESSION['cId']))
	{
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&gId=".$_POST['goodCategoryEditSelect']);
	}
	else
	{
		header("Location: ../index.php");
	}

?>