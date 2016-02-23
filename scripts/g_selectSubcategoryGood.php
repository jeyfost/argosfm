<?php

	session_start();
	
	if(!empty($_SESSION['id']) and !empty($_SESSION['category']) and !empty($_SESSION['mode']) and !empty($_SESSION['goodsType']) and !empty($_SESSION['cId']) and !empty($_SESSION['sId']))
	{
		header("Location: ../admin/admin.php?category=".$_SESSION['category']."&mode=".$_SESSION['mode']."&goodsType=".$_SESSION['goodsType']."&cId=".$_SESSION['cId']."&sId=".$_SESSION['sId']."&gId=".$_POST['goodCategorySelect']);
	}
	else
	{
		header("Location: ../index.php");
	}

?>