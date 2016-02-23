<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		$name = iconv('UTF-8', 'windows-1251', htmlspecialchars($_POST['categoryName']));
		if(strlen($name) > 0)
		{
			$categoryResult = mysql_query("SELECT COUNT(id) FROM categories_new WHERE name = '".$name."'");
			$category = mysql_fetch_array($categoryResult, MYSQL_NUM);

			if($category[0] == 0)
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