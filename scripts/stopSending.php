<?php

	include('connect.php');

	if(!empty($_REQUEST['hash']))
	{
		$emailResult = $mysqli->query("SELECT * FROM mail WHERE hash = '".htmlspecialchars($_REQUEST['hash'])."'");
		$email = $emailResult->fetch_assoc();

		if(!empty($email))
		{
			if($mysqli->query("UPDATE mail SET in_send = '0', disactivation_date = '".date("Y-m-d")."' WHERE hash = '".htmlspecialchars($_REQUEST['hash'])."'"))
			{
				echo "�� ���� ������� �������� �� ��������. �������� ���� ��������� �� ������������ ������������.";
			}
			else
			{
				echo "� ���������, ��-�� ����������� ������ ������� �� ������ ������� ��� ����� �� ���� ������. ��� �������� ��������� � ���� �� �������� �� ������. �������� ���� ��������� �� ������������ ������������.";
			}
		}
		else
		{
			echo "����� �� ��� ������ � ���� ������.";
		}
	}
	else
	{
		echo "����� �� ��� ������ � ���� ������.";
	}

?>