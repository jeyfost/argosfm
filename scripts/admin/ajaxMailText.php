<?php

	include('../connect.php');

	$mailTextResult = $mysqli->query("SELECT text FROM mail_result WHERE id = '".$_POST['id']."'");
	$mailText = $mailTextResult->fetch_array(MYSQLI_NUM);

	if(!empty($mailText[0]))
	{
		$mt = str_replace("&lt;", "<", $mailText[0]);
		$mt = str_replace("&gt;", ">", $mt);
		$mt = strip_tags($mt, "<br>");
		echo iconv("cp1251", "utf-8", $mt);
	}