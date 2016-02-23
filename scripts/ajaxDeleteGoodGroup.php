<?php

	include('connect.php');

	if(!empty($_POST['orderID']) and !empty($_POST['goodID']))
	{
		if(mysql_query("DELETE FROM orders WHERE order_id = '".$_POST['orderID']."' AND good_id = '".$_POST['goodID']."'"))
		{
			echo "a";
		}
		else
		{
			echo "b";
		}
	}
	else
	{
		echo "b";
	}

?>