<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		$name = iconv('UTF-8', 'windows-1251', htmlspecialchars($_POST['subcategory2Name']));
		if(strlen($name) > 0)
		{
			$subcategory2Result = mysql_query("SELECT COUNT(id) FROM subcategories2 WHERE name = '".$name."' AND subcategory = '".$_SESSION['s']."'");
			$subcategory2 = mysql_fetch_array($subcategory2Result, MYSQL_NUM);

			if($subcategory2[0] == 0)
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