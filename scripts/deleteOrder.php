<?php
	
	session_start();
	
	if(empty($_SESSION['userID']) or empty ($_REQUEST['id']))
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
	
	include('connect.php');
	
	$orderInfoResult = $mysqli->query("SELECT * FROM orders_date WHERE id='".$_REQUEST['id']."'");
	$orderInfo = $orderInfoResult->fetch_array(MYSQLI_ASSOC);
	
	if(empty($orderInfo) or $orderInfo['user_id'] != $_SESSION['userID'])
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
		if($mysqli->query("DELETE FROM orders WHERE order_id = '".$_REQUEST['id']."'") and $mysqli->query("DELETE FROM orders_date WHERE id = '".$_REQUEST['id']."'"))
		{
			echo "<span class='basicGreen'>����� ������� �����!</span><br /><br />";
			
			$orders1Result = $mysqli->query("SELECT * FROM orders_date WHERE user_id = '".$_SESSION['userID']."' AND status = '0' ORDER BY id");
			if(MYSQLI_NUM_rows($orders1Result) == 0)
			{
				echo "<span class='basic'>������� ����� ������� �����, ��� ��� �� �� ���������� ��� �� ������ ������. ��� ����� ��������� � <a href='catalogue.php' class='noBorder'><span class='catalogueItemTextDecorated'>�������</span></a> � �������� ����������� ��� ������.</span>";
			}
			else
			{
				echo "
					<div class='ordersTable'>
						<div class='line'>
							<div class='orderNumberTop'>
								<span class='goodStyle'>�</span>
							</div>
							<div class='tableSpace'></div>
							<div class='orderNameTop'>
								<span class='goodStyle'>������� ������</span>
							</div>
							<div class='tableSpace'></div>
							<div class='orderStatusTop'>
								<span class='goodStyle'>������</span>
							</div>
						</div>
					";
								
				$number = 0;
								
				while($orders1 = $orders1Result->fetch_assoc())
				{
					$number++;			
					$status = "�� ���������";
									
					echo "
						<div class='tableVSpace'></div>
						<div class='line'>
							<div class='orderNumber'>
								<span class='tableStyle'>".$number."</span>
							</div>
							<div class='tableSpace'></div>
							<div class='orderName'>
								<span class='tableStyleDecorated' id='order".$orders1['id']."' title='��������� ��������' style='float: left;' onclick='showDetails(\"".$orders1['id']."\", \"order".$orders1['id']."\", \"tableGood".$orders1['id']."\")'>����� �".$orders1['id']." �� ".$orders1['date']."</span>
								<span class='tableStyleDecorated' id='deleteOrder".$orders1['id']."' title='�������� ������ �����' style='float: right; margin-right: 5px;' onclick='deleteOrder(\"".$orders1['id']."\")'>�������</span>
							</div>
							<div class='tableSpace'></div>
							<div class='orderStatus'>
								<span class='tableStyle'>".$status."</span>
							</div>
						</div>
						<div class='tableVSpace'></div>
						<div class='tableGood' id='tableGood".$orders1['id']."'></div>
					";
				}
							
				echo "</div>";
			}
		}
		else
		{
			echo "<span class='basicRed'>��� �������� ������ ��������� ������. ���������� �����.</span>";
		}
	}

?>