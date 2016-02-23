<?php

	include('connect.php');

	if(!empty($_REQUEST['hash']))
	{
		$emailResult = mysql_query("SELECT * FROM mail WHERE hash = '".htmlspecialchars($_REQUEST['hash'])."'");
		$email = mysql_fetch_assoc($emailResult);

		if(!empty($email))
		{
			if(mysql_query("UPDATE mail SET in_send = '0', disactivation_date = '".date("Y-m-d")."' WHERE hash = '".htmlspecialchars($_REQUEST['hash'])."'"))
			{
				echo "Вы были успешно отписаны от рассылки. Приносим свои извинения за доставленное беспокойство.";
			}
			else
			{
				echo "К сожалению, из-за неизвестной ошибки система не смогла удалить ваш адрес из базы данных. Для удаления свяжитесь с нами по телефону из письма. Приносим свои извинения за доставленное беспокойство.";
			}
		}
		else
		{
			echo "Адрес не был найден в базе данных.";
		}
	}
	else
	{
		echo "Адрес не был найден в базе данных.";
	}

?>