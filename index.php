<?php

	session_start();
	include('scripts/connect.php');

	if(isset($_SESSION['userID']))
	{
		if(isset($_COOKIE['argosfm_login']) and isset($_COOKIE['argosfm_password']))
		{
			setcookie("argosfm_login", "", 0, '/');
			setcookie("argosfm_password", "", 0, '/');unset($_SESSION['recovery']);
            unset($_SESSION['recovery_email']);
			setcookie("argosfm_login", $_COOKIE['argosfm_login'], time()+60*60*24*30*12, '/');
			setcookie("argosfm_password", $_COOKIE['argosfm_password'], time()+60*60*24*30*12, '/');
		}
		else
		{
			$userResult = $mysqli->query("SELECT * FROM users WHERE id = '".$_SESSION['userID']."'");
			$user = $userResult->fetch_assoc();
			setcookie("argosfm_login", $user['login'], time()+60*60*24*30*12, '/');
			setcookie("argosfm_password", $user['password'], time()+60*60*24*30*12, '/');
		}
	}
	else
	{
		if(isset($_COOKIE['argosfm_login']) and isset($_COOKIE['argosfm_password']) and !empty($_COOKIE['argosfm_login']) and !empty($_COOKIE['argosfm_password']))
		{
			$userResult = $mysqli->query("SELECT * FROM users WHERE login = '".$_COOKIE['argosfm_login']."'");
			$user = $userResult->fetch_assoc();
			
			if(!empty($user) and $user['password'] == $_COOKIE['argosfm_password'])
			{
				$_SESSION['userID'] = $user['id'];
			}
			else
			{
				setcookie("argosfm_login", "", 0, '/');
				setcookie("argosfm_password", "", 0, '/');
			}
		}
	}

?>

<!doctype html>

<html>

<head>

    <meta charset="windows-1251">
    <meta name='keywords' content='��������� ���������, ������������� ��� ������, �����-��, ������������� ��� ������ �������, �������, ��������� ���������, ������, ������ ���, ����� ���������, ��������� ��������� �������, ������ ��� �������, ����� ���������, ����� ��������� �������, ����� ��������� �������, ������ �������'>
    <meta name='description' content='����������� �������� ���� ����� ��������� ��������� ���������� � �������������� ������������. ���������� ��������, �. ������.'>
    
    <title>�����-�� | �������</title>
    
    <link rel='shortcut icon' href='pictures/icons/favicon.ico' type='image/x-icon'>
	<link rel='icon' href='pictures/icons/favicon.ico' type='image/x-icon'>
    <link rel='stylesheet' media='screen' type='text/css' href='css/style.css'>
    <link rel='stylesheet' type='text/css' href='js/shadowbox/source/shadowbox.css'>
    <?php
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false)
		{
			echo "<link rel='stylesheet' media='screen' type='text/css' href='css/styleOpera.css'>";
		}
	?>
    
    <script type='text/javascript' src='js/menu.js'></script>
    <script type='text/javascript' src='js/footer.js'></script>
    <script type='text/javascript' src='js/jquery-1.8.3.min.js'></script>
    <script type='text/javascript' src='js/ajax.js'></script>
    <script type='text/javascript' src='js/shadowbox/source/shadowbox.js'></script>

    <?php
    	if(!isset($_SESSION['background']))
		{
			$BGCountResult = $mysqli->query("SELECT COUNT(id) FROM background");
			$BGCount = $BGCountResult->fetch_array(MYSQLI_NUM);

			$index = rand(1, $BGCount[0]);

			$backgroundResult = $mysqli->query("SELECT photo FROM background WHERE id = '".$index."'");
			$background = $backgroundResult->fetch_array(MYSQLI_NUM);

			$_SESSION['background'] = $background[0];
		}

    	echo "
    		<style>
    			html {
    					background: url(pictures/background/".$_SESSION['background'].") no-repeat center center fixed;
    					-webkit-background-size: cover;
					    -moz-background-size: cover;
					    -o-background-size: cover;
					    background-size: cover;
						padding: 0px;
						margin: 0px;
						height: 100%;
    				}
    		</style>
    	";
    ?>

</head>

<body onresize = 'footerPos()'>

	<div id='layout' <?php if((isset($_SESSION['login']) and $_SESSION['login'] != 1) or isset($_SESSION['recovery']) or isset($_SESSION['registration']) or isset($_SESSION['activation']) or isset($_SESSION['activationFalse']) or isset($_SESSION['registration_cancel']) or isset($_SESSION['delete']) or isset($_SESSION['basket'])) {echo "style='display: block;'";} else {echo "style='display: none;'";} ?> onclick='resetBlocks();' onmousemove='resizeLayout()' onmousewheel='resizeLayout()'></div>
    
    <?php
		if(!empty($_SESSION['recovery']) and $_SESSION['recovery'] == 'sent')
		{
			echo "
				<div id='notificationWindowOuter' style='display: block;'>
					<div id='notificationWindow'>
						<form id='recoveryNotificationForm'>
							<center><span class='headerStyleRed'>�������������� ������</span></center>
							<br /><br />
							<span class='basic'>��� ������ ��� ������. ����� ������ ����� ������, �������� ������ �� ������, ���������� ��� �����������: <b>".$_SESSION['recovery_email']."</b></span>
							<br /><br />
							<center><input type='button' class='windowSubmit' onclick='closeNotification()' value='OK' id='loginCancel' style='float: none;' /></center>
						</form>
					</div>
				</div>
			";
		}
		
		if(isset($_SESSION['basket']))
		{
			echo "
				<div id='notificationWindowOuter' style='display: block;'>
					<div id='notificationWindow'>
						<form id='recoveryNotificationForm'>
							<center><span class='headerStyleRed'>������� �����</span></center>
							<br /><br />
							<span class='basic'>�� ������ ������ ���� ������� �����. ��� ���������� ������ �������� � �� ����� �� </span><a href='catalogue.php' title='������� � �������'><span class='basic' style='text-decoration: underline; color: #3e94fe;'>��������</span></a><span class='basic'>.</span>
							<br /><br />
							<center><input type='button' class='windowSubmit' onclick='closeNotification()' value='OK' id='loginCancel' style='float: none;' /></center>
						</form>
					</div>
				</div>
			";
			unset($_SESSION['basket']);
		}
		
		if(isset($_SESSION['activationFalse']) and $_SESSION['activationFalse'] == 'no')
		{
			echo "
				<div id='notificationWindowOuter' style='display: block;'>
					<div id='notificationWindow'>
						<form id='recoveryNotificationForm'>
							<center><span class='headerStyleRed'>������� �� �����������.</span></center>
							<br /><br />
							<span class='basic'>�� �� ������� �������� �� �������� ������ �������� � ��������� ������-������ �� ��� ���, ���� �� ��������� �� ����� ���������. ��� ����� ��������� ���� ����������� �����.</span>
							<br /><br />
							<center><input type='button' class='windowSubmit' onclick='closeNotification()' value='OK' id='loginCancel' style='float: none;' /></center>
						</form>
					</div>
				</div>
			";
			unset($_SESSION['activationFalse']);
		}
		
		if(isset($_SESSION['delete']))
		{
			echo "
				<div id='notificationWindowOuter' style='display: block;'>
					<div id='notificationWindow'>
						<form id='recoveryNotificationForm'>
							<center><span class='headerStyleRed'>�������� ��������</span></center>
							<br /><br />
							<span class='basic'>��� ������� ��� ������� �����. ������ �� �� ������� ��������� ������-������.</span>
							<br /><br />
							<center><input type='button' class='windowSubmit' onclick='closeNotification()' value='OK' id='loginCancel' style='float: none;' /></center>
						</form>
					</div>
				</div>
			";
			unset($_SESSION['delete']);
		}
		
		if(isset($_SESSION['activation']))
		{
			echo "
				<div id='notificationWindowOuter' style='display: block;'>
					<div id='notificationWindow'>
						<form id='recoveryNotificationForm'>
							<center><span class='headerStyleRed'>���������� �����������</span></center>
							<br /><br />
			";
			
			switch($_SESSION['activation'])
			{
				case "ok":
					echo "<span class='basic'>����������� ��������� �������. ��� ������� �����������.</span>";
					break;
				case "failed":
					echo "<span class='basicRed'>��� ��������� �������� ��������� ������. ���������� �����.</span>";
					break;
				case "hash":
					echo "<span class='basicRed'>�� ������� �� �������������� ������ ��� ��������� ��������. ��������� �� ������, ��������� �� ���� ����������� �����.</span>";
					break;
				case "no_activation":
					echo "<span class='basicRed'>���� ������� ������ ��� �� ������������. �� �� ������� �������� � ��������� �� ��� ���, ���� �� ��������� �� ����� ���������. ��� ����� ��������� ���� ����������� �����.</span>";
					break;
				default:
					break;
			}
			
			echo "
							<br /><br />
							<center><input type='button' class='windowSubmit' onclick='closeNotification()' value='OK' id='loginCancel' style='float: none;' /></center>
						</form>
					</div>
				</div>
			";
			
			unset($_SESSION['activation']);
		}
		
		if(!empty($_SESSION['registration_cancel']))
		{
			echo "
				<div id='notificationWindowOuter' style='display: block;'>
					<div id='notificationWindow'>
						<form id='recoveryNotificationForm'>
							<center><span class='headerStyleRed'>������ �����������</span></center>
							<br /><br />
			";
			
			switch($_SESSION['registration_cancel'])
			{
				case "ok":
					echo "<span class='basic'>����������� �������� �������. ������� � ������� ����� ����������� ����� �����.</span>";
					break;
				case "failed":
					echo "<span class='basicRed'>��� ������������� ����������� ��������� ������. ���������� �����.</span>";
					break;
				case "hash":
					echo "<span class='basicRed'>�� ������� �� �������������� ������ ��� ������������� ��������. ��������� �� ������, ��������� �� ���� ����������� �����.</span>";
					break;
				default:
					break;
			}
			
			echo "
							<br /><br />
							<center><input type='button' class='windowSubmit' onclick='closeNotification()' value='OK' id='loginCancel' style='float: none;' /></center>
						</form>
					</div>
				</div>
			";
			
			unset($_SESSION['registration_cancel']);
		}
		
		if(isset($_SESSION['registration']) and $_SESSION['registration'] == 'ok')
		{
			echo "
				<div id='notificationRegistrationWindowOuter' style='display: block;'>
					<div id='notificationRegistrationWindow'>
						<form id='registrationNotificationForm'>
							<center><span class='headerStyleRed'>����������� ���������!</span></center>
							<br /><br />
							<span class='basic'>�����������! �� ������� ������������������. ������ �� ������ ��������� ������-������ � </span><span class='basicRed'><a href='catalogue.php' class='noBorder'>��������</a></span><span class='basic'>.</span>
							<br /><br />
							<center><input type='button' class='windowSubmit' onclick='closeNotification()' value='OK' id='loginCancel' style='float: none;' /></center>
						</form>
					</div>
				</div>
			";

            unset($_SESSION['registration']);
		}
	?>
    	<div id='registrationWindow' onmousemove='resizeLayout()' onmousewheel='resizeLayout()' <?php if(isset($_SESSION['registration']) and $_SESSION['registration'] != 'ok'){echo "style='display: block;'";}else{echo "style='display: none;'";} ?>>
        	<form name='registrationForm' id='registrationForm' method='post' action='scripts/registration.php'>
            	<center><span class='headerStyleRed'>����������� ������ ������������</span></center>
                <br /><br />
				<?php
				if(isset($_SESSION['registration']) and $_SESSION['registration'] != 'ok')
				{
					switch($_SESSION['registration'])
					{
						case "failed":
							echo "<div class='notification'><span class='basicRed'>��� ����������� ��������� ������. ���������� �����.</span></div><br />";
							break;
						case "empty":
							echo "<div class='notification'><span class='basicRed'>��� ����������� ���������� ��������� ��� ����.</span></div><br />";
							break;
						case "login":
							echo "<div class='notification'><span class='basicRed'>����� ������ ������ ���������� �� 3 �� 25 ��������. ����������� �� �����������.</span></div><br />";
							break;
						case "password":
							echo "<div class='notification'><span class='basicRed'>����� ������ ������ ���������� �� 5 �� 25 ��������.</span></div><br />";
							break;
						case "email":
							echo "<div class='notification'><span class='basicRed'>����� ������������ e-mail.</span></div><br />";
							break;
						case "login_d":
							echo "<div class='notification'><span class='basicRed'>�������� ���� ����� ��� ����������.</span></div><br />";
							break;
						case "email_d":
							echo "<div class='notification'><span class='basicRed'>�������� ���� e-mail ��� ����������.</span></div><br />";
							break;
						case "organisation_d":
							echo "<div class='notification'><span class='basicRed'>�������� ���� �������� ����������� ��� ����������.</span></div><br />";
							break;
						case "phone_d":
							echo "<div class='notification'><span class='basicRed'>�������� ���� ����� �������� ��� ����������.</span></div><br />";
							break;
						default:
							break;
					}

					echo "<br />";
				}
				?>
                <label>��� ������������:</label>
                <br />
                <input type='radio' name='userType' value='organisation' class='radio' onclick='registrationType(1)' <?php if(isset($_SESSION['registration_type'])){if($_SESSION['registration_type'] == '1'){echo "checked";}}else{echo "checked";} ?>><span class='mainIMGText'>����������� ��� ��</span><br />
                <input type='radio' name='userType' value='person' class='radio' onclick='registrationType(2)' <?php if(isset($_SESSION['registration_type']) and $_SESSION['registration_type'] == '2'){echo "checked";} ?>><span class='mainIMGText'>���������� ����</span><br />
                <br />
                <label>�����:</label>
                <br />
                <input type='text' class='admInput' name='userLogin' id='userLoginInput' <?php if(isset($_SESSION['registration_login'])){echo " value='".$_SESSION['registration_login']."'";} ?> />
                <br /><br />
                <label>������:</label>
                <br />
                <input type='password' class='admInput' name='userPassword' id='userPasswordInput' <?php if(isset($_SESSION['registration_password'])){echo " value='".$_SESSION['registration_password']."'";} ?> />
                <br /><br />
                <label>E-mail:</label>
                <br />
                <input type='text' class='admInput' name='userEmail' id='userEmailInput' <?php if(isset($_SESSION['registration_email'])){echo " value='".$_SESSION['registration_email']."'";} ?> />
                <?php
					if((isset($_SESSION['registration_type']) and $_SESSION['registration_type'] == 1) or !isset($_SESSION['registration_type']))
					{
						echo "
							<br /><br />
							<label>�������� �����������:</label>
							<br />
							<input type='text' class='admInput' name='organisation' id='organisationInput' "; if(isset($_SESSION['registration_organisation'])){echo " value='".$_SESSION['registration_organisation']."'";} echo "/>";
					}
				?>
                <br /><br />
                <label>���������� ����:</label>
                <br />
                <input type='text' class='admInput' name='userName' id='userNameInput' <?php if(isset($_SESSION['registration_name'])){echo " value='".$_SESSION['registration_name']."'";} ?> />
                <br /><br />
                <label>���������� �������:</label>
                <br />
                <input type='text' class='admInput' name='userPhone' id='userPhoneInput' <?php if(isset($_SESSION['registration_phone'])){echo " value='".$_SESSION['registration_phone']."'";} ?> />
				<br /><br />
                <input type='submit' class='windowSubmit' value='������������������' id='registrationSubmit' />
                <input type='button' class='windowSubmit' value='������' id='loginCancel' onclick='resetBlocks();' />
            </form>
            
            <?php
				
				unset($_SESSION['registration']);
				unset($_SESSION['registration_type']);
				unset($_SESSION['registration_login']);
				unset($_SESSION['registration_password']);
				unset($_SESSION['registration_email']);
				unset($_SESSION['registration_organisation']);
				unset($_SESSION['registration_name']);
				unset($_SESSION['registration_phone']);
				
			?>
        </div>
				

		<div id='passwordRecoveryBlock' onmousemove='resizeLayout()' onmousewheel='resizeLayout()' <?php if(!empty($_SESSION['recovery']) and $_SESSION['recovery'] != 'sent'){echo "style='display: block;'";}else{echo "style='display: none;'";} ?>>
			<form name='passwordRecoveryForm' id='passwordRecoveryForm' method='post' action='scripts/recovery.php'>
				<center><span class='headerStyleRed'>�������������� ������</span></center>
				<br /><br />
				<?php
				switch($_SESSION['recovery'])
				{
					case "empty":
						echo "<div class='notification'><span class='basicRed'>�� �� ����� ���� ����� ��� e-mail.</span></div><br />";
						break;
					case "login":
						echo "<div class='notification'><span class='basicRed'>�� ����� �������������� ����� ��� e-mail.</span></div><br />";
						break;
					default:
						break;
				}
				?>
				<label>������� ����� ��� e-mail, ��������� ��� �����������:</label>
				<br />
				<input type='text' class='admInput' name='recovery' id='recoveryInput' <?php if(isset($_SESSION['recovery_email'])) {echo " value=".$_SESSION['recovery_email'];} ?> />
				<br /><br />
                <input type='submit' class='windowSubmit'  value='����������' id='loginSubmit' />
                <input type='button' class='windowSubmit' value='������' id='loginCancel' onclick='resetBlocks();' />
			</form>
			<?php

                unset($_SESSION['recovery_email']);
                unset($_SESSION['recovery']);

            ?>
		</div>
    
	<header>
    	<div id='headerBlock'>
        	<a href='index.php' class='noBorder'>
                <div id='logo'>
                    <img src='pictures/system/logo.png' class='noBorder' id='logoIMG' onmouseover='logoChange(1)' onmouseout='logoChange(0)' />
                </div>
            </a>
            <menu>
            	<a href='index.php' class='noBorder' title='��������� �� ������� ��������'>
                    <div id='mainPoint'>
                        <div id='mainPointCenter'>
                            <div id='mpTopActive'></div>
                            <div class='pBottom'>
                                <img src='pictures/system/mainTextRed.png' id='mpIMG' class='noBorder'>
                            </div>
                        </div>
                    </div>
                </a>
                <a href='catalogue.php' class='noBorder' title='������� ��������� ��������� � �������������'>
                    <div id='cataloguePoint' onmouseover='menuVisual("1", "cpIMG", "cpTop")' onmouseout='menuDefault2()'>
                        <div id='cataloguePointCenter'>
                            <div id='cpTop'></div>
                            <div class='pBottom'>
                                <img src='pictures/system/catalogueText.png' id='cpIMG' class='noBorder'>
                            </div>
                        </div>
                    </div>
                </a>
                <a href='news.php' class='noBorder' title='�������, ����� � ������������ �����������'>
                    <div id='offersPoint' onmouseover='menuVisual("1", "opIMG", "opTop")' onmouseout='menuDefault3()'>
                        <div id='offersPointCenter'>
                            <div id='opTop'></div>
                            <div class='pBottom'>
                                <img src='pictures/system/newsText.png' id='opIMG' class='noBorder'>
                            </div>
                        </div>
                    </div>
                </a>
                <a href='contacts.php' class='noBorder' title='��� � ���� ���������'>
                    <div id='contactsPoint' onmouseover='menuVisual("1", "csIMG", "csTop")' onmouseout='menuDefault4()'>
                        <div id='contactsPointCenter'>
                            <div id='csTop'></div>
                            <div class='pBottom'>
                                <img src='pictures/system/contactsText.png' id='csIMG' class='noBorder'>
                            </div>
                        </div>
                    </div>
                </a>
            </menu>
            <div id='searchBlock'>
            	<?php
					if(empty($_SESSION['userID']))
					{
						echo "
							<div id='login' onmouseover='changeLoginIcon(1)' onmouseout='changeLoginIcon(0)' onclick='showLoginForm()'>
								<img src='pictures/system/login.png' class='noBorder' id='loginIcon' title='����� � ������ �������' />
							</div>

								<div id='loginBlock' onmousemove='resizeLayout()' onmousewheel='resizeLayout()' "; if(isset($_SESSION['login'])){echo "style='display: block;'";}else{echo "style='display: none;'";} echo ">
									<form name='loginForm' id='loginForm' method='post' action='scripts/login.php'>
										<center><span class='headerStyleRed'>�����������</span></center>
										";
										if(isset($_SESSION['login']))
										{
											switch($_SESSION['login'])
											{
												case 'error':
													echo "<div class='notification'><span class='basicRed'>�������� ��� ������������ ��� ������.</span></div><br />";
													break;
												case 'empty':
													echo "<div class='notification'><span class='basicRed'>��������� ��� ����.</span></div><br />";
													break;
												default:
													break;
											}
										}
										else
										{
											echo "<br /><br />";
										}
										echo "
										<label>�����:</label>
										<br />
										<input type='text' class='windowInput' id='userLogin' name='userLogin'"; if(isset($_SESSION['userLogin'])){echo "value='".$_SESSION['userLogin']."'";} echo " />
										<br /><br />
										<label>������:</label>
										<br />
										<input type='password' class='windowInput' id='userPassword' name='userPassword'"; if(isset($_SESSION['userPassword'])){echo "value='".$_SESSION['userPassword']."'";} echo " />
										<br /><br />
										<span class='redItalicHover' style='cursor: pointer;' onclick='registrationWindow();'>��� �� ����������������?</span>
										<br />
										<span class='redItalicHover' style='cursor: pointer;' onclick='recoveryWindow();'>������ ������?</span>
										<br /><br />
										<input type='submit' class='windowSubmit' value='�����' id='loginSubmit' class='button' />
										<input type='button' class='windowSubmit' value='������' id='loginCancel' onclick='resetBlocks();' />
									</form>
								</div>
						";
					}
					else
					{
						$userResult = $mysqli->query("SELECT * FROM users WHERE id = '".$_SESSION['userID']."'");
						$user = $userResult->fetch_assoc();

						echo "
							<div id='loginL'>
								<a href='settings.php?s=1' class='noBorder'><img src='pictures/system/user.png' class='noBorder' id='userIcon' title='".$user['login']." | ������������ ��������' onmouseover='changeUserIcon(1)' onmouseout='changeUserIcon(0)' /></a>
							";
						
						if($_SESSION['userID'] != 1)
						{	
							$ordersResult = $mysqli->query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND status = '0'");
							if($ordersResult->num_rows < 1)
							{
								echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basket.png' class='noBorder' id='basketIcon' title='�������' onmouseover='changeBasketIcon(1)' onmouseout='changeBasketIcon(0)' /></a>
								";
							}
							else
							{
								echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basketFull.png' class='noBorder' id='basketIcon' title='������� | ���������� �������: ".$ordersResult->num_rows."' onmouseover='changeBasketFullIcon(1)' onmouseout='changeBasketFullIcon(0)' /></a>
								";
							}
						}
						else
						{
							$ordersResult = $mysqli->query("SELECT * FROM orders_date WHERE status = '0'");
							if($ordersResult->num_rows < 1)
							{
								echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basket.png' class='noBorder' id='basketIcon' title='������' onmouseover='changeBasketIcon(1)' onmouseout='changeBasketIcon(0)' /></a>
								";
							}
							else
							{
								echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basketFull.png' class='noBorder' id='basketIcon' title='������� | ���������� ������: ".$ordersResult->num_rows."' onmouseover='changeBasketFullIcon(1)' onmouseout='changeBasketFullIcon(0)' /></a>
								";
							}
						}
						
						echo "
								<a href='scripts/exit.php' class='noBorder'><img src='pictures/system/exit.png' class='noBorder' id='exitIcon' title='����� �� ��������' onmouseover='changeExitIcon(1)' onmouseout='changeExitIcon(0)' /></a>
							</div>
						";
					}
					
					unset($_SESSION['login']);
					unset($_SESSION['userLogin']);
					unset($_SESSION['userPassword']);
                ?>
                <div id='searchBG'>
                    <form name='searchForm' id='searchForm' method='post' action='scripts/search.php'>
                        <input type='text' id='searchField' name='searchQuery' placeholder='�����...' onfocus='if(this.value=="�����...") {this.value = "";}' onblur='if(this.value == "") {this.value = "�����...";}' value='�����...' onkeyup='lookup(this.value)'>
                        <input type='submit' id='searchSubmit' value='' title='�����'>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div id='fastSearch'></div>

    <?php
    	if(isset($_SESSION['query']))
	{
		unset($_SESSION['query']);
	}
	
	if(isset($_SESSION['quantity']))
	{
		unset($_SESSION['quantity']);
	}

	if(isset($_SESSION['login']))
	{
		unset($_SESSION['login']);
	}

	if(isset($_SESSION['recovery']))
	{
		unset($_SESSION['recovery']);
	}

	if(isset($_SESSION['registration']))
	{
		unset($_SESSION['registration']);
	}

	if(isset($_SESSION['activation']))
	{
		unset($_SESSION['activation']);
	}

	if(isset($_SESSION['activationFalse']))
	{
		unset($_SESSION['activationFalse']);
	}

	if(isset($_SESSION['registration_cancel']))
	{
		unset($_SESSION['registration_cancel']);
	}

	if(isset($_SESSION['delete']))
	{
		unset($_SESSION['delete']);
	}

	if(isset($_SESSION['basket']))
	{
		unset($_SESSION['basket']);
	}
    ?>
    <div id='content_main'>
    	<div id='fa_main_block'>
        	<h1 class='headerStyle'>��������� ���������</h1>
            <div class='mainIMGContainer'>
            	<a href='catalogue.php?type=fa' class='noBorder'><img src='pictures/system/fa.png' class='noBorder' title='� ������� ��������� ���������' /></a>
            </div>
            <div class='mainIMGText'>
            	<h2 class='basic'>�������� ����������� ������������ �������� � ����������� �������� ��������� ���������, ���������� � ������������� ���������� � �������������� ������������ ��� ��������� ������ � �������������� ������. ������� �� ����� ��������� ���������, �� ��������� ��������� ����������� ������������ ���������.</h2>
            </div>
            <a href='catalogue.php?type=fa' class='noBorder'>
                <div class='toCatalogueButton' style='margin-top: 17px;'>
                    <span class='toCatBText'>� �������</span>
                </div>
            </a>
        </div>
        <div id='em_main_block'>
        	<h1 class='headerStyle'>��������� ���������</h1>
            <div class='mainIMGContainer'>
            	<a href='catalogue.php?type=em' class='noBorder'><img src='pictures/system/em.png' class='noBorder' title='� ������� ��������� ����������' /></a>
            </div>
            <div class='mainIMGText'>
            	<h2 class='basic'>����� �� ������� ����������� ����� ������������ �������� �������� ��������� ���������� �������� �� 0.4�� �� 2�� � ������� �� 19�� �� 42�� �� ������������������� ���. ��������� ��������� ����������� ����� 40 ������������� � 20 ���������� �������.</h2>
            </div>
            <a href='catalogue.php?type=em' class='noBorder'>
                <div class='toCatalogueButton' style='margin-top: 33px;'>
                    <span class='toCatBText'>� �������</span>
                </div>
            </a>
        </div>
        <div id='ca_main_block'>
        	<h1 class='headerStyle'>���������� ��� ����</h1>
            <div class='mainIMGContainer'>
            	<a href='catalogue.php?type=ca' class='noBorder'><img src='pictures/system/ca.png' class='noBorder' title='� ������� ����������� ��� ����' /></a>
            </div>
            <div class='mainIMGText'>
            	<h2 class='basic'>���������� ������� ��� ����, ���������, �������, ���������� ������ ��� ��������, ������, ��������� ����, ������������ �����, �������, ����� � �������� ��� ����.</h2>
            </div>
            <a href='catalogue.php?type=ca' class='noBorder'>
                <div class='toCatalogueButton' style='margin-top: 60px;'>
                    <span class='toCatBText'>� �������</span>
                </div>
            </a>
        </div>
        
        <div id='newsMain'>
        	<center>
            	<span class='bigHeaderStyle'>��������� �������</span>
                <br /><br />
            </center>
        	<?php

				$newsCount = 0;
				$newsResult = $mysqli->query("SELECT * FROM news ORDER BY id DESC LIMIT 3");
				while($news = $newsResult->fetch_assoc())
				{
					$newsCount++;
					$date = substr($news['date'], 0, 10);
					$time = substr($news['date'], 11, 5);
					
					if($newsCount == 1)
					{
						echo "<a href='news.php?id=".$news['id']."' clas='noBorder'><div class='newsEntry' title='��������� �������'>";
					}
					else
					{
						echo "<a href='news.php?id=".$news['id']."' clas='noBorder'><div class='newsEntry' style='margin-top: 10px;' title='��������� �������'>";
					}
					echo "
							<span class='newsHeadingFont'>".$news['header']."</span>
							<br />
							<span class='basic'>".$news['short']."</span>
							<br /><br />
							<span class='smallBasicRed'>������������ ".$date." � ".$time."</span>
					
						</div></a>
					";
				}

					
			?>
            <div id='indexNewsButtons' style='float: right; margin-right: 20px; margin-top: 20px;'>
            	<span class='basic'>�������� 3 ��������� �������</span>
            	&nbsp;&nbsp;&nbsp;
            	<?php
	            	if($_SESSION['userID'] == 1)
	        		{
	        			echo "
	        				<a href='admin/admin.php?section=users&action=addNews'>
	        					<span class='catalogueItemTextDecorated'>�������� �������</span>
	        				</a>
	        				&nbsp;&nbsp;&nbsp;
	        			";
	        		}
	            ?>
            	<a href='news.php' class='noBorder'><span class='catalogueItemTextDecorated'>��� �������</span></a>
            </div>

        </div>
        
        <div id='partners'>
        	<center>
            	<span class='bigHeaderStyle'>���� ��������</span>
                <br /><br /><br />
                <div style='position: relative; margin: 0 auto;'>
                	<a href='http://ozkardeslermetal.com.tr/en/' class='noBorder'><img src='pictures/system/partner_ozkm.png' class='noBorder' title='OZKARDESLER METAL, ������' /></a>
                    <div class='tableSpace'></div>
                    <a href='http://www.boyard.biz/' class='noBorder' style='margin-left: 30px;'><img src='pictures/system/partner_boyard.png' class='noBorder' title='BOYARD, ������' /></a>
                    <div class='tableSpace'></div>
                    <a href='http://aldi04.ru/' class='noBorder'><img src='pictures/system/partner_aldi.png' class='noBorder' title='����, ������' /></a>
                </div>
            </center>
        </div>

    </div>

    <footer>
		<div id='footerContent'>
        	<div id='location'>
            	<span class='headerStyle'>���������� ��������</span>
               	<br />
                <span class='headerStyle'>�. �������</span>
            </div>
            <div id='copyright'>
                <a href='index.php' class='noBorder'><span class='headerStyleRedHover'>�����-��</span></a><span class='headerStyle'> &copy; 2008 - <?php echo date('Y'); ?></span>
            </div>
            <div id='web'>
				<span class='headerStyle'>�������� �����</span>
                <br />
                <a href='http://airlab.by/' class='noBorder'><span class='headerStyleRedHover'>������ AIR LAB</span></a>
            </div>
        </div>
    </footer>
    
    <?php
	
		function getUrl()
		{
			$url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
			$url .= ( $_SERVER["SERVER_PORT"] != 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
			$url .= $_SERVER["REQUEST_URI"];
			return $url;
		}
		
		$_SESSION['last_page'] = getUrl();
	
	?>

    <script type='text/javascript'>footerPos();</script>
    <script type='text/javascript'>
		if(jQuery('#content_main').height()<jQuery('#fa_main_block').height()) {
			jQuery('#content_main').height(jQuery('#fa_main_block').height() + 40);
		}
    </script>
    <script type='text/javascript'>
		var fullHeight = $('#partners').offset().top + $('#partners').height() + 35;
		if($('footer').offset().top < fullHeight) {
			$('footer').offset({top: fullHeight + 30});
		}
    </script>
    
</body>

</html>