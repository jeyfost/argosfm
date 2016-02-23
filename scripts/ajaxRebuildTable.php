<?php

	session_start();
	include('connect.php');

	$goodsResult = $mysqli->query("SELECT * FROM orders WHERE order_id = '".$_POST['orderID']."'");
	if($goodsResult->num_rows == 0)
	{
		echo "
			<span class='goodStyle'>��� ������ �� ������ ���� �������. ����� ��� �����������.</span>
		";
	}
	else
	{
		$originalSumResult = $mysqli->query("SELECT sum FROM orders_date WHERE id = '" . $_POST['orderID'] . "'");
		$originalSum = $originalSumResult->fetch_array(MYSQLI_NUM);

		$rateResult = $mysqli->query("SELECT rate FROM currency WHERE code = 'usd'");
		$rate = $rateResult->fetch_array(MYSQLI_NUM);

		while ($goods = $goodsResult->fetch_array())
		{
			$goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '" . $goods['good_id'] . "'");
			$good = $goodResult->fetch_assoc();
			$total += $good['price'] * $rate[0] * $goods['quantity'];

			echo "
				<div class='basketGoodH'>
				";
				if ($order['status'] == 0) {
					echo "
					<div class='orderGoodDelete'>
						<img src='pictures/system/deleteRed.png' id='di" . $good['id'] . "' class='noBorder' onmouseover='deleteIcon(\"1\", \"di" . $good['id'] . "\")' onmouseout='deleteIcon(\"0\", \"di" . $good['id'] . "\")' onclick='deleteGoodGroup(\"" . $_POST['orderID'] . "\", \"" . $good['id'] . "\")' title='������� ��� ������ �������' />
					</div>
				";
				}

				echo "
					<div class='basketGoodPicture'>
						<a href='pictures/catalogue/big/" . $good['picture'] . "' class='noBorder' rel='lightbox'><img src='pictures/catalogue/small/" . $good['small'] . "' class='noBorder' /></a>
					</div>
					<div class='basketGoodContent'>
						<div class='basketGoodTopLine'>
							<div class='basketGTLRed'></div>
							<div class='basketGoodName'>
								<span class='goodStyle'>" . $good['name'] . "</span>
							</div>
							<div class='basketGoodDescription'>
								<span class='basic'>" . $good['description'] . "</span>
							</div>
							<div class='basketGoodCodePrice'>
								<div class='basketGoodCode'>
									<span class='basic'><b>�������: </b>" . $good['code'] . "</span>
								</div>
								<div class='basketGoodPrice'>
									<span class='basic'><b>���� �� ��.: </b>" . ($good['price'] * $rate[0]) . " ���. ���.</span>
									<br />
									<span class='basic'><b>����������: </b>" . $goods['quantity'] . " ��.</span>
									<br />
									<span class='basic'><b>����� ��������� ������ ������ �������: </b><span id='price" . $good['id'] . "'>" . ($goods['quantity'] * $good['price'] * $rate[0]) . "</span> ���. ���.</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			";
		}

		echo "
			<br />
			<div style='position: relative; float: right; margin-top: 10px;'>
				<span class='basic' style='float: right; margin-right: 75px; margin-top: -40px;'><b>����� ��������� ������ �� ������ ���������:</b> " . $originalSum[0] . " ���. ���.</span>
				<span class='basicGreen' style='float: right; margin-right: 75px; margin-top: -25px;'><b>����� ��������� ������ �� ������ ������ (�������� ������������ �����):</b> " . $total . " ���. ���.</span>
			</div>
		";
	}

?>