<?php

include("../connect.php");

$region = $mysqli->real_escape_string($_POST['region']);

$mailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE location = '".$region."' AND in_send = '1'");
$mailCount = $mailCountResult->fetch_array(MYSQLI_NUM);
$buttonsCount = ceil($mailCount[0] / 10);

$locationResult = $mysqli->query("SELECT * FROM locations ORDER BY id");

echo "<br /><br /><label class='admLabel'>Выберите область:</label><br /><select class='admSelect' name='addressGroupSelect' id='addressGroupSelect' onchange='selectRegion()'>";

while($location = $locationResult->fetch_assoc()) {
	echo "<option value='".$location['id']."'"; if($location['id'] == $region) {echo " selected";} echo ">".$location['name']."</option>";
}

echo "</select><br /><br /><div id='responseField'></div><span style='font-family: \"Roboto\", \"Myriad Set Pro\", Tahoma, sans-serif, \"Open Sans\", \"Lucida Grande\", \"Helvetica Neue\", \"Helvetica\", \"Arial\", \"Verdana\", Proxima; font-style:  normal; font-size: 15px; font-weight: normal;'>Отправить рассылку по группам:<br /><br />";

for($i = 0; $i < $buttonsCount; $i++) {
	$a = $i * 10 + 1;
	$b = $a + 9;
	$parameter = $i + 1;

	if($b > $mailCount[0]) {
		$b = $mailCount[0];
	}

	echo "<div class='sendEmailButton' id='eb".$parameter."' onclick='sendPartly(".$parameter.", ".$region.", \"eb".$parameter."\")'>".$a." - ".$b."</div><div style='position: relative; float: left; width: 10px; height: 40px;'></div>";
}

echo "<div style='clear: both;'></div><br />";