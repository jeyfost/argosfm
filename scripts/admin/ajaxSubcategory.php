<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		$name = iconv('UTF-8', 'windows-1251', htmlspecialchars($_POST['subcategoryName']));
		if(strlen($name) > 0)
		{
			$subcategoryResult = mysql_query("SELECT COUNT(id) FROM subcategories_new WHERE name = '".$name."' AND category = '".$_SESSION['c']."'");
			$subcategory = mysql_fetch_array($subcategoryResult, MYSQL_NUM);

			if($subcategory[0] == 0)
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