<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(strlen($_POST['userSearch']) > 0 and $_POST['userSearch'] != "Поиск...")
		{
			$query = iconv('utf-8', 'cp1251', $mysqli->real_escape_string($_POST['userSearch']));

			$searchResult = $mysqli->query("SELECT * FROM users WHERE login LIKE '%".$query."%' OR organisation LIKE '%".$query."%' OR person LIKE '%".$query."%' ORDER BY login LIMIT 10");

			if(MYSQLI_NUM_rows($searchResult) > 0)
			{
				while($search = $searchResult->fetch_assoc())
				{
					echo "
						<a href='admin.php?section=users&action=users&user=".$search['id']."' class='noBorder'>
							<div class='admSearchVariant' id='usb".$search['id']."' onmouseover='admPointChange(\"1\", \"usb".$search['id']."\", \"usf".$search['id']."\")' onmouseout='admPointChange(\"0\", \"usb".$search['id']."\", \"usf".$search['id']."\")' title='".$search['organisation']."; ".$search['person']."; ".$search['phone']."'>
								<span class='admMenuFont' style='font-size: 14px;' id='usf".$search['id']."'>".$search['login']."</span>
							</div>
						</a>
					";
				}
			}
		}
	}
	else
	{
		header("Location: ../../imdex.php");
	}

?>