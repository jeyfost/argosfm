<?php

	session_start();
	include('connect.php');
	
	if(empty($_REQUEST['id']))
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
		$orderResult = mysql_query("SELECT * FROM orders_date WHERE id = '".$_REQUEST['id']."'");
		if(mysql_num_rows($orderResult) == 0)
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
			$originalSumResult = mysql_query("SELECT sum from orders_date WHERE id = '".$_REQUEST['id']."'");
			$originalSum = mysql_fetch_array($originalSumResult, MYSQL_NUM);
			$order = mysql_fetch_assoc($orderResult);
			$total = 0;
			$rateResult = mysql_query("SELECT rate FROM currency WHERE code = 'usd'");
			$rate = mysql_fetch_array($rateResult, MYSQL_NUM);
			$goodsResult = mysql_query("SELECT * FROM orders WHERE order_id = '".$_REQUEST['id']."'");
			while($goods = mysql_fetch_array($goodsResult))
			{
				$goodResult = mysql_query("SELECT * FROM catalogue_new WHERE id = '".$goods['good_id']."'");
				$good = mysql_fetch_array($goodResult, MYSQL_ASSOC);
				$total += $good['price']*$rate[0]*$goods['quantity'];
				
				echo "
					<div class='basketGoodH'>
				";

				if($order['status'] == 0)
				{
					echo "
						<div class='orderGoodDelete'>
							<img src='pictures/system/deleteRed.png' id='di".$good['id']."' class='noBorder' onmouseover='deleteIcon(\"1\", \"di".$good['id']."\")' onmouseout='deleteIcon(\"0\", \"di".$good['id']."\")' onclick='deleteGoodGroup(\"".$order['id']."\", \"".$good['id']."\")' title='Удалить эту группу товаров' />
						</div>
					";
				}
				
				echo "
						<div class='basketGoodPicture'>
							<a href='pictures/catalogue/big/".$good['picture']."' class='noBorder' rel='lightbox'><img src='pictures/catalogue/small/".$good['small']."' class='noBorder' /></a>
						</div>
						<div class='basketGoodContent'>
							<div class='basketGoodTopLine'>
								<div class='basketGTLRed'></div>
								<div class='basketGoodName'>
									<span class='goodStyle'>".$good['name']."</span>
								</div>
								<div class='basketGoodDescription'>
									<span class='basic'>".$good['description']."</span>
								</div>
								<div class='basketGoodCodePrice'>
									<div class='basketGoodCode'>
										<span class='basic'><b>Артикул: </b>".$good['code']."</span>
									</div>
									<div class='basketGoodPrice'>
										<span class='basic'><b>Цена за ед.: </b>".($good['price']*$rate[0])." бел. руб.</span>
										<br />
										<div id='gq".$goods['id']."' onclick='changeQuantity(\"gq".$goods['id']."\", \"gqt".$good['id']."\", \"".$goods['quantity']."\", \"".$goods['id']."\", \"".$order['id']."\")' style='cursor: pointer;' title='Изменить количество данного товара в заказе'><span class='basic'><b>Количество: </b><span id='gqt".$good['id']."'>".$goods['quantity']."</span> шт.</span></div>
										<span class='basic'><b>Общая стоимость данной группы товаров: </b><span id='price".$good['id']."'>".($goods['quantity']*$good['price']*$rate[0])."</span> бел. руб.</span>
									</div>
								</div>
							</div>
						</div>
					</div>

				";
			}

			

			echo "
				<br />
				<span class='basic' style='float: right; margin-right: 75px; margin-top: -40px;'><b>Общая стоимость заказа на момент офрмления:</b> ".$originalSum[0]." бел. руб.</span>
				<span class='basicGreen' style='float: right; margin-right: 75px; margin-top: -25px;'><b>Общая стоимость заказа на данный момент (согласно сегодняшнему курсу):</b> ".$total." бел. руб.</span>
			";
		}
	}
	
?>