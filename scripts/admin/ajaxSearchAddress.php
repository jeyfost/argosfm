<?php

	session_start();
	include('../connect.php');

	if(!isset($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
		header("Location: ../index.php");
	}

	if(!empty($_POST['searchQuery']))
	{
		$query = iconv('UTF-8', 'CP1251', $_POST['searchQuery']);
		$count = 0;

		$searchResult = mysql_query("SELECT * FROM mail WHERE email LIKE '%".$query."%' ORDER BY email LIMIT 10");

		if(mysql_num_rows($searchResult) > 0)
		{
			while($search = mysql_fetch_assoc($searchResult))
			{
				$count++;

				echo "<div class='searchAddress' id='searchAddressBlock".$search['id']."'"; if($count != 1) {echo " style='margin-top: 10px;'";} echo "><span class='admULFont' style='cursor: pointer;' onclick='editEmail(\"".$search['id']."\", \"".$search['email']."\", \"searchAddressBlock".$search['id']."\")'>".$search['email']."</span></div>";
			}
		}
		else
		{
			echo "<span class='admLabel'>К сожалению, ничего не найдено.</span>";
		}
	}

?>