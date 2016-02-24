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

		if($mysqli->query("DELETE FROM orders_date WHERE id = '".$_REQUEST['id']."'") and $mysqli->query("DELETE FROM orders WHERE order_id = '".$orderInfo['id']."'"))
		{
			echo "<span class='basicGreen'>����� ������� �����!</span><br /><br />";
		}
		else
		{
			echo "<span class='basicRed'>��� �������� ������ ��������� ������. ��������� �������.</span><br /><br />";
		}
		
		$ordersResult = $mysqli->query("SELECT * FROM orders_date WHERE status = '0' ORDER BY date");
		while($orders = $ordersResult->fetch_assoc()) {
			$number++;
			$customerResult = $mysqli->query("SELECT * FROM users WHERE id = '" . $orders['user_id'] . "'");
			$customer = $customerResult->fetch_assoc();
			$info = $customer['person'] . "; " . $customer['phone'];
			if (!empty($customer['organisation'])) {
				$info = $customer['organisation'] . "; " . $info;
			}

			echo "
				<div class='tableVSpace'></div>
				<div class='line'>
					<div class='orderNumber'>
						<span class='tableStyle'>" . $number . "</span>
					</div>
						<div class='tableSpace'></div>
					<div class='orderName'>
						<span class='tableStyleDecorated' id='order" . $orders['id'] . "' title='��������� ��������' style='float: left;' onclick='showDetails(\"" . $orders['id'] . "\", \"order" . $orders['id'] . "\", \"tableGood" . $orders['id'] . "\")'>����� �" . $orders['id'] . " �� " . $orders['date'] . "</span>
						<span class='tableStyleDecorated' id='deleteOrder" . $orders['id'] . "' title='�������� ������ �����' style='float: right; margin-right: -30px;' onclick='cancelOrder(\"" . $orders['id'] . "\")'>���������</span>
										<span class='tableStyleDecorated' id='acceptOrder" . $orders['id'] . "' title='������� ������ �����' style='float: right; margin-right: 10px;' onclick='acceptOrder(\"" . $orders['id'] . "\")'>�������</span>
					</div>
					<div class='tableSpace'></div>
					<div class='orderStatus'>
						<span class='tableStyleDecorated' title='��������: " . $info . "' style='float: right; margin-right: 10px; cursor: help;'>��������</span>
					</div>
				</div>
				<div class='tableVSpace'></div>
				<div class='tableGood' id='tableGood" . $orders['id'] . "'></div>
			";
		}
?>