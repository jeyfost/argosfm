<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(strlen($_POST['userSearch']) > 0 and $_POST['userSearch'] != "Поиск...")
		{
			$query = iconv('utf-8', 'cp1251', mysql_real_escape_string($_POST['userSearch']));

			$searchResult = mysql_query("SELECT * FROM users WHERE login LIKE '%".$query."%' OR organisation LIKE '%".$query."%' OR person LIKE '%".$query."%' ORDER BY login LIMIT 10");

			if(mysql_num_rows($searchResult) > 0)
			{
				while($search = mysql_fetch_assoc($searchResult))
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