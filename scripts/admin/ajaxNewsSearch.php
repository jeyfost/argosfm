<?php

	session_start();
	include('../connect.php');

	if(!empty($_SESSION['userID']) and $_SESSION['userID'] == 1)
	{
		if(strlen($_POST['newsSearch']) > 0 and $_POST['newsSearch'] != "Поиск...")
		{
			$query = iconv('utf-8', 'cp1251', $mysqli->real_escape_string($_POST['newsSearch']));

			$searchResult = $mysqli->query("SELECT * FROM news WHERE header LIKE '%".$query."%' ORDER BY header LIMIT 10");

			if($searchResult->num_rows > 0)
			{
				while($search = $searchResult->fetch_assoc())
				{
					echo "
						<a href='admin.php?section=users&action=news&news=".$search['id']."' class='noBorder'>
							<div class='admSearchVariant' id='usb".$search['id']."' onmouseover='admPointChange(\"1\", \"usb".$search['id']."\", \"usf".$search['id']."\")' onmouseout='admPointChange(\"0\", \"usb".$search['id']."\", \"usf".$search['id']."\")' title='".$search['short']."'>
								<span class='admMenuFont' style='font-size: 14px;' id='usf".$search['id']."'>".$search['header']."</span>
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