<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 16.11.2017
 * Time: 16:12
 */

include("../connect.php");

$presentGroup = $mysqli->real_escape_string($_POST['group']);

$groupResult = $mysqli->query("SELECT * FROM filters ORDER BY name");
while($group = $groupResult->fetch_assoc()) {
	echo "<option value='".$group['id']."'"; if($group['id'] == $presentGroup) {echo "selected";} echo ">".iconv("cp1251", "utf8", $group['name'])."</option>";
}