<?php

include('connect.php');

$newsCountResult = $mysqli->query("SELECT COUNT(id) FROM news WHERE header LIKE '%".iconv('UTF-8', 'CP1251', $_POST['query'])."%' OR short LIKE '%".iconv('UTF-8', 'CP1251', $_POST['query'])."%' OR text LIKE '%".iconv('UTF-8', 'CP1251', $_POST['query'])."%'");
$newsCount = $newsCountResult->fetch_array(MYSQLI_NUM);

if($newsCount[0] > 0) {
    $count = 0;
    $newsResult = $mysqli->query("SELECT * FROM news WHERE header LIKE '%".iconv('UTF-8', 'CP1251', $_POST['query'])."%' OR short LIKE '%".iconv('UTF-8', 'CP1251', $_POST['query'])."%' OR text LIKE '%".iconv('UTF-8', 'CP1251', $_POST['query'])."%' ORDER BY date DESC LIMIT 10");

    while($news = $newsResult->fetch_assoc()) {
        $count++;
        echo "
            <a href='news.php?id=".$news['id']."' class='noBorder'>
                <div "; if($count % 2 == 0) {echo "class='searchGreyBG'";} else {echo "class='searchLine'";} echo " style='padding: 20px auto;'>
                    <span class='goodStyle'>".$news['header']."</span>
                    <br />
                    <span class='basic'><i>������������ ".substr($news['date'], 0, 10)."� ".substr($news['date'], 11)."</i></span>
                    <br /><br />
                    <span class='basic'><b>".$news['short']."</b></span>
                </div>
            </a>
        ";
    }
} else {
    echo "<span class='basic'>� ���������, ������ �������� �� �������.</span>";
}