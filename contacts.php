<?php
	
	session_start();
	include('scripts/connect.php');
	
	if(isset($_SESSION['query']))
	{
		unset($_SESSION['query']);
	}
	
	if(isset($_SESSION['quantity']))
	{
		unset($_SESSION['quantity']);
	}
	
?>

<!doctype html>

<html>

<head>

    <meta charset="windows-1251">
    <meta name='keywords' content='��������� ���������, ������������� ��� ������, �����-��, ������������� ��� ������ �������, �������, ��������� ���������, ������, ������ ���, ����� ���������, ��������� ��������� �������, ������ ��� �������, ����� ���������, ����� ��������� �������, ����� ��������� �������, ������ �������'>
    
    <title>���������� ����������</title>
    
    <link rel='shortcut icon' href='pictures/icons/favicon.ico' type='image/x-icon'>
	<link rel='icon' href='pictures/icons/favicon.ico' type='image/x-icon'>
    <link rel='stylesheet' media='screen' type='text/css' href='css/style.css'>
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

<body onresize='footerPos()'>

	<div id='layout' <?php if((isset($_SESSION['login']) and $_SESSION['login'] != 1) or isset($_SESSION['recovery']) or isset($_SESSION['recovery_final']) or isset($_SESSION['registration']) or isset($_SESSION['activation']) or isset($_SESSION['activationFalse']) or isset($_SESSION['registration_cancel']) or isset($_SESSION['delete']) or isset($_SESSION['basket'])) {echo "style='display: block;'";} else {echo "style='display: none;'";} ?> onclick='resetBlocks();' onmousemove='resizeLayout()' onmousewheel='resizeLayout()'></div>

	<?php
	if(!empty($_SESSION['recovery']) and $_SESSION['recovery'] == 'sent')
	{
		echo "
					<div id='notificationWindowOuter' style='display: block;'>
						<div id='notificationWindow'>
							<form id='recoveryNotificationForm'>
								<center><span class='headerStyleRed'>�������������� ������</span></center>
								<br /><br />
								<span class='basic'>������ �� ��������� ������ ��� ��������� �� �����, ��������� ��� �����������: <b>".$_SESSION['recovery_email']."</b>. ��� ����������� ��������� �� ������, ����������� � ������.</span>
								<br /><br />
								<center><input type='button' class='windowSubmit' onclick='closeNotification()' value='OK' id='loginCancel' style='float: none;' /></center>
							</form>
						</div>
					</div>
				";
	}

	if(!empty($_SESSION['recovery_final']) and $_SESSION['recovery_final'] == 'ok')
	{
		echo "
			<div id='notificationWindowOuter' style='display: block;'>
				<div id='notificationWindow'>
					<form id='recoveryNotificationForm'>
						<center><span class='headerStyleRed'>�������������� ������</span></center>
						<br /><br />
						<span class='basic'>��� ������ ��� ������. ����� ������ ��� ��������� �� �����, ��������� ��� �����������: <b>".$_SESSION['recovery_email']."</b>.</span>
						<br /><br />
						<center><input type='button' class='windowSubmit' onclick='closeNotification()' value='OK' id='loginCancel' style='float: none;' /></center>
					</form>
				</div>
			</div>
		";

		unset($_SESSION['recovery_email']);
		unset($_SESSION['recovery_final']);
	}

	if(!empty($_SESSION['recovery_final']) and ($_SESSION['recovery_final'] == 'empty' or $_SESSION['recovery_final'] == 'failed'))
	{
		echo "
			<div id='notificationWindowOuter' style='display: block;'>
				<div id='notificationWindow'>
					<form id='recoveryNotificationForm'>
						<center><span class='headerStyleRed'>�������������� ������</span></center>
						<br /><br />
						<span class='basic'>� ���������, ��� ��������� ������ ������ ��������� ������. ��������� �������, ���� ��������� � ����. ���� ���������� ������ ������� � ������� \"��������\".</span>
						<br /><br />
						<center><input type='button' class='windowSubmit' onclick='closeNotification()' value='OK' id='loginCancel' style='float: none;' /></center>
					</form>
				</div>
			</div>
		";

		unset($_SESSION['recovery_final']);
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
								<center><span class='headerStyleRed'>����������� ����� ���������!</span></center>
								<br /><br />
								<span class='basic'>�����������! �� ������� ������������������. ������ ��� ���������� ����������� ��� ����������� �����. ��� ����� ��������� �� ������ �� ������, ������� �� ��� ���������.</span>
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
			<input type='radio' name='userType' value='organisation' class='radio' onclick='registrationType(1)' <?php if(isset($_SESSION['registration_type'])){if($_SESSION['registration_type'] == '1'){echo "checked";}}else{echo "checked";} ?>><span class='mainIMGText'>�����������</span><br />
			<input type='radio' name='userType' value='person' class='radio' onclick='registrationType(2)' <?php if(isset($_SESSION['registration_type']) and $_SESSION['registration_type'] == '2'){echo "checked";} ?>><span class='mainIMGText'>���������� ����</span><br />
			<br />
			<label>�����: </label><span class='hintText' id='hintLogin' title='����� ������ �������� ������� �� 3 ��������� ����, ���� ��� ���������� ��������.'>(���������)</span>
			<br />
			<input type='text' class='admInput' name='userLogin' id='userLoginInput' <?php if(isset($_SESSION['registration_login'])){echo " value='".$_SESSION['registration_login']."'";} ?> />
			<br /><br />
			<label>������: </label><span class='hintText' id='hintPassword' title='������ ������ ��������� ������� 5 ��������.'>(���������)</span>
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
			<label>���������� �������: </label><span class='hintText' id='hintPhone' title='����� �������� ���������� ��������� � ������������� �������: +375 (XX) XXXXXXX'>(���������)</span>
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
            	<a href='index.php' class='noBorder'>
                    <div id='mainPoint' onmouseover='menuVisual("1", "mpIMG", "mpTop")' onmouseout='menuDefault1()'>
                        <div id='mainPointCenter'>
                            <div id='mpTop'></div>
                            <div class='pBottom'>
                                <img src='pictures/system/mainText.png' id='mpIMG' class='noBorder'>
                            </div>
                        </div>
                    </div>
                </a>
                <a href='catalogue.php' class='noBorder'>
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
                <a href='contacts.php' class='noBorder'>
                    <div id='contactsPoint'>
                        <div id='contactsPointCenter'>
                            <div id='csTopActive'></div>
                            <div class='pBottom'>
                                <img src='pictures/system/contactsTextRed.png' id='csIMG' class='noBorder'>
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
									<a href='order.php' class='noBorder'><img src='pictures/system/basketFull.png' class='noBorder' id='basketIcon' title='������ | ���������� ������: ".$ordersResult->num_rows."' onmouseover='changeBasketFullIcon(1)' onmouseout='changeBasketFullIcon(0)' /></a>
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
    
    <div id='content'>
    	<div id='map'>
        	<script type="text/javascript" charset="utf-8" src="//api-maps.yandex.ru/services/constructor/1.0/js/?sid=jP7c8wlTbyby5rBmEDuGbJs3PsWmMwDx&width=570&height=415"></script>
        </div>
        <div id='contacts'>
        	<span class='basicBig'>
            	<span class='headerStyle'>�����:</span>
                <br />
                212040
                <br />
                ���������� ��������, �. �������, ��. ���������, 21
                <br /><br />
                <span class='headerStyle'>�������/���� (����� ����������):</span>
                <br />
                +375 (222) 707-707
                <br /><br />
                <span class='headerStyle'>������� (��������):</span>
                <br />
                +375 (222) 44-70-30
                <br /><br />
                <span class='headerStyle'>E-mail:</span>
                <br />
                <a href='mailto:argos-fm@mail.ru' class='noBorder'><span class='basicBigLink'>argos-fm@mail.ru</span></a>
                <br /><br />
                <span class='headerStyle'>����� ������:</span>
                <br />
                ����������� - �������
                <br />
                8:00 - 17:00
            </span>
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
    
    <script type='text/javascript'>
		footerPos();
	</script>
    
</body>

</html>