<?php

include ('../connect.php');

echo "<br /><br /><label class='admLabel'>Выберите область:</label><br /><select class='admSelect' name='addressGroupSelect' id='addressGroupSelect'>";

$locationResult = $mysqli->query("SELECT * FROM locations ORDER BY id");
while($location = $locationResult->fetch_assoc()) {
    echo "<option value='".$location['id']."'"; if($location['id'] == 6) {echo " selected";} echo ">".$location['name']."</option>";
}

echo "</select>";