<?php
	include('config.php');
	
	$link = mysql_connect($host, $user, $password);
	mysql_select_db($db, $link);
	
	mysql_query("SET NAMES 'cp1251'");
	mysql_query("SET CHARACTER SET 'cp1251'");
?>