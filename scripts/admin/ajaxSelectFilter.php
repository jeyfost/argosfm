<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 24.11.2017
 * Time: 12:29
 */

include("../connect.php");

$region = $mysqli->real_escape_string($_POST['region']);
$filter = $mysqli->real_escape_string($_POST['filter']);

switch($region) {
	case "all":
		$mailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE in_send = '1' AND filter = '".$filter."'");
		break;
	default:
		$mailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE location = '".$region."' AND in_send = '1' AND filter = '".$filter."'");
		break;
}

$mailCount = $mailCountResult->fetch_array(MYSQLI_NUM);

if($mailCount[0] > 0) {
	$buttonsCount = ceil($mailCount[0] / 10);

	echo "<br /><br /><span style='font-family: \"Roboto\", \"Myriad Set Pro\", Tahoma, sans-serif, \"Open Sans\", \"Lucida Grande\", \"Helvetica Neue\", \"Helvetica\", \"Arial\", \"Verdana\", Proxima; font-style:  normal; font-size: 15px; font-weight: normal;'>".iconv('cp1251', 'utf8', "Отправить рассылку по группам:")."<br /><br />";

	for($i = 0; $i < $buttonsCount; $i++) {
		$a = $i * 10 + 1;
		$b = $a + 9;
		$parameter = $i + 1;

		if($b > $mailCount[0]) {
			$b = $mailCount[0];
		}

		echo "<div class='sendEmailButton' id='eb".$parameter."' onclick='sendFilter(".$parameter.", ".$region.", ".$filter.", \"eb".$parameter."\")'>".$a." - ".$b."</div><div style='position: relative; float: left; width: 10px; height: 40px; margin-top: 5px;'></div>";
	}

	echo "<div style='clear: both;'></div><br />";
} else {
	echo "<br /><br /><span style='color: #df4e47;'>".iconv("cp1251", "utf8", "Предприятий с заданными праметрами не найдено.")."</span>";
}