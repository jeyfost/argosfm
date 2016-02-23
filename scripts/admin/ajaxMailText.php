<?php

	include('../connect.php');

	$mailTextResult = mysql_query("SELECT text FROM mail_result WHERE id = '".$_POST['id']."'");
	$mailText = mysql_fetch_array($mailTextResult, MYSQL_NUM);

	if(!empty($mailText[0]))
	{
		echo $mailText[0];
	}

?>