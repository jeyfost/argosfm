<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 24.11.2017
 * Time: 13:55
 */

include("../connect.php");

$region = $mysqli->real_escape_string($_POST['region']);
$locationResult = $mysqli->query("SELECT * FROM locations ORDER BY id");
$filterResult = $mysqli->query("SELECT * FROM filters ORDER BY name");

echo "<br /><br /><label class='admLabel'>".iconv('cp1251', 'utf8', "Выберите область:")."</label><br /><select class='admSelect' name='regionSelect' id='regionSelect' onchange='selectRegionFilter()'><option value='all'"; if($region == "all") {echo " selected";} echo ">".iconv("cp1251", "utf8", "Все области")."</option>";

while($location = $locationResult->fetch_assoc()) {
	echo "<option value='".$location['id']."'"; if($location['id'] == $region) {echo " selected";} echo ">".iconv('cp1251', 'utf8', $location['name'])."</option>";
}

echo "</select><br /><br /><div id='responseField'></div><br /><span style='font-family: \"Roboto\", \"Myriad Set Pro\", Tahoma, sans-serif, \"Open Sans\", \"Lucida Grande\", \"Helvetica Neue\", \"Helvetica\", \"Arial\", \"Verdana\", Proxima; font-style:  normal; font-size: 15px; font-weight: normal;'>".iconv('cp1251', 'utf8', "Выберите группу:")."<br /><select class='admSelect' name='filterSelect' id='filterSelect' onchange='selectFilter()'><option value='0' selected>".iconv('cp1251', 'utf8', "- Выберите группу -")."</option>";

while ($filter = $filterResult->fetch_assoc()) {
	echo "<option value='".$filter['id']."'>".iconv('cp1251', 'utf8', $filter['name'])."</option>";
}

echo "</select><div id='buttonsField'></div><div style='clear: both;'></div><br />";