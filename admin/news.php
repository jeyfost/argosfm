<?php

	session_start();
	include('../scripts/connect.php');
	
	if(isset($_SESSION['query']))
	{
		unset($_SESSION['query']);
	}
	
	if(isset($_SESSION['quantity']))
	{
		unset($_SESSION['quantity']);
	}
	
	if(isset($_SESSION['userID']))
	{
		if(isset($_COOKIE['argosfm_login']) and isset($_COOKIE['argosfm_password']))
		{
			setcookie("argosfm_login", "", 0, '/');
			setcookie("argosfm_password", "", 0, '/');
			setcookie("argosfm_login", $_COOKIE['argosfm_login'], time()+60*60*24*30*12, '/');
			setcookie("argosfm_password", $_COOKIE['argosfm_password'], time()+60*60*24*30*12, '/');
		}
		else
		{
			$userResult = mysql_query("SELECT * FROM users WHERE id = '".$_SESSION['userID']."'");
			$user = mysql_fetch_array($userResult, MYSQL_ASSOC);
			setcookie("argosfm_login", $user['login'], time()+60*60*24*30*12, '/');
			setcookie("argosfm_password", $user['password'], time()+60*60*24*30*12, '/');
		}
	}
	else
	{
		if(isset($_COOKIE['argosfm_login']) and isset($_COOKIE['argosfm_password']) and !empty($_COOKIE['argosfm_login']) and !empty($_COOKIE['argosfm_password']))
		{
			$userResult = mysql_query("SELECT * FROM users WHERE login = '".$_COOKIE['argosfm_login']."'");
			$user = mysql_fetch_array($userResult, MYSQL_ASSOC);
			
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
	
	if(!empty($_REQUEST['id']))
	{
		$newsResult = mysql_query("SELECT * FROM news WHERE id = '".$_REQUEST['id']."'");
		
		if(mysql_num_rows($newsResult) == 0)
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
	}

?>

<!doctype html>

<html>

<head>

    <meta charset="windows-1251">
    <meta name='keywords' content='��������� ���������, ������������� ��� ������, �����-��, ������������� ��� ������ �������, �������, ��������� ���������, ������, ������ ���, ����� ���������, ��������� ��������� �������, ������ ��� �������, ����� ���������, ����� ��������� �������, ����� ��������� �������, ������ �������'>
    
    <title>�����-�� | ���������� �������</title>
    
    <link rel='shortcut icon' href='../pictures/icons/favicon.ico' type='image/x-icon'>
	<link rel='icon' href='../pictures/icons/favicon.ico' type='image/x-icon'>
    <link rel='stylesheet' media='screen' type='text/css' href='../css/style.css'>
    <?php
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false)
		{
			echo "<link rel='stylesheet' media='screen' type='text/css' href='css/styleOpera.css'>";
		}
	?>
    
    <script type='text/javascript' src='../js/menuAdmin.js'></script>
    <script type='text/javascript' src='../js/footer.js'></script>
    <script type='text/javascript' src='../js/jquery-1.8.3.min.js'></script>
	<script type='text/javascript' src='http://js.nicedit.com/nicEdit-latest.js'></script>
    
    <script type='text/javascript'>
		bkLib.onDomLoaded(function() {
			new nicEditor({fullPanel : true}).panelInstance('newsText');
		});
    </script>				

</head>

<body onresize = 'footerPos()'>

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
							<center><span class='headerStyleRed'>������ ���� ����������� ��������!</span></center>
							<br /><br />
							<span class='basic'>�����������! �� ������� ������ ������ ���� �����������. ��� ����������� ��������� �� ������ �� ������, ���������� �� ������ ����� ����������� �����. ���� ����� ����� ���, ��������� ����� \"����\".</span>
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
        	<form name='registrationForm' id='registrationForm' method='post' action='../scripts/registration.php'>
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
			<form name='passwordRecoveryForm' id='passwordRecoveryForm' method='post' action='../scripts/recovery.php'>
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
                            echo "<span class='basicRed'>�� ����� �������������� ����� ��� e-mail.</span><br /><br />";
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
        	<a href='../index.php' class='noBorder'>
                <div id='logo'>
                    <img src='../pictures/system/logo.png' class='noBorder' />
                </div>
            </a>
            <menu>
            	<a href='../index.php' class='noBorder'>
                    <div id='mainPoint' onmouseover='menuVisual("1", "mpIMG", "mpTop")' onmouseout='menuDefault1()'>
                        <div id='mainPointCenter'>
                            <div id='mpTop'></div>
                            <div class='pBottom'>
                                <img src='../pictures/system/mainText.png' id='mpIMG' class='noBorder'>
                            </div>
                        </div>
                    </div>
                </a>
                <a href='../catalogue.php' class='noBorder'>
                    <div id='cataloguePoint' onmouseover='menuVisual("1", "cpIMG", "cpTop")' onmouseout='menuDefault2()'>
                        <div id='cataloguePointCenter'>
                            <div id='cpTop'></div>
                            <div class='pBottom'>
                                <img src='../pictures/system/catalogueText.png' id='cpIMG' class='noBorder'>
                            </div>
                        </div>
                    </div>
                </a>
                <a href='../news.php' class='noBorder' title='�������, ����� � ������������ �����������'>
                    <div id='offersPoint' onmouseover='menuVisual("1", "opIMG", "opTop")' onmouseout='menuDefault3()'>
                        <div id='offersPointCenter'>
                            <div id='opTop'></div>
                            <div class='pBottom'>
                                <img src='../pictures/system/newsText.png' id='opIMG' class='noBorder'>
                            </div>
                        </div>
                    </div>
                </a>
                <a href='../contacts.php' class='noBorder'>
                    <div id='contactsPoint' onmouseover='menuVisual("1", "csIMG", "csTop")' onmouseout='menuDefault4()'>
                        <div id='contactsPointCenter'>
                            <div id='csTop'></div>
                            <div class='pBottom'>
                                <img src='../pictures/system/contactsText.png' id='csIMG' class='noBorder'>
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
								<img src='../pictures/system/login.png' class='noBorder' id='loginIcon' title='����� � ������ �������' />
							</div>
							
							<div id='loginBlockOuter' "; if(isset($_SESSION['login'])){echo "style='display: block;'";}else{echo "style='display: none;'";} echo ">
								<div id='loginBlock'>
									<form name='loginForm' id='loginForm' method='post' action='../scripts/login.php'>
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
								<a href='../settings.php?s=1' class='noBorder'><img src='../pictures/system/user.png' class='noBorder' id='userIcon' title='".$user['login']." | ������������ ��������' onmouseover='changeUserIcon(1)' onmouseout='changeUserIcon(0)' /></a>
							";
						
						if($_SESSION['userID'] != 1)
						{	
							$ordersResult = mysql_query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND status = '0'");
							$orders = mysql_num_rows($ordersResult);
							if($orders < 1)
							{
								echo "
									<a href='../order.php' class='noBorder'><img src='../pictures/system/basket.png' class='noBorder' id='basketIcon' title='�������' onmouseover='changeBasketIcon(1)' onmouseout='changeBasketIcon(0)' /></a>
								";
							}
							else
							{
								echo "
									<a href='../order.php' class='noBorder'><img src='../pictures/system/basketFull.png' class='noBorder' id='basketIcon' title='������� | ���������� �������: ".$orders."' onmouseover='changeBasketFullIcon(1)' onmouseout='changeBasketFullIcon(0)' /></a>
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
									<a href='../order.php' class='noBorder'><img src='../pictures/system/basket.png' class='noBorder' id='basketIcon' title='������' onmouseover='changeBasketIcon(1)' onmouseout='changeBasketIcon(0)' /></a>
								";
							}
							else
							{
								echo "
									<a href='../order.php' class='noBorder'><img src='../pictures/system/basketFull.png' class='noBorder' id='basketIcon' title='������� | ���������� ������: ".$orders."' onmouseover='changeBasketFullIcon(1)' onmouseout='changeBasketFullIcon(0)' /></a>
								";
							}
						}
						
						echo "
								<a href='../scripts/exit.php' class='noBorder'><img src='../pictures/system/exit.png' class='noBorder' id='exitIcon' title='����� �� ��������' onmouseover='changeExitIcon(1)' onmouseout='changeExitIcon(0)' /></a>
							</div>
						";
					}
					
					unset($_SESSION['login']);
					unset($_SESSION['userLogin']);
					unset($_SESSION['userPassword']);
                ?>
                <div id='searchBG'>
                    <form name='searchForm' id='searchForm' method='post' action='../scripts/search.php'>
                        <input type='text' id='searchField' name='searchQuery' placeholder='�����...' onfocus='if(this.value=="�����...") {this.value = "";}' onblur='if(this.value == "") {this.value = "�����...";}' value='�����...' onkeyup='lookup(this.value)'>
                        <input type='submit' id='searchSubmit' value='' title='�����'>
                    </form>
                </div>
            </div>
        </div>
    </header>
    
    <div id='content'>
    	<div id='basketBlock'>
        	<?php
			
				if(empty($_REQUEST['id']))
				{
					echo "<span class='headerStyle'>���������� �������</span>";
				}
				else
				{
					echo "<span class='headerStyle'>�������������� �������</span>";
				}
			
			?>
            <br /><br />
            <?php
			
				$newsResult = mysql_query("SELECT * FROM news WHERE id = '".$_REQUEST['id']."'");
				$news = mysql_fetch_assoc($newsResult);
            
				if(!empty($_SESSION['newsResult']))
				switch($_SESSION['newsResult'])
				{
					case 'success':
						echo "<span class='basicGreen'>������� ������� ���������!</span>";
						break;
					case 'empty':
						echo "<span class='basicRed'>��� ���������� ������� ���������� ��������� ��� ����.</span>";
						break;
					case 'failed':
						echo "<span class='basicRed'>��� ���������� ������� ��������� ������. ��������� �������.</span>";
						break;
					default: break;
				}
				
				unset($_SESSION['newsResult']);
				echo "<br /><br />";
				echo $_SESSION['nText'];
			?>
            
            <form method='post' <?php if(empty($_REQUEST['id'])){echo "action='../scripts/addNews.php'";}else{echo "action='../scripts/editNews.php?id=".$_REQUEST['id']."'";} ?> id='newsForm' enctype='multipart/form-data'>
            	<label>���������:</label>
                <br />
                <input type='text' name='newsHeader' id='newsHeader' <?php if(!empty($_REQUEST['id'])){echo "value = '".$news['header']."'";} if(isset($_SESSION['nHeader'])){echo "value = '".$_SESSION['nHeader']."'";} ?> />
                <br /><br />
                <div style='position: relative; margin: -56px 0 0 260px;'>
                	<label>������� ��������:</label>
                    <br />
                	<input type='text' name='newsDescription' id='newsDescription' <?php if(!empty($_REQUEST['id'])){echo "value = '".$news['short']."'";} if(isset($_SESSION['nDescription'])){echo "value = '".$_SESSION['nDescription']."'";} ?> style='width: 584px;' />
                </div>
                <br />
                <label>����� �������:</label>
                <br />
                <textarea id='newsText' name='newsText' style='width: 100%;' rows='20'><?php if(!empty($_REQUEST['id'])){echo $news['text'];} if(isset($_SESSION['nText'])){echo $_SESSION['nText'];} ?></textarea>
                <br /><br />
                
                <?php
				
					if(empty($_REQUEST['id']))
					{
						echo "<input type='submit' value='��������' id='addNewsSubmit' onclick='nicEditors.findEditor(\"newsText\").saveContent();' />";
					}
					else
					{
						echo "<input type='submit' value='�������������' id='addNewsSubmit' onclick='nicEditors.findEditor(\"newsText\").saveContent();' />";
					}
								
				?>
            </form>
            
            <?php
			
				if(empty($_REQUEST['id']))
				{
					echo "
					<a href='admin.php' class='noBorder'>
						<div id='backToAdminButton'>��������� � ������ �����������������</div>
					</a>
					";
				}
				else
				{
					echo "
						<a href='admin.php' class='noBorder'>
							<div id='backToAdminButton'>��������� � ������ �����������������</div>
						</a>
						<a href='../news.php?id=".$_REQUEST['id']."' class='noBorder'>
							<div id='backToCurrentNews'>�����</div>
						</a>
					";
				}
			
			?>
            
          	
            <?php
				unset($_SESSION['nHeader']);
				unset($_SESSION['nText']);
				unset($_SESSION['nDescription']);
			?>
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
    	$('#basketBlock').keyup(function() {
            var fullHeight = $('#basketBlock').height() - $('#basketBlock').offset().top + 220;
			if($('footer').offset().top < fullHeight) {
				$('footer').offset({top: fullHeight});
			}
        });
    </script>
    <script type='text/javascript'>
		$(window).load(function() {
            var fullHeight = $('#basketBlock').height() - $('#basketBlock').offset().top + 220;
			if($('footer').offset().top < fullHeight) {
				$('footer').offset({top: fullHeight});
			}
        });
	</script>
    
</body>

</html>