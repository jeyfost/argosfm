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
	else
	{
		if(!empty($_REQUEST['id']) and !empty($_REQUEST['q']) and is_int((int)$_REQUEST['id']) and is_int((int)$_REQUEST['q']))
		{
			include('connect.php');
			
			$goodResult = $mysqli->query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND good_id = '".$_REQUEST['id']."' AND status = '0'");
			$good = $goodResult->fetch_assoc();
			
			if($good['quantity'] != $_REQUEST['q'])
			{
				if($mysqli->query("UPDATE basket SET quantity = '".$_REQUEST['q']."' WHERE user_id = '".$_SESSION['userID']."' AND good_id = '".$_REQUEST['id']."'"))
				{
					echo "���������� ��������.";
				}
				else
				{
					echo "<span class='basicRed'>��������� ������. ���������� �����</span>";
				}
			}
			else
			{
				echo "<span class='basicRed'>���������� �� ����������.</span>";
			}
		}
		else
		{
			echo "<span class='basicRed'>� ������ ��������� ��������� ���������. ��������� �������. ".$_REQUEST['q']."</span>";
		}
	}
	
?>