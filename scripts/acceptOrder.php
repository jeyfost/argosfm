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
			echo "<span class='basicRed'>Данный заказ нельзя принять, так как он не содержит ни одной позиции.</span><br /><br />";
		}
		else {
			if($mysqli->query("UPDATE orders_date SET status = '1', proceed_date='".date('Y-m-d H:i:s')."' WHERE id = '".$_REQUEST['id']."'"))
			{
				echo "<span class='basicGreen'>Заказ успешно принят!</span><br /><br />";
			}
			else
			{
				echo "<span class='basicRed'>Во время принятия заказа произошла ошибка. Повторите попытку.</span><br /><br />";
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
						<span class='tableStyleDecorated' id='order".$orders['id']."' title='Помотреть подробно' style='float: left;' onclick='showDetails(\"".$orders['id']."\", \"order".$orders['id']."\", \"tableGood".$orders['id']."\")'>Заказ №".$orders['id']." от ".$orders['date']."</span>
						<span class='tableStyleDecorated' id='deleteOrder".$orders['id']."' title='Отменить данный заказ' style='float: right; margin-right: -30px;' onclick='cancelOrder(\"".$orders['id']."\")'>Отклонить</span>
										<span class='tableStyleDecorated' id='acceptOrder".$orders['id']."' title='Принять данный заказ' style='float: right; margin-right: 10px;' onclick='acceptOrder(\"".$orders['id']."\")'>Принять</span>
					</div>
					<div class='tableSpace'></div>
					<div class='orderStatus'>
						<span class='tableStyleDecorated' title='Заказчик: ".$info."' style='float: right; margin-right: 10px; cursor: help;'>Заказчик</span>
					</div>
				</div>
				<div class='tableVSpace'></div>
				<div class='tableGood' id='tableGood".$orders['id']."'></div>
			";
		}

?>