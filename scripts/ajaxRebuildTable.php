<?php

	include('connect.php');

	$goodsResult = mysql_query("SELECT * FROM orders WHERE order_id = '".$_POST['orderID']."'");

	$originalSumResult = mysql_query("SELECT sum FROM orders_date WHERE id = '".$_POST['orderID']."'");
	$originalSum = mysql_fetch_array($originalSumResult, MYSQL_NUM);

	$rateResult = mysql_query("SELECT rate FROM currency WHERE code = 'usd'");
	$rate = mysql_fetch_array($rateResult, MYSQL_NUM);

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
					<img src='pictures/system/deleteRed.png' id='di".$good['id']."' class='noBorder' onmouseover='deleteIcon(\"1\", \"di".$good['id']."\")' onmouseout='deleteIcon(\"0\", \"di".$good['id']."\")' onclick='deleteGoodGroup(\"".$_POST['orderID']."\", \"".$good['id']."\")' title='������� ��� ������ �������' />
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
								<span class='basic'><b>�������: </b>".$good['code']."</span>
							</div>
							<div class='basketGoodPrice'>
								<span class='basic'><b>���� �� ��.: </b>".($good['price']*$rate[0])." ���. ���.</span>
								<br />
								<span class='basic'><b>����������: </b>".$goods['quantity']." ��.</span>
								<br />
								<span class='basic'><b>����� ��������� ������ ������ �������: </b><span id='price".$good['id']."'>".($goods['quantity']*$good['price']*$rate[0])."</span> ���. ���.</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		";
	}

			

	echo "
		<br />
		<span class='basic' style='float: right; margin-right: 75px; margin-top: -40px;'><b>����� ��������� ������ �� ������ ���������:</b> ".$originalSum[0]." ���. ���.</span>
		<span class='basicGreen' style='float: right; margin-right: 75px; margin-top: -25px;'><b>����� ��������� ������ �� ������ ������ (�������� ������������ �����):</b> ".$total." ���. ���.</span>
	";

?>