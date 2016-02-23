<?php

	session_start();
	include('connect.php');
	
	$_SESSION['userLogin'] = $_POST['userLogin'];
	$_SESSION['userPassword'] = $_POST['userPassword'];
	
	if(!empty($_POST['userLogin']))
	{
		if(!empty($_POST['userPassword']))
		{
			$loginResult = mysql_query("SELECT * FROM users WHERE login = '".$_POST['userLogin']."'");
			$login = mysql_fetch_assoc($loginResult);
			
			if(!empty($login))
			{
				if(md5(md5($_POST['userPassword'])) == $login['password'])
				{
					$_SESSION['userID'] = $login['id'];
					$_SESSION['login'] = 1;
					setcookie("argosfm_login", $login['login'], time()+60*60*24*30*12, '/');
					setcookie("argosfm_password", $login['password'], time()+60*60*24*30*12, '/');

					if(isset($_SESSION['last_page']))
					{
						header("Location: ".$_SESSION['last_page']);
					}
					else
					{
						header("Location: ../index.php");
					}
				}
				else
				{
					$_SESSION['login'] = 'error';
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
			else
			{
				$_SESSION['login'] = 'error';
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
		else
		{
			$_SESSION['login'] = 'empty';
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
	else
	{
		$_SESSION['login'] = 'empty';
		if(isset($_SESSION['last_page']))
		{
			header("Location: ".$_SESSION['last_page']);
		}
		else
		{
			header("Location: ../index.php");
		}
	}

?>