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
		$orderResult = $mysqli->query("SELECT * FROM orders_date WHERE id = '".$_REQUEST['id']."'");
		if($orderResult->num_rows == 0)
		{
			echo "<span class='goodStyle' style='margin-left: 20px;'>������ ����� �� �������� �������.</span>";
		}
		else
		{
			$order = $orderResult->fetch_assoc();

			$gResult = $mysqli->query("SELECT COUNT(id) FROM orders WHERE order_id = '".$_REQUEST['id']."'");
			if($gResult->num_rows != 0)
			{
				$originalSumResult = $mysqli->query("SELECT sum from orders_date WHERE id = '".$_REQUEST['id']."'");
				$originalSum = $originalSumResult->fetch_array(MYSQLI_NUM);
				$total = 0;
				$rateResult = $mysqli->query("SELECT rate FROM currency WHERE code = 'usd'");
				$rate = $rateResult->fetch_array(MYSQLI_NUM);
				$goodsResult = $mysqli->query("SELECT * FROM orders WHERE order_id = '".$_REQUEST['id']."'");
				while($goods = $goodsResult->fetch_assoc())
				{
					$goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$goods['good_id']."'");
					$good = $goodResult->fetch_assoc();
					$total += $good['price']*$rate[0]*$goods['quantity'];

					echo "
						<div class='basketGoodH'>
					";

					if($order['status'] == 0)
					{
						echo "
							<div class='orderGoodDelete'>
								<img src='pictures/system/deleteRed.png' id='di".$good['id']."' class='noBorder' onmouseover='deleteIcon(\"1\", \"di".$good['id']."\")' onmouseout='deleteIcon(\"0\", \"di".$good['id']."\")' onclick='deleteGoodGroup(\"".$order['id']."\", \"".$good['id']."\")' title='������� ��� ������ �������' />
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
										<div id='gq".$goods['id']."' onclick='changeQuantity(\"gq".$goods['id']."\", \"gqt".$good['id']."\", \"".$goods['quantity']."\", \"".$goods['id']."\", \"".$order['id']."\")' style='cursor: pointer;' title='�������� ���������� ������� ������ � ������'><span class='basic'><b>����������: </b><span id='gqt".$good['id']."'>".$goods['quantity']."</span> ��.</span></div>
										<span class='basic'><b>����� ��������� ������ ������ �������: </b><span id='price".$good['id']."'>".($goods['quantity']*$good['price']*$rate[0])."</span> ���. ���.</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					";
				}

				if($order['status'] == 0)
				{
					echo "
						<br />
						<div style='position: relative; float: right; margin-top: 50px;'>
							<span class='basic' style='float: right; margin-right: 75px; margin-top: -40px;'><b>����� ��������� ������ �� ������ ���������:</b> ".$originalSum[0]." ���. ���.</span>
							<span class='basicGreen' style='float: right; margin-right: 75px; margin-top: -25px;'><b>����� ��������� ������ �� ������ ������ (�������� ������������ �����):</b> ".$total." ���. ���.</span>
						</div>
					";
				}
				else
				{
					echo "
						<br />
						<div style='position: relative; float: right; margin-top: 50px;'>
							<span class='basicGreen' style='float: right; margin-right: 75px; margin-top: -40px;'><b>����� ��������� ������ �� ������ ��������:</b> ".$order['sum_final']." ���. ���.</span>

						</div>
					";
				}
			}
			else
			{
				echo "<span class='goodStyle' style='margin-left: 20px;'>������ ����� �� �������� �������.</span>";
			}
		}
	}
	
?>