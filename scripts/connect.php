<?php
	include('config.php');

	global $mysqli;
	
	$mysqli = new mysqli($host, $user, $password, $db);

	if(!$mysqli)
	{
		printf("���������� ������������ � ���� ������. ��� ������: %s\n", mysqli_connect_error());
	}
	
	$mysqli->query("SET NAMES 'cp1251'");
	$mysqli->query("SET CHARACTER SET 'cp1251'");
?>