<?php

	session_start();
	
	if(empty($_SESSION['userID']))
	{
		if(isset($_SESSION['last_page']))
		{
			header("Location: ".$_SESSION['last_page']);
		}
		else
		{
			header("Location: ../index.php");
		}
	}
	
	if(empty($_REQUEST['s']))
	{
		header("Location: order.php?s=1");
	}
	else
	{
		if($_REQUEST['s'] != 1 and $_REQUEST['s'] != 2)
		{
			header("Location: order.php?s=1");
		}
	}
	
	include('scripts/connect.php');
	
	if(isset($_SESSION['query']))
	{
		unset($_SESSION['query']);
	}
	
	if(isset($_SESSION['quantity']))
	{
		unset($_SESSION['quantity']);
	}
	
	if($_REQUEST['s'] == 2)
	{
		if(empty($_REQUEST['p']))
		{
			header("Location: order.php?s=2&p=1");
		}
		else
		{
			if($_SESSION['userID'] == 1)
			{
				$ordersResult = mysql_query("SELECT * FROM orders_date WHERE status = '1'");
							
				$quantity = mysql_num_rows($ordersResult);
				if($quantity > 10)
				{
					if($quantity % 10 != 0)
					{
						$numbers = intval(($quantity / 10) + 1);
					}
					else
					{
						$numbers = intval($quantity / 10);
					}
				}
				else
				{
					$numbers = 1;
				}
														
				$page = $_REQUEST['p'];
				$start = $page * 10 - 10;
			}
			else
			{
				$ordersResult = mysql_query("SELECT * FROM orders_date WHERE status = '1' AND user_id = '".$_SESSION['userID']."'");
							
				$quantity = mysql_num_rows($ordersResult);
				if($quantity > 10)
				{
					if($quantity % 10 != 0)
					{
						$numbers = intval(($quantity / 10) + 1);
					}
					else
					{
						$numbers = intval($quantity / 10);
					}
				}
				else
				{
					$numbers = 1;
				}
														
				$page = $_REQUEST['p'];
				$start = $page * 10 - 10;
			}
		}
	}
	
?>
	
<!doctype html>

<html>

<head>

    <meta charset="windows-1251">
    <meta name='keywords' content='��������� ���������, ������������� ��� ������, �����-��, ������������� ��� ������ �������, �������, ��������� ���������, ������, ������ ���, ����� ���������, ��������� ��������� �������, ������ ��� �������, ����� ���������, ����� ��������� �������, ����� ��������� �������, ������ �������'>
    
    <title>�����-�� | <?php if($_SESSION['userID'] == 1) {echo "������";} else {echo "�������";} ?></title>
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
    <script type='text/javascript' src='js/footerO.js'></script>
    <script type='text/javascript' src='js/catalogue.js'></script>
    <script type='text/javascript' src='js/jquery-1.8.3.min.js'></script>
    <script type='text/javascript' src='js/shadowbox/source/shadowbox.js'></script>
    <script type='text/javascript' src='js/ajax.js'></script>
    <script type='text/javascript' src='js/functions.js'></script>

    <?php
    	if(!isset($_SESSION['background']))
		{
			$BGCountResult = mysql_query("SELECT COUNT(id) FROM background");
			$BGCount = mysql_fetch_array($BGCountResult, MYSQL_NUM);

			$index = rand(1, $BGCount[0]);

			$backgroundResult = mysql_query("SELECT photo FROM background WHERE id = '".$index."'");
			$background = mysql_fetch_array($backgroundResult, MYSQL_NUM);

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

	<div id='layout' <?php if((isset($_SESSION['login']) and $_SESSION['login'] != 1) or isset($_SESSION['recovery']) or isset($_SESSION['registration']) or isset($_SESSION['activation']) or isset($_SESSION['activationFalse']) or isset($_SESSION['registration_cancel']) or isset($_SESSION['delete']) or isset($_SESSION['basket'])) {echo "style='display: block;'";} else {echo "style='display: none;'";} ?> onclick='resetBlocks();'></div>
    
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
							<center><input type='button' onclick='closeNotification()' value='OK' id='cancelButton' style='float: none;' /></center>
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
							<center><input type='button' onclick='closeNotification()' value='OK' id='cancelButton' style='float: none;' /></center>
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
							<center><input type='button' onclick='closeNotification()' value='OK' id='cancelButton' style='float: none;' /></center>
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
							<center><input type='button' onclick='closeNotification()' value='OK' id='cancelButton' style='float: none;' /></center>
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
				default:
					break;
			}
			
			echo "
							<br /><br />
							<center><input type='button' onclick='closeNotification()' value='OK' id='cancelButton' style='float: none;' /></center>
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
							<center><input type='button' onclick='closeNotification()' value='OK' id='cancelButton' style='float: none;' /></center>
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
							<center><input type='button' onclick='closeNotification()' value='OK' id='cancelButton' style='float: none;' /></center>
						</form>
					</div>
				</div>
			";
		}
		
		unset($_SESSION['recovery']);
		unset($_SESSION['recovery_email']);
	?>

    <div id='registrationWindowOuter' <?php if(isset($_SESSION['registration']) and $_SESSION['registration'] != 'ok'){echo "style='display: block;'";}else{echo "style='display: none;'";} ?>>
    	<div id='registrationWindow'>
        	<form name='registrationForm' id='registrationForm' method='post' action='scripts/registration.php'>
            	<center><span class='headerStyleRed'>����������� ������ ������������</span></center>
                <br /><br />
                <label>��� ������������:</label>
                <br />
                <input type='radio' name='userType' value='organisation' class='radio' onclick='registrationType(1)' <?php if(isset($_SESSION['registration_type'])){if($_SESSION['registration_type'] == '1'){echo "checked";}}else{echo "checked";} ?>><span class='mainIMGText'>����������� ��� ��</span><br />
                <input type='radio' name='userType' value='person' class='radio' onclick='registrationType(2)' <?php if(isset($_SESSION['registration_type']) and $_SESSION['registration_type'] == '2'){echo "checked";} ?>><span class='mainIMGText'>���������� ����</span><br />
                <br />
                <label>�����:</label>
                <br />
                <input type='text' name='userLogin' id='userLoginInput' <?php if(isset($_SESSION['registration_login'])){echo "value='".$_SESSION['registration_login']."'";} ?> />
                <br /><br />
                <label>������:</label>
                <br />
                <input type='password' name='userPassword' id='userPasswordInput' <?php if(isset($_SESSION['registration_password'])){echo "value='".$_SESSION['registration_password']."'";} ?> />
                <br /><br />
                <label>E-mail:</label>
                <br />
                <input type='text' name='userEmail' id='userEmailInput' <?php if(isset($_SESSION['registration_email'])){echo "value='".$_SESSION['registration_email']."'";} ?> />
                <?php
					if((isset($_SESSION['registration_type']) and $_SESSION['registration_type'] == 1) or !isset($_SESSION['registration_type']))
					{
						echo "
							<br /><br />
							<label>�������� �����������:</label>
							<br />
							<input type='text' name='organisation' id='organisationInput' ";
							if(isset($_SESSION['registration_organisation'])){echo "value='".$_SESSION['registration_organisation']."'";}
							
						echo "
							/>;
						";
					}
				?>
                <br /><br />
                <label>���������� ����:</label>
                <br />
                <input type='text' name='userName' id='userNameInput' <?php if(isset($_SESSION['registration_name'])){echo "value='".$_SESSION['registration_name']."'";} ?> />
                <br /><br />
                <label>���������� �������:</label>
                <br />
                <input type='text' name='userPhone' id='userPhoneInput' <?php if(isset($_SESSION['registration_phone'])){echo "value='".$_SESSION['registration_phone']."'";} ?> />
                <?php 
					if(isset($_SESSION['registration']) and $_SESSION['registration'] != 'ok')
					{
						switch($_SESSION['registration'])
						{
							case "failed":
								echo "<br /><br /><span class='basicRed'>��� ����������� ��������� ������. ���������� �����.</span><br />";
								break;
							case "empty":
								echo "<br /><br /><span class='basicRed'>��� ����������� ���������� ��������� ��� ����.</span><br />";
								break;
							case "login":
								echo "<br /><br /><span class='basicRed'>����� ������ ������ ���������� �� 3 �� 25 ��������. ����������� �� �����������.</span>";
								break;
							case "password":
								echo "<br /><br /><span class='basicRed'>����� ������ ������ ���������� �� 5 �� 25 ��������.</span><br />";
								break;
							case "email":
								echo "<br /><br /><span class='basicRed'>����� ������������ e-mail.</span><br />";
								break;
							case "login_d":
								echo "<br /><br /><span class='basicRed'>�������� ���� ����� ��� ����������.</span><br />";
								break;
							case "email_d":
								echo "<br /><br /><span class='basicRed'>�������� ���� e-mail ��� ����������.</span><br />";
								break;
							case "organisation_d":
								echo "<br /><br /><span class='basicRed'>�������� ���� �������� ����������� ��� ����������.</span><br />";
								break;
							case "phone_d":
								echo "<br /><br /><span class='basicRed'>�������� ���� ����� �������� ��� ����������.</span><br />";
								break;
							default:
								break;
						}
						
						echo "<br />";
					}
					else
					{
						echo "<br /><br /><br />";
					}
				?>
                <input type='submit' value='������������������' id='registrationSubmit' />
                <input type='button' value='������' id='cancelButton' onclick='resetBlocks();' />
                <br /><br />
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
    </div>
				
    <div id='passwordRecoveryOuter' <?php if(!empty($_SESSION['recovery']) and $_SESSION['recovery'] != 'sent'){echo "style='display: block;'";}else{echo "style='display: none;'";} ?>>
		<div id='passwordRecoveryBlock'>
			<form name='passwordRecoveryForm' id='passwordRecoveryForm' method='post' action='scripts/recovery.php'>
				<center><span class='headerStyleRed'>�������������� ������</span></center>
				<br /><br />
				<label>����� ����� ��� e-mail, ��������� ��� �����������:</label>
				<br />
				<input type='text' name='recovery' id='recoveryInput' />
				<br /><br />
				<?php
                    switch($_SESSION['recovery'])
                    {
                        case "empty":
                            echo "<span class='basicRed'>�� �� ����� ���� ����� ��� e-mail.</span><br /><br />";
                            break;

                        case "login":
                            echo "<span class='basicRed'>�� ����� �������������� ����� ��� e-mail..</span><br /><br />";
                            break;
                        default:
                            break;
                    }
                ?>
                <input type='submit' value='����������' id='recoverySubmit' />
                <input type='button' value='������' id='cancelButton' onclick='resetBlocks();' />
			</form>
		</div>
	</div>
    
	<header>
    	<div id='headerBlock'>
        	<a href='index.php' class='noBorder'>
                <div id='logo'>
                    <img src='pictures/system/logo.png' class='noBorder' />
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
                <a href='news.php' class='noBorder' title='�������, ����� � ������������ ����������'>
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
							
							<div id='loginBlockOuter' "; if(isset($_SESSION['login'])){echo "style='display: block;'";}else{echo "style='display: none;'";} echo ">
								<div id='loginBlock'>
									<form name='loginForm' id='loginForm' method='post' action='scripts/login.php'>
										<center><span class='headerStyleRed'>�����������</span></center>
										";
										if(isset($_SESSION['login']))
										{
											switch($_SESSION['login'])
											{
												case 'error':
													echo "<br /><span class='basicRed'>�������� ��� ������������ ��� ������.</span><br /><br />";
													break;
												case 'empty':
													echo "<br /><span class='basicRed'>��������� ��� ����.</span><br /><br />";
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
										<input type='text' id='userLogin' name='userLogin'"; if(isset($_SESSION['userLogin'])){echo "value='".$_SESSION['userLogin']."'";} echo " />
										<br /><br />
										<label>������:</label>
										<br />
										<input type='password' id='userPassword' name='userPassword'"; if(isset($_SESSION['userPassword'])){echo "value='".$_SESSION['userPassword']."'";} echo " />
										<br /><br />
										<span class='redItalicHover' style='cursor: pointer;' onclick='registrationWindow();'>��� �� ����������������?</span>
										<br />
										<span class='redItalicHover' style='cursor: pointer;' onclick='recoveryWindow();'>������ ������?</span>
										<br /><br />
										<input type='submit' value='�����' id='loginSubmit' class='button' />
										<input type='button' value='������' id='cancelButton' onclick='resetBlocks();' />
									</form>
								</div>
							</div>
						";
					}
					else
					{
						$userResult = mysql_query("SELECT * FROM users WHERE id = '".$_SESSION['userID']."'");
						$user = mysql_fetch_array($userResult, MYSQL_ASSOC);

						echo "
							<div id='loginL'>
								<a href='settings.php?s=1' class='noBorder'><img src='pictures/system/user.png' class='noBorder' id='userIcon' title='".$user['login']." | ������������ ��������' onmouseover='changeUserIcon(1)' onmouseout='changeUserIcon(0)' /></a>
							";
						
						if($_SESSION['userID'] != 1)
						{	
							$ordersResult = mysql_query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND status = '0'");
							$orders = mysql_num_rows($ordersResult);
							if($orders < 1)
							{
								echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basketRed.png' class='noBorder' id='basketIcon' title='�������' /></a>
								";
							}
							else
							{
								echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basketFullRed.png' class='noBorder' id='basketIcon' title='������� | ���������� �������: ".$orders."' /></a>
								";
							}
						}
						else
						{
							$ordersResult = mysql_query("SELECT * FROM orders_date WHERE status = '0'");
							$orders = mysql_num_rows($ordersResult);
							if($orders < 1)
							{
								echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basketRed.png' class='noBorder' id='basketIcon' title='������' /></a>
								";
							}
							else
							{
								echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basketFullRed.png' class='noBorder' id='basketIcon' title='������ | ���������� ������: ".$orders."' /></a>
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
    	<div id='basketBlock'>
        	<div id='basketMenu'>
            	<?php
					if($_SESSION['userID'] == 1)
					{
						$ordersQuantityResult = mysql_query("SELECT COUNT(id) FROM orders_date WHERE status = '0'");
						$ordersQuantity = mysql_fetch_array($ordersQuantityResult, MYSQL_NUM);

						switch($_REQUEST['s'])
						{
							case "1":
								echo "
									<div class='basketPointActive'><span class='headerStyleWhite'>�������� ������ (".$ordersQuantity[0].")</span></div>
									<a href='order.php?s=2' class='noBorder'><div class='basketPoint' id='bp2' onmouseover='settingsMenuButton(\"1\", \"bp2\", \"bp2t\")' onmouseout='settingsMenuButton(\"0\", \"bp2\", \"bp2t\")'><span class='headerStyle' id='bp2t'>������� �������</span></div></a>
								";
								break;
							case "2":
								echo "
									<a href='order.php?s=1' class='noBorder'><div class='basketPoint' id='bp1' onmouseover='settingsMenuButton(\"1\", \"bp1\", \"bp1t\")' onmouseout='settingsMenuButton(\"0\", \"bp1\", \"bp1t\")'><span class='headerStyle' id='bp1t'>�������� ������ (".$ordersQuantity[0].")</span></div></a>
									<div class='basketPointActive'><span class='headerStyleWhite'>������� �������</span></div>
								";
								break;
							default:
								break;
						}
					}
					else
					{
						$ordersQuantityResult = mysql_query("SELECT COUNT(id) FROM orders_date WHERE user_id = '".$_SESSION['userID']."' AND status = '0'");
						$ordersQuantity = mysql_fetch_array($ordersQuantityResult, MYSQL_NUM);

						switch($_REQUEST['s'])
						{
							case "1":
								echo "
									<div class='basketPointActive'><span class='headerStyleWhite'>������� �����</span></div>
									<a href='order.php?s=2' class='noBorder'><div class='basketPoint' id='bp2' onmouseover='settingsMenuButton(\"1\", \"bp2\", \"bp2t\")' onmouseout='settingsMenuButton(\"0\", \"bp2\", \"bp2t\")'><span class='headerStyle' id='bp2t'>������� ������� (".$ordersQuantity[0].")</span></div></a>
								";
								break;
							case "2":
								echo "
									<a href='order.php?s=1' class='noBorder'><div class='basketPoint' id='bp1' onmouseover='settingsMenuButton(\"1\", \"bp1\", \"bp1t\")' onmouseout='settingsMenuButton(\"0\", \"bp1\", \"bp1t\")'><span class='headerStyle' id='bp1t'>������� �����</span></div></a>
									<div class='basketPointActive'><span class='headerStyleWhite'>������� ������� (".$ordersQuantity[0].")</span></div>
								";
								break;
							default:
								break;
						}
					}
				?>
            </div>
            <?php
				
				if($_SESSION['userID'] == 1)
				{
					if($_REQUEST['s'] == 1)
					{
						echo "<div style='height: 30px;'></div>";
						
						echo "<div id='orderListBlock' style='padding: auto 20px;'>";
						$ordersResult = mysql_query("SELECT * FROM orders_date WHERE status = '0' ORDER BY date");

						$number = 0;
						
						if(mysql_num_rows($ordersResult) == 0)
						{
							echo "<span class='basic'>�� ������ ������ ������ ���.</span>";
						}
						else
						{
							while($orders = mysql_fetch_array($ordersResult, MYSQL_ASSOC))
							{
								$number++;	
								$customerResult = mysql_query("SELECT * FROM users WHERE id = '".$orders['user_id']."'");
								$customer = mysql_fetch_assoc($customerResult);
								$info = $customer['person']."; ".$customer['phone'];
								if(!empty($customer['organisation']))
								{
									$info = $customer['organisation']."; ".$info;
								}
														
								echo "
									<div class='tableVSpace'></div>
									<div class='line'>
										<div class='orderNumber'>
											<span class='tableStyle'>".$number."</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderName'>
											<span class='tableStyleDecorated' id='order".$orders['id']."' title='��������� ��������' style='float: left;' onclick='showDetails(\"".$orders['id']."\", \"order".$orders['id']."\", \"tableGood".$orders['id']."\")'>����� �".$orders['id']." �� ".$orders['date']."</span>
											<span class='tableStyleDecorated' id='deleteOrder".$orders['id']."' title='�������� ������ �����' style='float: right; margin-right: -30px;' onclick='cancelOrder(\"".$orders['id']."\")'>���������</span>
											<span class='tableStyleDecorated' id='acceptOrder".$orders['id']."' title='������� ������ �����' style='float: right; margin-right: 10px;' onclick='acceptOrder(\"".$orders['id']."\")'>�������</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderStatus'>
											<span class='tableStyleDecorated' title='��������: ".$info."' style='float: right; margin-right: 10px; cursor: pointer;' onclick='showCustomer(\"".$orders['user_id']."\")'>��������</span>
										</div>
									</div>
									<div class='tableVSpace'></div>
									<div class='tableGood' id='tableGood".$orders['id']."'></div>
								";	
							}
						}
						echo "</div>";
						echo "<div id='mailTextBlock'></div>";
					}
					
					if($_REQUEST['s'] == 2)
					{
						$ordersResult = mysql_query("SELECT * FROM orders_date WHERE status = '1' ORDER BY date DESC LIMIT ".$start.", 10");
						
						echo "<div style='height: 30px;'></div>";
						echo "<div style='padding: auto 20px;'></div>";
						echo "<span class='headerStyle'>������������ ������</span><br /><br />";
						echo "<div id='newOrders'>";
						echo "
							<div id='ordersContent'>
								<div class='tableVSpace'></div>
								<div class='ordersTable'>
									<div class='line'>
										<div class='orderNumberTop'>
										<span class='goodStyle'>�</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderNameTop'>
											<span class='goodStyle'>�������� ������</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderStatusTop'>
											<span class='goodStyle'>������</span>
										</div>
									</div>
							";

						$number = $_REQUEST['p'] * 10 - 10;
							
						while($orders = mysql_fetch_array($ordersResult, MYSQL_ASSOC))
						{
							$number++;	
							$status = "���������";
								
							echo "
								<div class='tableVSpace'></div>
								<div class='line'>
									<div class='orderNumber'>
										<span class='tableStyle'>".$number."</span>
									</div>
									<div class='tableSpace'></div>
									<div class='orderName'>
										<span class='tableStyleDecorated' id='order".$orders['id']."' title='��������� ��������' style='float: left;' onclick='showDetails(\"".$orders['id']."\", \"order".$orders['id']."\", \"tableGood".$orders['id']."\")'>����� �".$orders['id']." �� ".$orders['date']."</span>
									</div>
									<div class='tableSpace'></div>
									<div class='orderStatus'>
										<span class='tableStyle'>".$status."</span>
									</div>
								</div>
								<div class='tableVSpace'></div>
								<div class='tableGood' id='tableGood".$orders['id']."'></div>
							";
						}
						
						echo "</div></div></div>";
						
						echo "<div id='pageNumbers'>";

						if($numbers > 1)
		                {
		                    if($numbers <= 7)
		                    {
		                        echo "
		                            <br /><br />
		                        ";

		                        if($_REQUEST['p'] == 1)
		                        {
		                        	echo "<div class='admPageNumberBlockSide' id='pbPrev' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>����������</span></div>";
		                        }
		                        else
		                        {
		                        	echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>����������</span></div></a>";
		                        }

		                        for($i = 1; $i <= $numbers; $i++)
		                        {
		                            if($_REQUEST['p'] != $i)
		                            {
		                                echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".$i."' class='noBorder'>";
		                            }

		                            echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='goodStyleWhite'";} else {echo "class='goodStyleRed' id='pbt".$i."'";} echo ">".$i."</span></div>";

		                            if($_REQUEST['p'] != $i)
		                            {
		                                echo "</a>";
		                            }
		                        }

		                        if($_REQUEST['p'] == $numbers)
		                        {
		                        	echo "<div class='admPageNumberBlockSide' id='pbNext' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>���������</span></div>";
		                        }
		                        else
		                        {
		                            echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>���������</span></div></a>";
		                        }

		                        echo "</div>";

		                    }
		                    else
		                    {
		                        if($_REQUEST['p'] < 5)
		                        {
		                            if($_REQUEST['p'] == 1)
		                            {
		                                echo "<div class='admPageNumberBlockSide' id='pbPrev' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>����������</span></div>";
		                            }
		                            else
		                            {
		                                echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>����������</span></div></a>";
		                            }
		                                                    
		                            for($i = 1; $i <= 5; $i++)
		                            {
		                                if($_REQUEST['p'] != $i)
		                                {
		                                    echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".$i."' class='noBorder'>";
		                                }

		                                echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='goodStyleWhite'";} else {echo "class='goodStyleRed' id='pbt".$i."'";} echo ">".$i."</span></div>";

		                                if($_REQUEST['p'] != $i)
		                                {
		                                    echo "</a>";
		                                }
		                            }

		                            echo "<div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>";
		                            echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='goodStyleRed' id='pbt".$numbers."'>".$numbers."</span></div></a>";

		                            if($_REQUEST['p'] == $numbers)
		                            {
		                                echo "<div class='admPageNumberBlockSide' id='pbNext' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>���������</span></div>";
		                            }
		                            else
		                            {
		                            	echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>���������</span></div></a>";
		                            }

		                            echo "</div>";
		                        }
		                        else
		                        {
		                            $check = $numbers - 3;

		                            if($_REQUEST['p'] >= 5 and $_REQUEST['p'] < $check)
		                            {
		                                echo "
		                                    <br /><br />
		                                    <div id='pageNumbers'>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>����������</span></div></a>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='goodStyleRed' id='pbt1'>1</span></div></a>
		                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] - 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")'><span class='goodStyleRed' id='pbt".($_REQUEST['p'] - 1)."'>".($_REQUEST['p'] - 1)."</span></div></a>
		                                        <div class='admPageNumberBlockActive'><span class='goodStyleWhite'>".$_REQUEST['p']."</span></div>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] + 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")'><span class='goodStyleRed' id='pbt".($_REQUEST['p'] + 1)."'>".($_REQUEST['p'] + 1)."</span></div></a>
		                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='goodStyleRed' id='pbt".$numbers."'>".$numbers."</span></div></a>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>���������</span></div></a>
		                                    </div>
		                                ";
		                            }
		                            else
		                            {
		                                echo "
		                                    <br /><br />
		                                    <div id='pageNumbers'>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>����������</span></div></a>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='goodStyleRed' id='pbt1'>1</span></div></a>
		                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
		                                ";

		                                for($i = ($numbers - 4); $i <= $numbers; $i++)
		                                {
		                                    if($_REQUEST['p'] != $i)
		                                    {
		                                        echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".$i."' class='noBorder'>";
		                                    }

		                                    echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='goodStyleWhite'";} else {echo "class='goodStyleRed' id='pbt".$i."'";} echo ">".$i."</span></div>";

		                                    if($_REQUEST['p'] != $i)
		                                    {
		                                        echo "</a>";
		                                    }
		                                }

		                                if($_REQUEST['p'] == $numbers)
		                                {
		                                    echo "<div class='admPageNumberBlockSide' id='pbNext' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
		                                }
		                                else
		                                {
		                                    echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>���������</span></div></a>";
		                                }   
		                            }
		                        }
		                    }
		                }

		                echo "</div>";
					}
				}
				else
				{
					if($_REQUEST['s'] == 1)
					{
						echo "<div style='height: 30px;'></div>";
						
						echo "<div style='padding: auto 20px;'>";
						if(isset($_SESSION['deleteFromBasket']))
						{
							switch($_SESSION['deleteFromBasket'])
							{
								case 'ok':
									echo "<span class='basicGreen'>����� ������� ����� �� ����� �������.</span>";
									break;
								case 'failed':
									echo "<span class='basicGreen'>��� �������� ������ �� ������� ��������� ������. ���������� �����.</span>";
									break;
								default:
									break;
							}
							echo "<br /><br /><br />";
							unset($_SESSION['deleteFromBasket']);
						}
						
						if(isset($_SESSION['clearBasket']))
						{
							switch($_SESSION['clearBasket'])
							{
								case 'ok':
									echo "<span class='basicGreen'>������� �������.</span>";
									break;
								case 'failed':
									echo "<span class='basicGreen'>��� ������� ������� ��������� ������. ���������� �����.</span>";
									break;
								case 'empty':
									echo "<span class='basicGreen'>������� �� ����� ���� �������, ��� ��� ��� �����.</span>";
									break;
								default:
									break;	
							}
							echo "<br /><br /><br />";
							unset($_SESSION['clearBasket']);
						}
						
						if(isset($_SESSION['orderComplete']))
						{
							switch($_SESSION['orderComplete'])
							{
								case 'ok':
									echo "<span class='basic'>��� ����� ������� ��������� �� ���������. �� ����� ��� ����������. ������ ������ ������ ����� ����������� � <a href='order.php?s=2' class='noBorder'><span class='catalogueItemTextDecorated'>������� �������</span></a>.</span>";
									break;
								case 'failed':
								echo "<span class='basicRed'>��� ���������� ������ �������� ������. ���������� �����.</span>";
									break;
								default: break;
							}
							echo "<br /><br /><br />";
							unset($_SESSION['orderComplete']);
						}
						echo "</div>";
						$totalPrice = 0;
						$rateResult = mysql_query("SELECT rate FROM currency WHERE code = 'usd'");
						$rate = mysql_fetch_array($rateResult, MYSQL_NUM);
						$goodsResult = mysql_query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND status = '0'");
						if(mysql_num_rows($goodsResult) == 0)
						{
							echo "<span class='basic'>�� ������ ������ ���� ������� �����. ����� �������� �����, �������� <a href='catalogue.php' class='noBorder'><span class='catalogueItemTextDecorated'>�������</span></a> � �������� ����������� ��� ������.</span>";
						}
						else
						{
							while ($goods = mysql_fetch_array($goodsResult, MYSQL_ASSOC))
							{
								$count++;
								$goodResult = mysql_query("SELECT * FROM catalogue_new WHERE id = '".$goods['good_id']."'");
								$good = mysql_fetch_array($goodResult, MYSQL_ASSOC);
								$totalPrice += $good['price']*$goods['quantity']*$rate[0];
								echo "
									<div class='basketGood'>
										<div class='basketGoodPicture'>
											<a href='pictures/catalogue/big/".$good['picture']."' class='noBorder' rel='lightbox'><img src='pictures/catalogue/small/".$good['small']."' class='noBorder' /></a>
										</div>
										<div class='basketGoodContent'>
											<div class='basketGoodTopLine'>
												<div class='basketGTLRed'></div>
												<div class='basketGoodName'>
													<span class='goodStyle'>".$good['name']."</span>
												</div>
												<div class='basketGoodDescription'>
													<span class='basic'>".$good['description']."</span>
												</div>
												<div class='basketGoodCodePrice'>
													<div class='basketGoodCode'>
														<span class='basic'><b>�������: </b>".$good['code']."</span>
													</div>
													<div class='basketGoodPrice'>
														<span class='basic'><b>���� �� ��.: </b>".($good['price']*$rate[0])." ���. ���.</span>
														<br />
														<span class='basic'><b>����� ���� ������ ������ �������: </b><span id='price".$good['id']."'>".($goods['quantity']*$good['price']*$rate[0])."</span> ���. ���.</span>
													</div>
												</div>
											</div>
										</div>
										<div class='basketEditBlock'>
											<form id='deleteFromBasketForm".$good['id']."' method='post' action='scripts/deleteFromBasket.php?id=".$good['id']."'>
												<img src='pictures/system/x.png' id='x".$good['id']."' class='noBorder' onmouseover='changeX(\"1\", \"x".$good['id']."\")' onmouseout='changeX(\"0\", \"x".$good['id']."\")' onclick='document.getElementById(\"deleteFromBasketForm".$good['id']."\").submit(); return false;' title='������ ��� ������ ������� �� �������' style='float: right; cursor: pointer;' />
											</form>
											<form method='post' style='padding-top: 25px;'>
												<label>����������</label>
												<br /><br />
												<input type='text' class='catalogueInput' id='quantity".$good['id']."' name='quantity".$good['id']."' value='".$goods['quantity']."' onKeyUp='validateQuantity(\"quantity".$good['id']."\")' onChange='validateQuantity(\"quantity".$good['id']."\")' />
												<img src='pictures/system/edit.png' id='edit".$good['id']."' class='noBorder'  onmouseover='changeEdit(\"1\", \"edit".$good['id']."\")' onmouseout='changeEdit(\"0\", \"edit".$good['id']."\")' onclick='editBasketGood(\"".$good['id']."\", \"".$good['price']."\", \"".$rate[0]."\", \"".$totalPrice."\", \"".$goods['quantity']."\")' title='�������� ���������� ������ � ������' style='float: right; cursor: pointer;' />
											</form>
											<div id='note".$good['id']."' style='padding-top: 40px;' class='basketNote'></div>
										</div>
									</div>
								";
							}
							echo "
								<form name='clearBasketForm' id='clearBasketForm' method='post' action='scripts/clearBasket.php'>
									<br /><br />
									<input type='submit' value='�������� �������' id='clearBasketSubmit' />
								</form>
								<form name='completeOrderForm' id='completeOrderForm' method='post' action='scripts/checkout.php'>
									<label style='font-size: 16px;'><b>����� ����� ������: </b><span id='totalPrice'>".$totalPrice."</span> ���. ���.</label>
									<br /><br />
									<input type='submit' id='completeOrderSubmit' value='��������� ����� ���������' />
								</form>
							";
						}
					}
					
					if($_REQUEST['s'] == 2)
					{
						echo "<div style='height: 30px;'></div>";
						echo "<div style='padding: auto 20px;'></div>";
						
						echo "<span class='headerStyle'>�������������� ������</span><br /><br />";
						echo "<div id='newOrders'>";
						$orders1Result = mysql_query("SELECT * FROM orders_date WHERE user_id = '".$_SESSION['userID']."' AND status = '0' ORDER BY id");
						if(mysql_num_rows($orders1Result) == 0)
						{
							echo "<span class='basic'>������� ����� ������� �����, ��� ��� �� �� ���������� ��� �� ������ ������. ��� ����� ��������� � <a href='catalogue.php' class='noBorder'><span class='catalogueItemTextDecorated'>�������</span></a> � �������� ����������� ��� ������.</span>";
						}
						else
						{
							echo "
								<div class='ordersTable'>
									<div class='line'>
										<div class='orderNumberTop'>
											<span class='goodStyle'>�</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderNameTop'>
											<span class='goodStyle'>�������� ������</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderStatusTop'>
											<span class='goodStyle'>������</span>
										</div>
									</div>
								";
								
							$number_n = $_REQUEST['p'] * 10 - 10;
								
							while($orders1 = mysql_fetch_array($orders1Result, MYSQL_ASSOC))
							{
								$number_n++;	
								$status = "�� ���������";
								
								echo "
									<div class='tableVSpace'></div>
									<div class='line'>
										<div class='orderNumber'>
											<span class='tableStyle'>".$number_n."</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderName'>
											<span class='tableStyleDecorated' id='order".$orders1['id']."' title='��������� ��������' style='float: left;' onclick='showDetails(\"".$orders1['id']."\", \"order".$orders1['id']."\", \"tableGood".$orders1['id']."\")'>����� �".$orders1['id']." �� ".$orders1['date']."</span>
											<span class='tableStyleDecorated' id='deleteOrder".$orders1['id']."' title='�������� ������ �����' style='float: right; margin-right: 5px;' onclick='deleteOrder(\"".$orders1['id']."\")'>�������</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderStatus'>
											<span class='tableStyle'>".$status."</span>
										</div>
									</div>
									<div class='tableVSpace'></div>
									<div class='tableGood' id='tableGood".$orders1['id']."'></div>
								";
							}
							
							echo "</div>";
						}
						echo "</div>";
						
						echo "<div style='height: 30px; overflow: hidden; clear: both;'></div>";
						echo "<div style='padding: auto 20px;'>";
							
						echo "</div>";
							
						echo "<span class='headerStyle' style='padding-top: 30px;'>������������ ������</span><br /><br />";
						echo "<div id='oldOrders'>";
						
						$orders1Result = mysql_query("SELECT * FROM orders_date WHERE user_id = '".$_SESSION['userID']."' AND status = '1' ORDER BY date DESC LIMIT ".$start.", 10");
						if(mysql_num_rows($orders1Result) == 0)
						{
							echo "<span class='basic'>� ���������, � ��� ��� ��� ������������ �������.</span>";
						}
						else
						{
							echo "
								<div class='ordersTable'>
									<div class='line'>
										<div class='orderNumberTop'>
											<span class='goodStyle'>�</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderNameTop'>
											<span class='goodStyle'>�������� ������</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderStatusTop'>
											<span class='goodStyle'>������</span>
										</div>
									</div>
								";
									
							$number = $_REQUEST['p'] * 10 - 10;
									
							while($orders1 = mysql_fetch_array($orders1Result, MYSQL_ASSOC))
							{
								$number++;	
								$status = "���������";
								$proceedeDate = "";
								
								switch (date('w', strtotime(substr($orders1['proceede_date'], 0, 10))))
								{
									case "1":
										$day = "� ����������� ";
										break;
									case "2":
										$day = "�� ������� ";
										break;
									case "3":
										$day = "� ����� ";
										break;
									case "4":
										$day = "� ������� ";
										break;
									case "5":
										$day = "� ������� ";
										break;
									case "6":
										$day = "� ������� ";
										break;
									case "7":
										$day = "� ����������� ";
										break;
									default:
										break;
								}
								$proceedeDate .= $day;							
								$proceedeDate .= substr($orders1['proceede_date'], 8, 2);
								switch(substr($orders1['proceede_date'], 5, 2))
								{
									case "01":
										$month = " ������ ";
										break;
									case "02":
										$month = " ������� ";
										break;
									case "03":
										$month = " ����� ";
										break;
									case "04":
										$month = " ������ ";
										break;
									case "05":
										$month = " ��� ";
										break;
									case "06":
										$month = " ���� ";
										break;
									case "07":
										$month = " ���� ";
										break;
									case "08":
										$month = " ������� ";
										break;
									case "09":
										$month = " �������� ";
										break;
									case "10":
										$month = " ������� ";
										break;
									case "11":
										$month = " ������ ";
										break;
									case "12":
										$month = " ������� ";
										break;
									default:
										break;
								}
								$proceedeDate .= $month;
								$proceedeDate .= "� ";
								$proceedeDate .= substr($orders1['proceede_date'], 11, 8);
										
								echo "
									<div class='tableVSpace'></div>
									<div class='line'>
										<div class='orderNumber'>
											<span class='tableStyle'>".$number."</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderName'>
											<span class='tableStyleDecorated' id='order".$orders1['id']."' title='��������� ��������' style='float: left;' onclick='showDetails(\"".$orders1['id']."\", \"order".$orders1['id']."\", \"tableGood".$orders1['id']."\")'>����� �".$orders1['id']." �� ".$orders1['date']."</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderStatus'>
											<span class='tableStyle' style='cursor: help;' title='����� ��������� ".$proceedeDate."'>".$status."</span>
										</div>
									</div>
									<div class='tableVSpace'></div>
									<div class='tableGood' id='tableGood".$orders1['id']."'></div>
								";
							}
								
							echo "</div>";
						}
					}
					echo "</div>";

					echo "<div id='pageNumbers'>";

						if($numbers > 1)
		                {
		                    if($numbers <= 7)
		                    {
		                        echo "
		                            <br /><br />
		                        ";

		                        if($_REQUEST['p'] == 1)
		                        {
		                        	echo "<div class='admPageNumberBlockSide' id='pbPrev' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>����������</span></div>";
		                        }
		                        else
		                        {
		                        	echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>����������</span></div></a>";
		                        }

		                        for($i = 1; $i <= $numbers; $i++)
		                        {
		                            if($_REQUEST['p'] != $i)
		                            {
		                                echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".$i."' class='noBorder'>";
		                            }

		                            echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='goodStyleWhite'";} else {echo "class='goodStyleRed' id='pbt".$i."'";} echo ">".$i."</span></div>";

		                            if($_REQUEST['p'] != $i)
		                            {
		                                echo "</a>";
		                            }
		                        }

		                        if($_REQUEST['p'] == $numbers)
		                        {
		                        	echo "<div class='admPageNumberBlockSide' id='pbNext' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>���������</span></div>";
		                        }
		                        else
		                        {
		                            echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>���������</span></div></a>";
		                        }

		                        echo "</div>";

		                    }
		                    else
		                    {
		                        if($_REQUEST['p'] < 5)
		                        {
		                            if($_REQUEST['p'] == 1)
		                            {
		                                echo "<div class='admPageNumberBlockSide' id='pbPrev' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>����������</span></div>";
		                            }
		                            else
		                            {
		                                echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>����������</span></div></a>";
		                            }
		                                                    
		                            for($i = 1; $i <= 5; $i++)
		                            {
		                                if($_REQUEST['p'] != $i)
		                                {
		                                    echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".$i."' class='noBorder'>";
		                                }

		                                echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='goodStyleWhite'";} else {echo "class='goodStyleRed' id='pbt".$i."'";} echo ">".$i."</span></div>";

		                                if($_REQUEST['p'] != $i)
		                                {
		                                    echo "</a>";
		                                }
		                            }

		                            echo "<div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>";
		                            echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='goodStyleRed' id='pbt".$numbers."'>".$numbers."</span></div></a>";

		                            if($_REQUEST['p'] == $numbers)
		                            {
		                                echo "<div class='admPageNumberBlockSide' id='pbNext' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>���������</span></div>";
		                            }
		                            else
		                            {
		                            	echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>���������</span></div></a>";
		                            }

		                            echo "</div>";
		                        }
		                        else
		                        {
		                            $check = $numbers - 3;

		                            if($_REQUEST['p'] >= 5 and $_REQUEST['p'] < $check)
		                            {
		                                echo "
		                                    <br /><br />
		                                    <div id='pageNumbers'>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>����������</span></div></a>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='goodStyleRed' id='pbt1'>1</span></div></a>
		                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] - 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")'><span class='goodStyleRed' id='pbt".($_REQUEST['p'] - 1)."'>".($_REQUEST['p'] - 1)."</span></div></a>
		                                        <div class='admPageNumberBlockActive'><span class='goodStyleWhite'>".$_REQUEST['p']."</span></div>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] + 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")'><span class='goodStyleRed' id='pbt".($_REQUEST['p'] + 1)."'>".($_REQUEST['p'] + 1)."</span></div></a>
		                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='goodStyleRed' id='pbt".$numbers."'>".$numbers."</span></div></a>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>���������</span></div></a>
		                                    </div>
		                                ";
		                            }
		                            else
		                            {
		                                echo "
		                                    <br /><br />
		                                    <div id='pageNumbers'>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>����������</span></div></a>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='goodStyleRed' id='pbt1'>1</span></div></a>
		                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
		                                ";

		                                for($i = ($numbers - 4); $i <= $numbers; $i++)
		                                {
		                                    if($_REQUEST['p'] != $i)
		                                    {
		                                        echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".$i."' class='noBorder'>";
		                                    }

		                                    echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='goodStyleWhite'";} else {echo "class='goodStyleRed' id='pbt".$i."'";} echo ">".$i."</span></div>";

		                                    if($_REQUEST['p'] != $i)
		                                    {
		                                        echo "</a>";
		                                    }
		                                }

		                                if($_REQUEST['p'] == $numbers)
		                                {
		                                    echo "<div class='admPageNumberBlockSide' id='pbNext' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
		                                }
		                                else
		                                {
		                                    echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>���������</span></div></a>";
		                                }   
		                            }
		                        }
		                    }
		                }

		                echo "</div>";
				}
			?>
        </div>
    </div>
    
    <div id='footerOrder'>
		<div id='footerContent'>
        	<div id='location'>
            	<span class='headerStyle'>���������� ��������</span>
               	<br />
                <span class='headerStyle'>�. �������</span>
            </div>
            <div id='copyright'>
                	<a href='index.php' class='noBorder'><span class='headerStyleRed'>�����-��</span></a><span class='headerStyle'> &copy; 2008 - <?php echo date('Y'); ?></span>
            </div>
            <div id='web'>
				<span class='headerStyle'>�������� �����</span>
                <br />
                <a href='http://airlab.by/' class='noBorder'><span class='headerStyleRed'>������ AIR LAB</span></a>
            </div>
        </div>
    </div>
    
    <script type='text/javascript'>
		footerPos();
		$(document).ready(function() {

			var nextRight = $('#pbNext').offset().left + $('#pbNext').width();
			var pnWidth = parseInt(nextRight - $('#pbPrev').offset().left);
			var cgWidth = $('#basketBlock').width();
			var pnShift = parseInt((cgWidth - pnWidth) / 2);

			$('#pageNumbers').css('margin-left', pnShift + 'px');

		});
	</script>

</body>

</html>