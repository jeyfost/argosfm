<?php

session_start();

if($_SESSION['userID'] != 1) {
	header("Location: ../../index.php");
}

include("../connect.php");

$parameter = $mysqli->real_escape_string($_POST['parameter']);
$region = $mysqli->real_escape_string($_POST['region']);
$subject = $mysqli->real_escape_string($_POST['subject']);
$text = $_POST['text'];

$from = "ЧТУП Аргос-ФМ <mail@argos-fm.by>";
$reply = "argos-fm@mail.ru";
$hash = md5(date('r', time()));
$headers = "From: ".$from."\nReply-To: ".$reply."\nMIME-Version: 1.0";
$headers .= "\nContent-Type: multipart/mixed; boundary = \"PHP-mixed-".$hash."\"\n\n";

$message = "--PHP-mixed-".$hash."\n";
$message .= "Content-Type: text/html; charset=\"windows-1251\"\n";
$message .= "Content-Transfer-Encoding: 8bit\n\n";
$message .= $text."\n";
$message .= "--PHP-mixed-".$hash."\n";

$baseMessage = $message;

$count = 0;

$mailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE location = '".$region."' AND in_send = '1'");
$mailCount = $mailCountResult->fetch_array(MYSQLI_NUM);

$start = $parameter * 10 - 10;

$mailResult = $mysqli->query("SELECT * FROM mail WHERE location = '".$region."' AND in_send = '1' ORDER BY id LIMIT ".$start.", 10");
while($mail = $mailResult->fetch_assoc()) {
	$fullMessage = $baseMessage."--PHP-mixed-".$hash."\n\n"."--------------------\n\nВесь ассортимент продукции можно посмотреть на нашем сайте: www.argos-fm.by\nА также уточнить наличие по телефону: +375 (222) 707-707 или посетить нас по адресу: Республика Беларусь, г. Могилёв, ул. Залуцкого, д.21\n\nМы всегда рады сотрудничеству с Вами!\n\nЕсли вы не хотите в дальнейшем получать эту рассылку, вы можете отписаться, перейдя по следующей ссылке: www.argos-fm.by/test/scripts/stopSending.php?hash=".$email['hash']."\n";
	$fullMessage .= "--PHP-mixed-".$hash."\n";

	if(mail($mail['email'], $subject, $fullMessage, $headers)) {
		$count++;
	}
}

if($count == 0) {
	echo "b";
}

if($count == $mailCount[0]) {
	echo "a";
}

if($count > 0 and $count < $mailCount[0]) {
	echo "c";
}