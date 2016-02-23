<?php

	session_start();
	include('connect.php');

	if(isset($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(!empty($_POST['goodPrice']) and $_POST['goodPrice'] > 0)
		{
			if(mysql_query("UPDATE catalogue_new SET price = '".$_POST['goodPrice']."' WHERE id = '".$_POST['goodID']."'"))
			{
				echo "a";
			}
			else
			{
				echo "b";
			}
		}
		else
		{
			echo "b";
		}
	}
	else
	{
		header("Location: ../index.php");
	}

?>