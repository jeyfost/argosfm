<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(strlen($_POST['goodCode']) >= 4)
		{
			$codeResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE code = '".$_POST['goodCode']."'");
			$code = $codeResult->fetch_array(MYSQLI_NUM);

			if($code[0] == 0)
			{
				echo "a";
			}
			else
			{
				echo "b";
			}
		}
	}

?>