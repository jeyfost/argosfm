<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		$success = false;
		$codes = array();
		$count = 1;

		for($i = 1; $i <= 9999; $i++)
		{
			array_push($codes, $i);
		}

		while($success)
		{
			$codeResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE code = '".$codes[$count]."'");
			$code = $codeResult->fetch_array(MYSQLI_NUM);

			if($code[0] == 0)
			{
				$success = false;
			}
			else
			{
				$count++;
			}
		}

		echo $codes[$count];
	}
	else
	{
		header("Location: ../../index.php");
	}

?>