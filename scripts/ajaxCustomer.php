<?php

	include('connect.php');

	$customerResult = mysql_query("SELECT * FROM users WHERE id = '".$_POST['id']."'");
	$customer = mysql_fetch_assoc($customerResult);

	echo "
		<center>
			<span class='admLabel'>Информация о заказчике</span>
			<br /><br />
		</center>
	";

	if(!empty($customer['organisation']))
	{
		echo "
			<span class='admLabel' style='font-weight: bold;'>Название организации: </span><span class='admLabel'>".$customer['organisation']."</span><br />
		";
	}
	
	echo "
		<span class='admLabel' style='font-weight: bold;'>Контактное лицо: </span><span class='admLabel'>".$customer['person']."</span><br />
		<span class='admLabel' style='font-weight: bold;'>Email: </span><span class='admLabel'>".$customer['email']."</span><br />
		<span class='admLabel' style='font-weight: bold;'>Телефон: </span><span class='admLabel'>".$customer['phone']."</span>
	";

?>