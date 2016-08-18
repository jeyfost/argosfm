<?php

session_start();

if($_SESSION['userID'] != 1) {
    header("Location: ../../index.php");
}

include('../connect.php');

$emailResult = $mysqli->query("SELECT * FROM mail_failed WHERE mail_id = '".$_POST['id']."'");

if($emailResult->num_rows > 0) {
    $groupResult = $mysqli->query("SELECT send_to FROM mail_result WHERE id = '".$_POST['id']."'");
    $group = $groupResult->fetch_array(MYSQLI_NUM);

    $groupMatch = 0;
    $provincesResult = $mysqli->query("SELECT * FROM locations");
    while($provinces = $provincesResult->fetch_assoc()) {
        if($provinces['id'] == $group[0]) {
            $groupMatch++;
        }
    }

    if($groupMatch > 0 or $group[0] == "all") {
        $count = 0;

        while ($email = $emailResult->fetch_assoc()) {
            $count++;

            $addressResult = $mysqli->query("SELECT * FROM mail WHERE id = '".$email['client_id']."'");
            $address = $addressResult->fetch_assoc();

            $provinceResult = $mysqli->query("SELECT name FROM locations WHERE id = '".$address['location']."'");
            $province = $provinceResult->fetch_array(MYSQLI_NUM);

            echo "<span style='color: #df4e47;'>".$count."</span>. Email: <span style='color: #df4e47;'>".$address['email']."</span> | Имя/Организация: <span style='color: #df4e47;'>"; if(empty($address['name'])) {echo "имя/название отсутствует";} else {echo $address['name'];} echo "</span> | Область: <span style='color: #df4e47;'>".$province[0]."</span><br /><br />";
        }
    } else {
        echo $group[0];
    }
}