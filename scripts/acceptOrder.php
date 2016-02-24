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

	$goodsQuantityResult = $mysqli->query("SELECT COUNT(id) FROM orders WHERE order_id = '".$_REQUEST['id']."'");
	$goodsQuantity = $goodsQuantityResult->fetch_array(MYSQLI_NUM);

		if($goodsQuantity[0] == 0) {
			echo "<span class='basicRed'>������ ����� ������ �������, ��� ��� �� �� �������� �� ����� �������.</span><br /><br />";
		}
		else {
			$finalSum = 0;

			$rateResult = $mysqli->query("SELECT rate FROM currency WHERE code = 'usd'");
			$rate = $rateResult->fetch_array(MYSQLI_NUM);

			$goodsResult = $mysqli->query("SELECT * FROM orders WHERE order_id = '".$_REQUEST['id']."'");
			while($goods = $goodsResult->fetch_assoc())
			{
				$goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$goods['good_id']."'");
				$good = $goodResult->fetch_assoc();

				$finalSum += $goods['quantity'] * $good['price'] * $rate[0];
			}

			if($mysqli->query("UPDATE orders_date SET status = '1', proceed_date = '".date('Y-m-d H:i:s')."', sum_final = '".$finalSum."' WHERE id = '".$_REQUEST['id']."'"))
			{
				echo "<span class='basicGreen'>����� ������� ������!</span><br /><br />";
			}
			else
			{
				echo "<span class='basicRed'>�� ����� �������� ������ ��������� ������. ��������� �������.</span><br /><br />";
			}
		}

		$ordersResult = $mysqli->query("SELECT * FROM orders_date WHERE status = '0' ORDER BY date");
		while($orders = $ordersResult->fetch_assoc())
		{
			$number++;
			$customerResult = $mysqli->query("SELECT * FROM users WHERE id = '".$orders['user_id']."'");
			$customer = $customerResult->fetch_assoc();
			$info = $customer['person']."; ".$customer['phone'];
			if(!empty($customer['organisation']))
			{
				$info = $customer['organisation']."; ".$info;
			}

			echo "
				<div class='tableVSpace'></div>
				<div class='line'>
					<div class='orderNumber'>
						<span class='tableStyle'>".$number."</span>
					</div>
						<div class='tableSpace'></div>
					<div class='orderName'>
						<span class='tableStyleDecorated' id='order".$orders['id']."' title='��������� ��������' style='float: left;' onclick='showDetails(\"".$orders['id']."\", \"order".$orders['id']."\", \"tableGood".$orders['id']."\")'>����� �".$orders['id']." �� ".$orders['date']."</span>
						<span class='tableStyleDecorated' id='deleteOrder".$orders['id']."' title='�������� ������ �����' style='float: right; margin-right: -30px;' onclick='cancelOrder(\"".$orders['id']."\")'>���������</span>
										<span class='tableStyleDecorated' id='acceptOrder".$orders['id']."' title='������� ������ �����' style='float: right; margin-right: 10px;' onclick='acceptOrder(\"".$orders['id']."\")'>�������</span>
					</div>
					<div class='tableSpace'></div>
					<div class='orderStatus'>
						<span class='tableStyleDecorated' title='��������: ".$info."' style='float: right; margin-right: 10px; cursor: help;'>��������</span>
					</div>
				</div>
				<div class='tableVSpace'></div>
				<div class='tableGood' id='tableGood".$orders['id']."'></div>
			";
		}

?>