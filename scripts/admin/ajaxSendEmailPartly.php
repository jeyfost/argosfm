<?php

session_start();

if($_SESSION['userID'] != 1) {
	header("Location: ../../index.php");
}

include("../connect.php");

$parameter = $mysqli->real_escape_string($_POST['parameter']);
$region = $mysqli->real_escape_string($_POST['region']);
$subject = $mysqli->real_escape_string($_POST['subject']);
$text = iconv("utf8", "cp1251", $_POST['text']);

$name = iconv("cp1251", "utf8", "���� ����� �� ");
$from = $name."<mail@argos-fm.by>";
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
$start = $parameter * 10 - 10;

$mailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE location = '".$region."' AND in_send = '1' ORDER BY id LIMIT ".$start.", 10");
$mailCount = $mailCountResult->fetch_array(MYSQLI_NUM);

$mailResult = $mysqli->query("SELECT * FROM mail WHERE location = '".$region."' AND in_send = '1' ORDER BY id LIMIT ".$start.", 10");
while($mail = $mailResult->fetch_assoc()) {
	$fullMessage = $baseMessage."--PHP-mixed-".$hash."\n\n"."--------------------\n\n���� ����������� ��������� ����� ���������� �� ����� �����: www.argos-fm.by\n� ����� �������� ������� �� ��������: +375 (222) 707-707 ��� �������� ��� �� ������: ���������� ��������, �. ������, ��. ���������, �.21\n\n�� ������ ���� �������������� � ����!\n\n���� �� �� ������ � ���������� �������� ��� ��������, �� ������ ����������, ������� �� ��������� ������: www.argos-fm.by/test/scripts/stopSending.php?hash=".$mail['hash']."\n";

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