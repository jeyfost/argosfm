<?php

	include('connect.php');

	$customerResult = $mysqli->query("SELECT * FROM users WHERE id = '".$_POST['id']."'");
	$customer = $customerResult->fetch_assoc();

	echo "
		<center>
			<span class='admLabel'>���������� � ���������</span>
			<br /><br />
		</center>
	";

	if(!empty($customer['organisation']))
	{
		echo "
			<span class='admLabel' style='font-weight: bold;'>�������� �����������: </span><span class='admLabel'>".$customer['organisation']."</span><br />
		";
	}
	
	echo "
		<span class='admLabel' style='font-weight: bold;'>���������� ����: </span><span class='admLabel'>".$customer['person']."</span><br />
		<span class='admLabel' style='font-weight: bold;'>Email: </span><span class='admLabel'>".$customer['email']."</span><br />
		<span class='admLabel' style='font-weight: bold;'>�������: </span><span class='admLabel'>".$customer['phone']."</span>
	";

?>