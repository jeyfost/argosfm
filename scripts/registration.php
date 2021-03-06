<?php

	session_start();
	include('connect.php');
	
	if($_POST['userType'] === 'organisation')
	{
		if(!empty($_POST['userLogin']) and !empty($_POST['userPassword']) and !empty($_POST['userEmail']) and !empty($_POST['organisation']) and !empty($_POST['userName']) and !empty($_POST['userPhone']))
		{
			$login = trim(htmlspecialchars($_POST['userLogin']));
			$password = $_POST['userPassword'];
			$email = strtolower($_POST['userEmail']);
			$organisation = mysqli_real_escape_string($mysqli, $_POST['organisation']);
			$name = mysqli_real_escape_string($mysqli, $_POST['userName']);
			$phone = mysqli_real_escape_string($mysqli, $_POST['userPhone']);
			
			$_SESSION['registration_type'] = '1';
			$_SESSION['registration_login'] = $login;
			$_SESSION['registration_password'] = $password;
			$_SESSION['registration_email'] = $email;
			$_SESSION['registration_organisation'] = $organisation;
			$_SESSION['registration_name'] = $name;
			$_SESSION['registration_phone'] = $phone;
			
			if(preg_match("~^[a-zA-Z0-9_.-]{3,32}~u", $login))
			{
				if(strlen($password) >= 5)
				{
					$loginResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE login = '".$login."'");
					$L = $loginResult->fetch_array(MYSQLI_NUM);
					
					if($L[0] == 0)
					{
						if(filter_var($email, FILTER_VALIDATE_EMAIL))
						{
							$emailResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE email = '".$email."'");
							$E = $emailResult->fetch_array(MYSQLI_NUM);
							
							if($E[0] == 0)
							{
								$symbols = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'n', 'm', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M');
								$organisationResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE organisation = '".$organisation."'");
								$O = $organisationResult->fetch_array(MYSQLI_NUM);
								
								if($O[0] == 0)
								{
									$phoneResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE phone = '".$phone."'");
									$P = $phoneResult->fetch_array(MYSQLI_NUM);
									
									if($P[0] == 0)
									{
										$hash = '';
										
										for($i = 0; $i < 64; $i++)
										{
											$j = rand(0, count($symbols));
											$hash .= $symbols[$j];
										}
										
										$password = md5(md5($password));
										
										if($mysqli->query("INSERT INTO users (login, password, email, hash, organisation, person, phone, activated) VALUES ('".$login."', '".$password."', '".$email."', '".$hash."', '".$organisation."', '".$name."', '".$phone."', '0')"))
										{
											$code = "";

											for($i = 0; $i < 32; $i++)
											{
												$number = rand(0, count($symbols) - 1);
												$code .= $symbols[$number];
											}

											sendMail($email, $hash);
											$_SESSION['registration'] = 'ok';
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
											$_SESSION['registration'] = 'failed';
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
										$_SESSION['registration'] = 'phone_d';
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
									$_SESSION['registration'] = 'organisation_d';
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
								$_SESSION['registration'] = 'email_d';
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
							$_SESSION['registration'] = 'email';
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
						$_SESSION['registration'] = 'login_d';
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
					$_SESSION['registration'] = 'password';
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
				$_SESSION['registration'] = 'login';
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
			$_SESSION['registration'] = 'empty';
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
		if(!empty($_POST['userLogin']) and !empty($_POST['userPassword']) and !empty($_POST['userEmail']) and !empty($_POST['userName']) and !empty($_POST['userPhone']))
		{
			$login = trim(htmlspecialchars($_POST['userLogin']));
			$password = $_POST['userPassword'];
			$email = strtolower($_POST['userEmail']);
			$name = addslashes($_POST['userName']);
			$phone = addslashes($_POST['userPhone']);
			
			$_SESSION['registration_type'] = '2';
			$_SESSION['registration_login'] = $login;
			$_SESSION['registration_password'] = $password;
			$_SESSION['registration_email'] = $email;
			$_SESSION['registration_name'] = $name;
			$_SESSION['registration_phone'] = $phone;
			
			if(preg_match("~^[a-zA-Z0-9_.-]{3,32}~u", $login))
			{
				if(strlen($password) >= 5 and strlen($password) <= 25)
				{
					$loginResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE login = '".$login."'");
					$L = $loginResult->fetch_array(MYSQLI_NUM);
					
					if($L[0] == 0)
					{
						if(filter_var($email, FILTER_VALIDATE_EMAIL))
						{
							$emailResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE email = '".$email."'");
							$E = $emailResult->fetch_array(MYSQLI_NUM);
							
							if($E[0] == 0)
							{
								$symbols = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'n', 'm', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M');

								$phoneResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE phone = '".$phone."'");
								$P = $phoneResult->fetch_array(MYSQLI_NUM);
								
								if($P[0] == 0)
								{
									$hash = '';
										
									for($i = 0; $i < 64; $i++)
									{
										$j = rand(0, count($symbols));
										$hash .= $symbols[$j];
									}
									
									$password = md5(md5($password));
										
									if($mysqli->query("INSERT INTO users (login, password, email, hash, organisation, person, phone, activated) VALUES ('".$login."', '".$password."', '".$email."', '".$hash."', '', '".$name."', '".$phone."', '0')"))
									{
										$code = "";

										for($i = 0; $i < 32; $i++)
										{
											$number = rand(0, count($symbols) - 1);
											$code .= $symbols[$number];
										}

										sendMail($email, $hash);

										$_SESSION['registration'] = 'ok';
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
										$_SESSION['registration'] = 'failed';
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
									$_SESSION['registration'] = 'phone_d';
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
								$_SESSION['registration'] = 'email_d';
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
							$_SESSION['registration'] = 'email';
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
						$_SESSION['registration'] = 'login_d';
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
					$_SESSION['registration'] = 'password';
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
				$_SESSION['registration'] = 'login';
				if(isset($_SESSION['last_page']))
				{
					header("Location: ".$_SESSION['last_page']);
				}
				else
				{
					header("Location: ../index.php");
				}
			}
			
			if(strlen($password) < 5)
			{
				$_SESSION['registration'] = 'password';
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
			$_SESSION['registration'] = 'empty';
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
	
	function sendMail($address, $code)
	{
		$to = $address;
		
		$headers = "Content-type=text/html; charset: windows-1251 \r\n";
		$headers .= "From: ������������� ����� �����-�� <no-reply@argos-fm.by>\r\n";
		
		$subject = "����������� �� ����� �����-��";
		$message = "������������!<br /><br />����� ����� ����������� ����� ��� ������ ��� ����������� �� ����� <a href='http://argos-fm.by/'>argos-fm.by</a>. ��� ���������� ����������� ���������, ����������, �� ��������� ������: <a href='argos-fm.by/scripts/confirm.php?h='".$code."'>argos-fm.by/scripts/confirm.php?h='".$code."</a><br /><br />���� �� �� ���������������� �� �����, � ���-�� �� ������ ��� ��������� ������ ����� ����� �����, ��������� �� ��������� ������, ����� ������������ �����������: <a href='argos-fm.by/scripts/cancel.php?h=".$code."'>argos-fm.by/scripts/cancel.php?h=".$code."</a>";
		
		mail($to, $subject, $message, $headers);
	}

?>