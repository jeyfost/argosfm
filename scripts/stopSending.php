<?php

	include('connect.php');

	if(!empty($_REQUEST['hash']))
	{
		$emailResult = mysql_query("SELECT * FROM mail WHERE hash = '".htmlspecialchars($_REQUEST['hash'])."'");
		$email = mysql_fetch_assoc($emailResult);

		if(!empty($email))
		{
			if(mysql_query("UPDATE mail SET in_send = '0', disactivation_date = '".date("Y-m-d")."' WHERE hash = '".htmlspecialchars($_REQUEST['hash'])."'"))
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