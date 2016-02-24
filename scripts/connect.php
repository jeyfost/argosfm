<?php
	include('config.php');
	
	$mysqli = new mysqli($host, $user, $password, $db);

	if(!$mysqli)
	{
		printf("Невозможно подключиться к базе данных. Код ошибки: %s\n", mysqli_connect_error());
	}
	
	$mysqli->query("SET NAMES 'cp1251'");
	$mysqli->query("SET CHARACTER SET 'cp1251'");
?>