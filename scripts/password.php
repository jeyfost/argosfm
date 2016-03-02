<?php

session_start();
include('connect.php');

if(!empty($_REQUEST['hash']))
{
    $userCountResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE hash = '".$_REQUEST['hash']."'");
    $userCount = $userCountResult->fetch_array(MYSQLI_NUM);

    if($userCount[0] == 0) {
        $_SESSION['recovery_final'] = 'empty';
        if(isset($_SESSION['last_page']))
        {
            header("Location: ".$_SESSION['last_page']);
        }
        else
        {
            header("Location: ../index.php");
        }
    } else {
        $symbols = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M');
        $password = '';

        for($i = 0; $i < 10; $i++)
        {
            $number = mt_rand(0, count($symbols) - 1);
            $password .= $symbols[$number];
        }

        if($mysqli->query("UPDATE users SET password = '".md5(md5($password))."' WHERE hash = '".$_REQUEST['hash']."'")) {
            $emailResult = $mysqli->query("SELECT email FROM users WHERE hash = '".$_REQUEST['hash']."'");
            $email = $emailResult->fetch_array(MYSQLI_NUM);

            sendMail($email[0], $password);

            $_SESSION['recovery_final'] = 'ok';
            $_SESSION['recovery_email'] = $email[0];

            if(isset($_SESSION['last_page']))
            {
                header("Location: ".$_SESSION['last_page']);
            }
            else
            {
                header("Location: ../index.php");
            }
        } else {
            $_SESSION['recovery_final'] = 'failed';
            if(isset($_SESSION['last_page']))
            {
                header("Location: ".$_SESSION['last_page']);
            }
            else
            {
                header("Location: ../index.php");
            }
        }
    }
}
else
{
    $_SESSION['recovery_final'] = 'empty';
    if(isset($_SESSION['last_page']))
    {
        header("Location: ".$_SESSION['last_page']);
    }
    else
    {
        header("Location: ../index.php");
    }
}

function sendMail($address, $new_password)
{
    $to = $address;

    $subject = "Восстановление пароля на сайте Аргос-ФМ";
    $message = "Ваш пароль на сайте <a href='http://argos-fm.by/'>argos-fm.by</a> был изменён.<br />Новый пароль: <b>".$new_password."</b><br /><br />Изменить пароль можно в личном кабинете, предварительно авторизовавшись на сайте.";

    $headers = "Content-type: text/html; charset=windows-1251 \r\n";
    $headers .= "From: Администрация сайта Аргос-ФМ <no-reply@argos-fm.by>\r\n";

    mail($to, $subject, $message, $headers);
}