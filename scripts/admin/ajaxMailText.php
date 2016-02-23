<?php

	include('../connect.php');

	$mailTextResult = $mysqli->query("SELECT text FROM mail_result WHERE id = '".$_POST['id']."'");
	$mailText = $mailTextResult->fetch_array(MYSQLI_NUM);

	if(!empty($mailText[0]))
	{
		echo $mailText[0];
	}

?>