<?php

	include('connect.php');

	$customerResult = mysql_query("SELECT * FROM users WHERE id = '".$_POST['id']."'");
	$customer = mysql_fetch_assoc($customerResult);

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