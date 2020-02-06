<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 24.11.2017
 * Time: 14:18
 */

session_start();

if($_SESSION['userID'] != 1) {
	header("Location: ../../index.php");
}

include("../connect.php");

$req = false;
ob_start();

$filesErrors = 0;
$filesCount = 0;

if(!empty($_FILES['emailFile']['tmp_name'][0])) {
	for($i = 0; $i < count($_FILES['emailFile']['name']); $i++) {
		if(empty($_FILES['emailFile']['tmp_name'][$i]) or $_FILES['emailFile']['error'][$i] != 0) {
			$filesErrors++;
		} else {
			$filesCount++;
		}
	}
}

if($filesErrors == 0) {
	$parameter = $mysqli->real_escape_string($_POST['parameter']);
	$region = $mysqli->real_escape_string($_POST['region']);
	$filter = $mysqli->real_escape_string($_POST['filter']);
	$subject = $mysqli->real_escape_string($_POST['emailTheme']);
	$name = iconv("cp1251", "utf8", "���� �����-�� ");
	$from = $name."<mail@argos-fm.by>";
	$reply = "mail@argos-fm.by";
	$text = iconv("utf8", "cp1251", stripslashes($_POST['text']));

	$hash = md5(date('r', time()));

	$headers = "From: ".$from."\nReply-To: ".$reply."\nMIME-Version: 1.0";
	$headers .= "\nContent-Type: multipart/mixed; boundary = \"PHP-mixed-".$hash."\"\n\n";

	$message = "--PHP-mixed-".$hash."\n";
	$message .= "Content-Type: text/html; charset=\"windows-1251\"\n";
	$message .= "Content-Transfer-Encoding: 8bit\n\n";
	$message .= $text."\n";
	$message .= "--PHP-mixed-".$hash."\n";

	if(!empty($_FILES['emailFile']['tmp_name'][0])) {
		for($i = 0; $i < count($_FILES['emailFile']['name']); $i++) {
			$attachment = chunk_split(base64_encode(file_get_contents($_FILES['emailFile']['tmp_name'][$i])));

			$message .= "Content-Type: application/octet-stream; name=".$_FILES['emailFile']['name'][$i]."\n";
			$message .= "Content-Transfer-Encoding: base64\n";
			$message .= "Content-Disposition: attachment\n\n";
			$message .= $attachment."\n";
			$message .= "--PHP-mixed-".$hash."\n";
		}
	}

	$baseMessage = $message;

	$count = 0;
	$start = $parameter * 10 - 10;

	switch ($region) {
		case "all":
			$mailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE in_send = '1' AND filter = '".$filter."' ORDER BY id LIMIT ".$start.", 10");
			$mailResult = $mysqli->query("SELECT * FROM mail WHERE in_send = '1' AND filter = '".$filter."' ORDER BY id LIMIT ".$start.", 10");
			break;
		default:
			$mailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE location = '".$region."' AND in_send = '1' AND filter = '".$filter."' ORDER BY id LIMIT ".$start.", 10");
			$mailResult = $mysqli->query("SELECT * FROM mail WHERE location = '".$region."' AND in_send = '1' AND filter = '".$filter."' ORDER BY id LIMIT ".$start.", 10");
			break;
	}

	$mailCount = $mailCountResult->fetch_array(MYSQLI_NUM);

	while($mail = $mailResult->fetch_assoc()) {
		$fullMessage = $baseMessage . "--PHP-mixed-" . $hash . "\n\n" . "--------------------\n\n���� ����������� ��������� ����� ���������� �� ����� �����: https://argos-fm.by\n� ����� �������� ������� �� ��������: +375 (222) 707-707 ��� �������� ��� �� ������: ���������� ��������, �. ������, ��. ���������, �.21\n\n�� ������ ���� �������������� � ����!\n\n--------------------\n\n���� �� �� ������ � ���������� �������� ��� ��������, �� ������ ����������, ������� �� ��������� ������: https://argos-fm.by/personal/unsubscribe.php?hash=" . $mail['hash'] . "\n\n��������! ������� �� �������������� �������� �������� �� ����� ������������� ������ ��� ������ �����������!\n\n\n";

		$fullMessage .= "--PHP-mixed-" . $hash . "\n";
		if (mail($mail['email'], $subject, $fullMessage, $headers)) {
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
} else {
	echo "files";
}

$req = ob_get_contents();
ob_end_clean();
echo json_encode($req);

exit;