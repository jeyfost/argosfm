<?php

	include('calendar.php');

	echo getDay(1, $_POST['month'], $_POST['year']).";".daysQuantity($_POST['month'], $_POST['year']);

?>