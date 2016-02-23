<?php

	session_start();
	
	if(empty($_SESSION['userID']))
	{
		if(isset($_SESSION['last_page']))
		{
			header("Location: ".$_SESSION['last_page']);
		}
		else
		{
			header("Location: ../index.php");
		}
	}
	
	if(!empty($_REQUEST['id']) and !empty($_REQUEST['q']) and is_int((int)$_REQUEST['id']) and is_int((int)$_REQUEST['q']))
	{
		include('connect.php');
		
		$goodResult = $mysqli->query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND good_id = '".$_REQUEST['id']."' AND status = '0'");
		$good = $goodResult->fetch_assoc();
		
		if(empty($good))
		{
			if($mysqli->query("INSERT INTO basket (user_id, good_id, quantity, status) VALUES ('".$_SESSION['userID']."', '".$_REQUEST['id']."', '".$_REQUEST['q']."', '0')"))
			{
				echo "����� �������� � <a href='order.php' class='noBorder' target='_blank'><span class='catalogueItemTextDecorated'>�������</span></a>.";
			}
			else
			{
				echo "<span class='basicRed'>��� ���������� ������ �������� ������. ���������� �����.</span>";
			}
		}
		else
		{
			if($mysqli->query("UPDATE basket SET quantity = '".($good['quantity'] + $_REQUEST['q'])."' WHERE id = '".$good['id']."'"))
			{
				echo "���������� ������� ������ � <a href='order.php' class='noBorder' target='_blank'><span class='catalogueItemTextDecorated'>�������</span></a> ���������.";
			}
			else
			{
				echo "<span class='basicRed'>��� ���������� ������ �������� ������. ���������� �����.</span>";
			}
		}
	}
	else
	{
		echo "<span class='basicRed'>� ������ ��������� ��������� ���������. ��������� �������. ".$_REQUEST['q']."</span>";
	}
?>