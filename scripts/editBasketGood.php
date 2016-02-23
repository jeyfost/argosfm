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
			
			$goodResult = mysql_query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND good_id = '".$_REQUEST['id']."' AND status = '0'");
			$good = mysql_fetch_array($goodResult, MYSQL_ASSOC);
			
			if($good['quantity'] != $_REQUEST['q'])
			{
				if(mysql_query("UPDATE basket SET quantity = '".$_REQUEST['q']."' WHERE user_id = '".$_SESSION['userID']."' AND good_id = '".$_REQUEST['id']."'"))
				{
					echo "Количество изменено.";
				}
				else
				{
					echo "<span class='basicRed'>Произошла ошибка. Попробуйте снова</span>";
				}
			}
			else
			{
				echo "<span class='basicRed'>Количество не изменилось.</span>";
			}
		}
		else
		{
			echo "<span class='basicRed'>В скрипт поступили ошибочные параметры. Повторите попытку. ".$_REQUEST['q']."</span>";
		}
	}
	
?>