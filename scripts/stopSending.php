<?php

	include('connect.php');

?>

<!doctype html>

<html>

<head>
	<title>Отпиасьтя от рассылки</title>

	<link rel='shortcut icon' href='../pictures/icons/favicon.ico' type='image/x-icon'>
	<link rel='icon' href='../pictures/icons/favicon.ico' type='image/x-icon'>
    <link rel='stylesheet' media='screen' type='text/css' href='../css/style.css'>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="../js/mail.js"></script>
	<script type="text/javascript" src="../js/notify.js"></script>
</head>

<body>
	<?php
		if(!empty($_REQUEST['hash']))
		{
			$emailResult = $mysqli->query("SELECT * FROM mail WHERE hash = '".$mysqli->real_escape_string($_REQUEST['hash'])."' AND in_send = '1'");
			$email = $emailResult->fetch_assoc();

			if(!empty($email))
			{
				echo "
					<input type='button' id='unsubscribe' onclick='unsubscribe(\"".$email['id']."\")' value='Отписаться от рыссылки ЧТУП &laquo;Аргос-ФМ&raquo;'></button>
				";
			}
			else
			{
				echo "<div style='margin-top: 80px; width: 100%; text-align: center;'><span style='font-size: 24px;'>Адрес не был найден в базе данных.</span></div>";
			}
		}
		else
		{
			echo "<div style='margin-top: 80px; width: 100%; text-align: center;'><span style='font-size: 24px;'>Адрес не был найден в базе данных.</span></div>";
		}
	?>
</body>

</html>