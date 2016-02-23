<?php

	session_start();

	if(isset($_SESSION['query']))
	{
		unset($_SESSION['query']);
	}
	
	if(isset($_SESSION['quantity']))
	{
		unset($_SESSION['quantity']);
	}

	include('scripts/connect.php');
	
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

	if(!empty($_REQUEST['type']))
	{
		if($_REQUEST['type'] != 'fa' and $_REQUEST['type'] != 'em' and $_REQUEST['type'] != 'ca')
		{
			header("Location: catalogue.php");
		}
	}
	
	if(!empty($_REQUEST['category']))
	{
		$categoryResult = mysql_query("SELECT MAX(id) FROM categories_new");
		$category = mysql_fetch_array($categoryResult, MYSQL_NUM);
		
		if($_REQUEST['category'] > $category[0] or $_REQUEST['category'] < 1 or !is_numeric($_REQUEST['category']))
		{
			if(!empty($_REQUEST['type']))
			{
				header("Location: catalogue.php?type=".$_REQUEST['type']);
			}
			else
			{
				header("Location: catalogue.php");
			}
		}
	}
	
	if(!empty($_REQUEST['subcategory']))
	{
		if(empty($_REQUEST['category']) or empty($_REQUEST['type']))
		{
			header("Location: catalogue.php");
		}
	}
	
	if(!empty($_REQUEST['category']))
	{
		if(empty($_REQUEST['type']))
		{
			header("Location: catalogue.php");
		}
	}
	
	function checkingStatus($sid, $arr)
	{	
		$status = 0;
		
		for($i = 0; $i < count($arr); $i++)
		{
			if($sid == $arr[$i])
			{
				$status++;
			}
		}
		
		if($status > 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	$c_result = mysql_query("SELECT MAX(id) FROM categories_new");
	$c = mysql_fetch_array($c_result, MYSQL_NUM);
	
	if(!empty($_REQUEST['category']) and ($_REQUEST['c'] > $c[0] or $_REQUEST['category'] < 1))
	{
		header("Location: catalogue.php");	
	}
	
	$s_result = mysql_query("SELECT MAX(id) FROM subcategories_new");
	$s = mysql_fetch_array($s_result, MYSQL_NUM);
		
	if(!empty($_REQUEST['category']) and empty($_REQUEST['subcategory']))
	{
		$sCountResult = mysql_query("SELECT COUNT(id) FROM subcategories_new WHERE category = '".$_REQUEST['category']."'");
		$sCount = mysql_fetch_array($sCountResult, MYSQL_NUM);

		if($sCount[0] == 1)
		{
			$subcategoryResult = mysql_query("SELECT id FROM subcategories_new WHERE category = '".$_REQUEST['category']."'");
			$subcategory = mysql_fetch_array($subcategoryResult, MYSQL_NUM);

			if($subcategory[0] >= 1000)
			{
				header("Location: catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$subcategory[0]);
			}
		}
	}

	if(empty($_REQUEST['type']))
	{
		if(empty($_REQUEST['p']))
		{
			header("Location: catalogue.php?p=1");
		}
		
		$quantity_result = mysql_query("SELECT COUNT(id) FROM catalogue_new");
		$quantity = mysql_fetch_array($quantity_result, MYSQL_NUM);
									
		if($quantity[0] > 10)
		{
			if($quantity[0] % 10 != 0)
			{
				$numbers = intval(($quantity[0] / 10) + 1);
			}
			else
			{
				$numbers = intval($quantity[0] / 10);
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
		if(empty($_REQUEST['category']))
		{
			if(empty($_REQUEST['p']))
			{
				header("Location: catalogue.php?type=".$_REQUEST['type']."&p=1");
			}
			
			$quantity_result = mysql_query("SELECT COUNT(id) FROM catalogue_new WHERE type = '".$_REQUEST['type']."'");
			$quantity = mysql_fetch_array($quantity_result, MYSQL_NUM);
										
			if($quantity[0] > 10)
			{
				if($quantity[0] % 10 != 0)
				{
					$numbers = intval(($quantity[0] / 10) + 1);
				}
				else
				{
					$numbers = intval($quantity[0] / 10);
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
			if(empty($_REQUEST['subcategory']))
			{
				if(empty($_REQUEST['p']))
				{
					header("Location: catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&p=1");
				}
				
				$quantity_result = mysql_query("SELECT COUNT(id) FROM catalogue_new WHERE category = '".$_REQUEST['category']."'");
				$quantity = mysql_fetch_array($quantity_result, MYSQL_NUM);
											
				if($quantity[0] > 10)
				{
					if($quantity[0] % 10 != 0)
					{
						$numbers = intval(($quantity[0] / 10) + 1);
					}
					else
					{
						$numbers = intval($quantity[0] / 10);
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
				if(empty($_REQUEST['subcategory2']))
				{
					if(empty($_REQUEST['p']))
					{
						header("Location: catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$_REQUEST['subcategory']."&p=1");
					}
					
					$quantity_result = mysql_query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory = '".$_REQUEST['subcategory']."'");
					$quantity = mysql_fetch_array($quantity_result, MYSQL_NUM);
												
					if($quantity[0] > 10)
					{
						if($quantity[0] % 10 != 0)
						{
							$numbers = intval(($quantity[0] / 10) + 1);
						}
						else
						{
							$numbers = intval($quantity[0] / 10);
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
					if(empty($_REQUEST['p']))
					{
						header("Location: catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$_REQUEST['subcategory']."&subcategory2=".$_REQUEST['subcategory2']."&p=1");
					}
					
					$quantity_result = mysql_query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['subcategory2']."'");
					$quantity = mysql_fetch_array($quantity_result, MYSQL_NUM);
												
					if($quantity[0] > 10)
					{
						if($quantity[0] % 10 != 0)
						{
							$numbers = intval(($quantity[0] / 10) + 1);
						}
						else
						{
							$numbers = intval($quantity[0] / 10);
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
	}
	
	mysql_set_charset("cp1251");
	
	$rateResult = mysql_query("SELECT rate FROM currency WHERE code = 'usd'");
	$rate = mysql_fetch_array($rateResult, MYSQL_NUM);

?>

<!doctype html>

<html>

<head>

    <meta charset="windows-1251">
    <meta name='keywords' content='��������� ���������, ������������� ��� ������, �����-��, ������������� ��� ������ �������, �������, ��������� ���������, ������, ������ ���, ����� ���������, ��������� ��������� �������, ������ ��� �������, ����� ���������, ����� ��������� �������, ����� ��������� �������, ������ �������'>
    
    <?php
		if(empty($_REQUEST['type']))
		{
			echo "<title>�����-�� | �������</title>";
		}
		else
		{
			if($_REQUEST['type'] == 'fa')
			{
				echo "<title>������� ��������� ���������</title>";
			}
			
			if($_REQUEST['type'] == 'em')
			{
				echo "<title>������� ��������� ����������</title>";
			}
			
			if($_REQUEST['type'] == 'ca')
			{
				echo "<title>������� ����������� ��� ����</title>";
			}
		}
	?>
    
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
    <script type='text/javascript' src='js/footerC.js'></script>
    <script type='text/javascript' src='js/catalogue.js'></script>
    <script type='text/javascript' src='js/jquery-1.8.3.min.js'></script>
    <script type='text/javascript' src='js/shadowbox/source/shadowbox.js'></script>
    <script type='text/javascript' src='js/ajax.js'></script>

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
							<span class='basic'>��� ������ ��� �������. ����� ������ ����� ������, �������� ������ �� ������, ���������� ��� �����������: <b>".$_SESSION['recovery_email']."</b></span>
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
                    <div id='cataloguePoint'>
                        <div id='cataloguePointCenter'>
                            <div id='cpTopActive'></div>
                            <div class='pBottom'>
                                <img src='pictures/system/catalogueTextRed.png' id='cpIMG' class='noBorder'>
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
									<a href='order.php' class='noBorder'><img src='pictures/system/basket.png' class='noBorder' id='basketIcon' title='�������' onmouseover='changeBasketIcon(1)' onmouseout='changeBasketIcon(0)' /></a>
								";
							}
							else
							{
								echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basketFull.png' class='noBorder' id='basketIcon' title='������� | ���������� �������: ".$orders."' onmouseover='changeBasketFullIcon(1)' onmouseout='changeBasketFullIcon(0)' /></a>
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
									<a href='order.php' class='noBorder'><img src='pictures/system/basket.png' class='noBorder' id='basketIcon' title='������' onmouseover='changeBasketIcon(1)' onmouseout='changeBasketIcon(0)' /></a>
								";
							}
							else
							{
								echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basketFull.png' class='noBorder' id='basketIcon' title='������� | ���������� ������: ".$orders."' onmouseover='changeBasketFullIcon(1)' onmouseout='changeBasketFullIcon(0)' /></a>
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
    	<div id='cataloguePoints'>
        	<a href='catalogue.php?type=fa' class='noBorder'>
            	<?php
					if($_REQUEST['type'] == 'fa')
					{
						echo "<span class='headerStyleRed'>��������� ���������</span>";
					}
					else
					{
						echo "<span class='headerStyleHover'>��������� ���������</span>";
					}
				?>
            </a>
            <div class='categoriesBlock'>
            	<?php
					
					if(empty($_REQUEST['category']))
					{
						$fa_categoriesResult = mysql_query("SELECT * FROM categories_new WHERE type = 'fa' ORDER BY name");
						while($fa_categories = mysql_fetch_array($fa_categoriesResult, MYSQL_ASSOC))
						{
							echo "
								<div class='categoryLine'>
									<div class='icon' onmouseover='categoryColor(\"1\", \"i".$fa_categories['id']."\", \"n".$fa_categories['id']."\", \"".$fa_categories['picture_red']."\")' onmouseout='categoryColor(\"0\", \"i".$fa_categories['id']."\", \"n".$fa_categories['id']."\", \"".$fa_categories['picture']."\"); document.getElementById(\"n".$fa_categories['id']."\").style.color = \"#3f3f3f\"'>
										<a href='catalogue.php?type=fa&category=".$fa_categories['id']."' class='noBorder'><img src='pictures/icons/".$fa_categories['picture']."' class='noBorder' id='i".$fa_categories['id']."' /></a>
									</div>
									<div class='categoryName'>
										<a href='catalogue.php?type=fa&category=".$fa_categories['id']."' class='noBorder'><span class='basic' id='n".$fa_categories['id']."' onmouseover='categoryColor(\"1\", \"i".$fa_categories['id']."\", \"n".$fa_categories['id']."\", \"".$fa_categories['picture_red']."\")' onmouseout='categoryColor(\"0\", \"i".$fa_categories['id']."\", \"n".$fa_categories['id']."\", \"".$fa_categories['picture']."\"); document.getElementById(\"n".$fa_categories['id']."\").style.color = \"#3f3f3f\"'>".$fa_categories['name']."</span></a>
									</div>
								</div>
							";
						}
					}
					else
					{
						$fa_categoriesResult = mysql_query("SELECT * FROM categories_new WHERE type = 'fa' ORDER BY name");
						while($fa_categories = mysql_fetch_array($fa_categoriesResult, MYSQL_ASSOC))
						{
							if($_REQUEST['category'] != $fa_categories['id'])
							{
								echo "
									<div class='categoryLine'>
										<div class='icon' onmouseover='categoryColor(\"1\", \"i".$fa_categories['id']."\", \"n".$fa_categories['id']."\", \"".$fa_categories['picture_red']."\")' onmouseout='categoryColor(\"0\", \"i".$fa_categories['id']."\", \"n".$fa_categories['id']."\", \"".$fa_categories['picture']."\"); document.getElementById(\"n".$fa_categories['id']."\").style.color = \"#3f3f3f\"'>
											<a href='catalogue.php?type=fa&category=".$fa_categories['id']."' class='noBorder'><img src='pictures/icons/".$fa_categories['picture']."' class='noBorder' id='i".$fa_categories['id']."' /></a>
										</div>
										<div class='categoryName'>
											<a href='catalogue.php?type=fa&category=".$fa_categories['id']."' class='noBorder'><span class='basic' id='n".$fa_categories['id']."' onmouseover='categoryColor(\"1\", \"i".$fa_categories['id']."\", \"n".$fa_categories['id']."\", \"".$fa_categories['picture_red']."\")' onmouseout='categoryColor(\"0\", \"i".$fa_categories['id']."\", \"n".$fa_categories['id']."\", \"".$fa_categories['picture']."\"); document.getElementById(\"n".$fa_categories['id']."\").style.color = \"#3f3f3f\"'>".$fa_categories['name']."</span></a>
										</div>
									</div>
								";
							}
							else
							{
								echo "
									<div class='categoryLine'>
										<div class='icon'>
											<a href='catalogue.php?type=fa&category=".$fa_categories['id']."' class='noBorder'><img src='pictures/icons/".$fa_categories['picture_red']."' class='noBorder' id='i".$fa_categories['id']."' /></a>
										</div>
										<div class='categoryName'>
											<a href='catalogue.php?type=fa&category=".$fa_categories['id']."' class='noBorder'><span class='basicRed' id='n".$fa_categories['id']."'>".$fa_categories['name']."</span></a>
										</div>
										<div class='subcategoriesBlock'>
								";
								
								if(empty($_REQUEST['subcategory']))
								{
									$subcategoriesResult = mysql_query("SELECT * FROM subcategories_new WHERE category = '".$_REQUEST['category']."' ORDER BY name");
									while($subcategories = mysql_fetch_array($subcategoriesResult))
									{
										echo "
											<br />
											<span class='basic' id='s".$subcategories['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$subcategories['id']."&p=1' class='noBorder'><span class='basicHover'>".$subcategories['name']."</span></a>
										";
									}
								}
								else
								{
									if($_REQUEST['subcategory'] < 1000)
									{
										if(checkingStatus($_REQUEST['subcategory'], $ids))
										{
											$subcategoriesResult = mysql_query("SELECT * FROM subcategories_new WHERE category = '".$_REQUEST['category']."' ORDER BY name");
											while($subcategories = mysql_fetch_array($subcategoriesResult))
											{
												if($_REQUEST['subcategory'] != $subcategories['id'])
												{
													echo "
														<br />
														<span class='basic' id='s".$subcategories['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$subcategories['id']."&p=1' class='noBorder'><span class='basicHover'>".$subcategories['name']."</span></a>
													";
												}
												else
												{
													echo "
														<br />
														<span class='basicRed' id='s".$subcategories['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$subcategories['id']."&p=1' class='noBorder'><span class='basicRed'>".$subcategories['name']."</span></a>
													";
													
													$s2CountResult = mysql_query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$_REQUEST['subcategory']."'");
													$s2Count = mysql_fetch_array($s2CountResult, MYSQL_NUM);
													
													if($s2Count[0] != 0)
													{
														echo "<div class='subcategories2Block'>";
														
														$s2Result = mysql_query("SELECT * FROM subcategories2 WHERE subcategory = '".$_REQUEST['subcategory']."'");
														while($s2 = mysql_fetch_array($s2Result, MYSQL_ASSOC))
														{
															if(empty($_REQUEST['subcategory2']))
															{
																echo "
																	<br />
																	<span class='basic' id='s2".$s2['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$_REQUEST['subcategory']."&subcategory2=".$s2['id']."&p=1' class='noBorder'><span class='basicHover'>".$s2['name']."</span></a>
																";
															}
															else
															{
																if($_REQUEST['subcategory2'] == $s2['id'])
																{
																	echo "
																		<br />
																		<span class='basicRed' id='s2".$s2['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$_REQUEST['subcategory']."&subcategory2=".$s2['id']."&p=1' class='noBorder'><span class='basicRed'>".$s2['name']."</span></a>
																	";
																}
																else
																{
																	echo "
																		<br />
																		<span class='basic' id='s2".$s2['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$_REQUEST['subcategory']."&subcategory2=".$s2['id']."&p=1' class='noBorder'><span class='basicHover'>".$s2['name']."</span></a>
																	";
																}
															}
														}
														
														echo "</div>";
													}
												}
											}
										}
									}
								}
								
								echo "
										</div>
									</div>
								";
							}
						}
					}
					
				?>
            </div>
            <a href='catalogue.php?type=em' class='noBorder'>
				<?php
                    if($_REQUEST['type'] == 'em')
                    {
                        echo "<span class='headerStyleRed'>��������� ���������</span>";
                    }
                    else
                    {
                        echo "<span class='headerStyleHover'>��������� ���������</span>";
                    }
                ?>
            </a>
            <div class='categoriesBlock'>
            	<?php
					
					if(empty($_REQUEST['category']))
					{
						$em_categoriesResult = mysql_query("SELECT * FROM categories_new WHERE type = 'em' ORDER BY name");
						while($em_categories = mysql_fetch_array($em_categoriesResult, MYSQL_ASSOC))
						{
							echo "
								<div class='categoryLine'>
									<div class='icon' onmouseover='categoryColor(\"1\", \"i".$em_categories['id']."\", \"n".$em_categories['id']."\", \"".$em_categories['picture_red']."\")' onmouseout='categoryColor(\"0\", \"i".$em_categories['id']."\", \"n".$em_categories['id']."\", \"".$em_categories['picture']."\"); document.getElementById(\"n".$em_categories['id']."\").style.color = \"#3f3f3f\"'>
										<a href='catalogue.php?type=em&category=".$em_categories['id']."' class='noBorder'><img src='pictures/icons/".$em_categories['picture']."' class='noBorder' id='i".$em_categories['id']."' /></a>
									</div>
									<div class='categoryName'>
										<a href='catalogue.php?type=em&category=".$em_categories['id']."' class='noBorder'><span class='basic' id='n".$em_categories['id']."' onmouseover='categoryColor(\"1\", \"i".$em_categories['id']."\", \"n".$em_categories['id']."\", \"".$em_categories['picture_red']."\")' onmouseout='categoryColor(\"0\", \"i".$em_categories['id']."\", \"n".$em_categories['id']."\", \"".$em_categories['picture']."\"); document.getElementById(\"n".$em_categories['id']."\").style.color = \"#3f3f3f\"'>".$em_categories['name']."</span></a>
									</div>
								</div>
							";
						}
					}
					else
					{
						$em_categoriesResult = mysql_query("SELECT * FROM categories_new WHERE type = 'em' ORDER BY name");
						while($em_categories = mysql_fetch_array($em_categoriesResult, MYSQL_ASSOC))
						{
							if($_REQUEST['category'] != $em_categories['id'])
							{
								echo "
									<div class='categoryLine'>
										<div class='icon' onmouseover='categoryColor(\"1\", \"i".$em_categories['id']."\", \"n".$em_categories['id']."\", \"".$em_categories['picture_red']."\")' onmouseout='categoryColor(\"0\", \"i".$em_categories['id']."\", \"n".$em_categories['id']."\", \"".$em_categories['picture']."\"); document.getElementById(\"n".$em_categories['id']."\").style.color = \"#3f3f3f\"'>
											<a href='catalogue.php?type=em&category=".$em_categories['id']."' class='noBorder'><img src='pictures/icons/".$em_categories['picture']."' class='noBorder' id='i".$em_categories['id']."' /></a>
										</div>
										<div class='categoryName'>
											<a href='catalogue.php?type=em&category=".$em_categories['id']."' class='noBorder'><span class='basic' id='n".$em_categories['id']."' onmouseover='categoryColor(\"1\", \"i".$em_categories['id']."\", \"n".$em_categories['id']."\", \"".$em_categories['picture_red']."\")' onmouseout='categoryColor(\"0\", \"i".$em_categories['id']."\", \"n".$em_categories['id']."\", \"".$em_categories['picture']."\"); document.getElementById(\"n".$em_categories['id']."\").style.color = \"#3f3f3f\"'>".$em_categories['name']."</span></a>
										</div>
									</div>
								";
							}
							else
							{
								echo "
									<div class='categoryLine'>
										<div class='icon'>
											<a href='catalogue.php?type=em&category=".$em_categories['id']."' class='noBorder'><img src='pictures/icons/".$em_categories['picture_red']."' class='noBorder' id='i".$em_categories['id']."' /></a>
										</div>
										<div class='categoryName'>
											<a href='catalogue.php?type=em&category=".$em_categories['id']."' class='noBorder'><span class='basicRed' id='n".$em_categories['id']."'>".$em_categories['name']."</span></a>
										</div>
										<div class='subcategoriesBlock'>
								";
								
								if(empty($_REQUEST['subcategory']))
								{
									$subcategoriesResult = mysql_query("SELECT * FROM subcategories_new WHERE category = '".$_REQUEST['category']."' ORDER BY name");
									while($subcategories = mysql_fetch_array($subcategoriesResult))
									{
										echo "
											<br />
											<span class='basic' id='s".$subcategories['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$subcategories['id']."&p=1' class='noBorder'><span class='basicHover'>".$subcategories['name']."</span></a>
										";
									}
								}
								else
								{
									if($_REQUEST['subcategory'] < 1000)
									{
										$subcategoriesResult = mysql_query("SELECT * FROM subcategories_new WHERE category = '".$_REQUEST['category']."' ORDER BY name");
										while($subcategories = mysql_fetch_array($subcategoriesResult))
										{
											if($_REQUEST['subcategory'] != $subcategories['id'])
											{
												echo "
													<br />
													<span class='basic' id='s".$subcategories['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$subcategories['id']."&p=1' class='noBorder'><span class='basicHover'>".$subcategories['name']."</span></a>
												";
											}
											else
											{
												echo "
													<br />
													<span class='basicRed' id='s".$subcategories['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$subcategories['id']."&p=1' class='noBorder'><span class='basicRed'>".$subcategories['name']."</span></a>
												";
												
												$s2CountResult = mysql_query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$_REQUEST['subcategory']."'");
													$s2Count = mysql_fetch_array($s2CountResult, MYSQL_NUM);
													
													if($s2Count[0] != 0)
													{
														echo "<div class='subcategories2Block'>";
														
														$s2Result = mysql_query("SELECT * FROM subcategories2 WHERE subcategory = '".$_REQUEST['subcategory']."'");
														while($s2 = mysql_fetch_array($s2Result, MYSQL_ASSOC))
														{
															if(empty($_REQUEST['subcategory2']))
															{
																echo "
																	<br />
																	<span class='basic' id='s2".$s2['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$_REQUEST['subcategory']."&subcategory2=".$s2['id']."&p=1' class='noBorder'><span class='basicHover'>".$s2['name']."</span></a>
																";
															}
															else
															{
																if($_REQUEST['subcategory2'] == $s2['id'])
																{
																	echo "
																		<br />
																		<span class='basicRed' id='s2".$s2['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$_REQUEST['subcategory']."&subcategory2=".$s2['id']."&p=1' class='noBorder'><span class='basicRed'>".$s2['name']."</span></a>
																	";
																}
																else
																{
																	echo "
																		<br />
																		<span class='basic' id='s2".$s2['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$_REQUEST['subcategory']."&subcategory2=".$s2['id']."&p=1' class='noBorder'><span class='basicHover'>".$s2['name']."</span></a>
																	";
																}
															}
														}
														
														echo "</div>";
													}
											}
										}
									}
								}
								
								echo "
										</div>
									</div>
								";
							}
						}
					}
				
				?>
            </div>
            <a href='catalogue.php?type=ca' class='noBorder'>
				<?php
                    if($_REQUEST['type'] == 'ca')
                    {
                        echo "<span class='headerStyleRed'>���������� ��� ����</span>";
                    }
                    else
                    {
                        echo "<span class='headerStyleHover'>���������� ��� ����</span>";
                    }
                ?>
            </a>
            <div class='categoriesBlock'>
            	<?php
					
					if(empty($_REQUEST['category']))
					{
						$ca_categoriesResult = mysql_query("SELECT * FROM categories_new WHERE type = 'ca' ORDER BY name");
						while($ca_categories = mysql_fetch_array($ca_categoriesResult, MYSQL_ASSOC))
						{
							echo "
								<div class='categoryLine'>
									<div class='icon' onmouseover='categoryColor(\"1\", \"i".$ca_categories['id']."\", \"n".$ca_categories['id']."\", \"".$ca_categories['picture_red']."\")' onmouseout='categoryColor(\"0\", \"i".$ca_categories['id']."\", \"n".$ca_categories['id']."\", \"".$ca_categories['picture']."\"); document.getElementById(\"n".$ca_categories['id']."\").style.color = \"#3f3f3f\"'>
										<a href='catalogue.php?type=ca&category=".$ca_categories['id']."' class='noBorder'><img src='pictures/icons/".$ca_categories['picture']."' class='noBorder' id='i".$ca_categories['id']."' /></a>
									</div>
									<div class='categoryName'>
										<a href='catalogue.php?type=ca&category=".$ca_categories['id']."' class='noBorder'><span class='basic' id='n".$ca_categories['id']."' onmouseover='categoryColor(\"1\", \"i".$ca_categories['id']."\", \"n".$ca_categories['id']."\", \"".$ca_categories['picture_red']."\")' onmouseout='categoryColor(\"0\", \"i".$ca_categories['id']."\", \"n".$ca_categories['id']."\", \"".$ca_categories['picture']."\"); document.getElementById(\"n".$ca_categories['id']."\").style.color = \"#3f3f3f\"'>".$ca_categories['name']."</span></a>
									</div>
								</div>
							";
						}
					}
					else
					{
						$ca_categoriesResult = mysql_query("SELECT * FROM categories_new WHERE type = 'ca' ORDER BY name");
						while($ca_categories = mysql_fetch_array($ca_categoriesResult, MYSQL_ASSOC))
						{
							if($_REQUEST['category'] != $ca_categories['id'])
							{
								echo "
									<div class='categoryLine'>
										<div class='icon' onmouseover='categoryColor(\"1\", \"i".$ca_categories['id']."\", \"n".$ca_categories['id']."\", \"".$ca_categories['picture_red']."\")' onmouseout='categoryColor(\"0\", \"i".$ca_categories['id']."\", \"n".$ca_categories['id']."\", \"".$ca_categories['picture']."\"); document.getElementById(\"n".$ca_categories['id']."\").style.color = \"#3f3f3f\"'>
											<a href='catalogue.php?type=ca&category=".$ca_categories['id']."' class='noBorder'><img src='pictures/icons/".$ca_categories['picture']."' class='noBorder' id='i".$ca_categories['id']."' /></a>
										</div>
										<div class='categoryName'>
											<a href='catalogue.php?type=fa&category=".$ca_categories['id']."' class='noBorder'><span class='basic' id='n".$ca_categories['id']."' onmouseover='categoryColor(\"1\", \"i".$ca_categories['id']."\", \"n".$ca_categories['id']."\", \"".$ca_categories['picture_red']."\")' onmouseout='categoryColor(\"0\", \"i".$ca_categories['id']."\", \"n".$ca_categories['id']."\", \"".$ca_categories['picture']."\"); document.getElementById(\"n".$ca_categories['id']."\").style.color = \"#3f3f3f\"'>".$ca_categories['name']."</span></a>
										</div>
									</div>
								";
							}
							else
							{
								echo "
									<div class='categoryLine'>
										<div class='icon'>
											<a href='catalogue.php?type=ca&category=".$ca_categories['id']."' class='noBorder'><img src='pictures/icons/".$ca_categories['picture_red']."' class='noBorder' id='i".$ca_categories['id']."' /></a>
										</div>
										<div class='categoryName'>
											<a href='catalogue.php?type=ca&category=".$ca_categories['id']."' class='noBorder'><span class='basicRed' id='n".$ca_categories['id']."'>".$ca_categories['name']."</span></a>
										</div>
										<div class='subcategoriesBlock'>
								";
								
								if(empty($_REQUEST['subcategory']))
								{
									$subcategoriesResult = mysql_query("SELECT * FROM subcategories_new WHERE category = '".$_REQUEST['category']."' ORDER BY name");
									while($subcategories = mysql_fetch_array($subcategoriesResult))
									{
										echo "
											<br />
											<span class='basic' id='s".$subcategories['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$subcategories['id']."&p=1' class='noBorder'><span class='basicHover'>".$subcategories['name']."</span></a>
										";
									}
								}
								else
								{
									if($_REQUEST['subcategory'] < 1000)
									{
										if(checkingStatus($_REQUEST['subcategory'], $ids))
										{
											$subcategoriesResult = mysql_query("SELECT * FROM subcategories_new WHERE category = '".$_REQUEST['category']."' ORDER BY name");
											while($subcategories = mysql_fetch_array($subcategoriesResult))
											{
												if($_REQUEST['subcategory'] != $subcategories['id'])
												{
													echo "
														<br />
														<span class='basic' id='s".$subcategories['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$subcategories['id']."&p=1' class='noBorder'><span class='basicHover'>".$subcategories['name']."</span></a>
													";
												}
												else
												{
													echo "
														<br />
														<span class='basicRed' id='s".$subcategories['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$subcategories['id']."&p=1' class='noBorder'><span class='basicRed'>".$subcategories['name']."</span></a>
													";
													
													$s2CountResult = mysql_query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$_REQUEST['subcategory']."'");
													$s2Count = mysql_fetch_array($s2CountResult, MYSQL_NUM);
													
													if($s2Count[0] != 0)
													{
														echo "<div class='subcategories2Block'>";
														
														$s2Result = mysql_query("SELECT * FROM subcategories2 WHERE subcategory = '".$_REQUEST['subcategory']."'");
														while($s2 = mysql_fetch_array($s2Result, MYSQL_ASSOC))
														{
															if(empty($_REQUEST['subcategory2']))
															{
																echo "
																	<br />
																	<span class='basic' id='s2".$s2['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$_REQUEST['subcategory']."&subcategory2=".$s2['id']."&p=1' class='noBorder'><span class='basicHover'>".$s2['name']."</span></a>
																";
															}
															else
															{
																if($_REQUEST['subcategory2'] == $s2['id'])
																{
																	echo "
																		<br />
																		<span class='basicRed' id='s2".$s2['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$_REQUEST['subcategory']."&subcategory2=".$s2['id']."&p=1' class='noBorder'><span class='basicRed'>".$s2['name']."</span></a>
																	";
																}
																else
																{
																	echo "
																		<br />
																		<span class='basic' id='s2".$s2['id']."'>- </span><a href='catalogue.php?type=".$_REQUEST['type']."&category=".$_REQUEST['category']."&subcategory=".$_REQUEST['subcategory']."&subcategory2=".$s2['id']."&p=1' class='noBorder'><span class='basicHover'>".$s2['name']."</span></a>
																	";
																}
															}
														}
														
														echo "</div>";
													}
												}
											}
										}
									}
								}
								
								echo "
										</div>
									</div>
								";
							}
						}
					}
				
				?>
            </div>
        </div>
        <div id='catalogueGoods'>
        	<?php
			
				if(empty($_REQUEST['type']))
				{
					echo "<span class='headerStyle'>������ ������ �������</span><br /><br /><br />";
					
					$goodsResult = mysql_query("SELECT * FROM catalogue_new ORDER BY RAND() LIMIT ".$start.", 10");
					while($goods = mysql_fetch_array($goodsResult))
					{
						echo "
							<div class='goodBlock'>
								<div class='picture'>
									<a href='pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='pictures/catalogue/small/".$goods['small']."' class='noBorder' /></a>
								</div>
								<div class='goodContent'>
									<div class='goodTopLine'>
										<div class='redStripe'></div>
										<div class='goodName'>
											<span class='goodStyle'>".$goods['name']."</span>
										</div>
									</div>
									<div class='goodDescription'>
										<div class='goodDescriptionLeft'>
											<span class='basic'>".$goods['description']."</span>
											<br /><br />
											<div>
												<div class='goodCode'><span class='basic'><b>�������:</b> ".$goods['code']."</span></div>
												<div class='goodPrice'"; if($_SESSION['userID'] == 1) {echo " id='gp".$goods['id']."' onclick='showForm(\"gp".$goods['id']."\", \"".$goods['price']."\", \"".$goods['id']."\", \"".$rate[0]."\")' style='cursor: pointer;'";} echo "><span class='basic'><b>����:</b> ".ceil($goods['price']*$rate[0])." ���. ���.</span></div>
											</div>
										</div>
						";
						if(isset($_SESSION['userID']) and $_SESSION['userID'] != 1)
						{
							echo "
											<div class='goodDescriptionRight'>
												<form class='toBasketForm".$goods['id']."' method='post' style='z-index: 999;'>
													<label>����������:</label>
													<br />
													<div style='padding-top: 8px;'>
														<input type='text' value='1' class='catalogueInput' name='quantity".$goods['id']."' id='quantity".$goods['id']."' onKeyUp='validateQuantity(\"quantity".$goods['id']."\")' onChange='validateQuantity(\"quantity".$goods['id']."\")' />
														<div onclick='addToBasket(\"".$goods['id']."\")'><img src='pictures/system/toBasket.png' id='toBasket".$goods['id']."' class='toBasketSubmit' onmouseover='toBasket(1, \"toBasket{$goods['id']}\")' onmouseout='toBasket(0, \"toBasket{$goods['id']}\")' title='�������� � �������' /></div>
														<div id='note".$goods['id']."' class='basic' style='position: relative; float: left; margin-top: 7px;'></div>
													</div>
												</form>
											</div>
							";
						}
						echo "
										</div>
							";
						
						if(!empty($goods['sketch']))
						{
							echo "
								<div class='sketch'>
									<a href='pictures/catalogue/sketch/".$goods['sketch']."' class='noBorder' rel='lightbox'><span class='sketchStyle'>������</span></a>
								</div>
							";
						}
						echo "
								</div>
							</div>
						";
					}
				}
				else
				{
					if(empty($_REQUEST['category']))
					{
						switch($_REQUEST['type'])
						{
							case 'fa':
								echo "<span class='headerStyle'>��������� ���������</span><br /><br /><br />";
								break;
							case 'em':
								echo "<span class='headerStyle'>��������� ���������</span><br /><br /><br />";
								break;
							case 'ca':
								echo "<span class='headerStyle'>���������� ��� ����</span><br /><br /><br />";
								break;
							default:
								break;
						}
					
						$goodsResult = mysql_query("SELECT * FROM catalogue_new WHERE type = '".$_REQUEST['type']."' ORDER BY RAND() LIMIT ".$start.", 10");
						while($goods = mysql_fetch_array($goodsResult))
						{
							echo "
								<div class='goodBlock'>
									<div class='picture'>
										<a href='pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='pictures/catalogue/small/".$goods['small']."' class='noBorder' /></a>
									</div>
									<div class='goodContent'>
										<div class='goodTopLine'>
											<div class='redStripe'></div>
											<div class='goodName'>
												<span class='goodStyle'>".$goods['name']."</span>
											</div>
										</div>
										<div class='goodDescription'>
											<div class='goodDescriptionLeft'>
												<span class='basic'>".$goods['description']."</span>
												<br /><br />
												<div>
													<div class='goodCode'><span class='basic'><b>�������:</b> ".$goods['code']."</span></div>
													<div class='goodPrice'"; if($_SESSION['userID'] == 1) {echo " id='gp".$goods['id']."' onclick='showForm(\"gp".$goods['id']."\", \"".$goods['price']."\", \"".$goods['id']."\", \"".$rate[0]."\")' style='cursor: pointer;'";} echo "><span class='basic'><b>����:</b> ".ceil($goods['price']*$rate[0])." ���. ���.</span></div>
												</div>
											</div>
							";
							if(isset($_SESSION['userID']) and $_SESSION['userID'] != 1)
							{
								echo "
												<div class='goodDescriptionRight'>
													<form class='toBasketForm".$goods['id']."' method='post' style='z-index: 999;'>
														<label>����������:</label>
														<br />
														<div style='padding-top: 8px;'>
															<input type='text' value='1' class='catalogueInput' name='quantity".$goods['id']."' id='quantity".$goods['id']."' onKeyUp='validateQuantity(\"quantity".$goods['id']."\")' onChange='validateQuantity(\"quantity".$goods['id']."\")' />
															<div onclick='addToBasket(\"".$goods['id']."\")'><img src='pictures/system/toBasket.png' id='toBasket".$goods['id']."' class='toBasketSubmit' onmouseover='toBasket(1, \"toBasket{$goods['id']}\")' onmouseout='toBasket(0, \"toBasket{$goods['id']}\")' title='�������� � �������' /></div>
															<div id='note".$goods['id']."' class='basic' style='position: relative; float: left; margin-top: 7px;'></div>
														</div>
													</form>
												</div>
								";
							}
							echo "
										</div>
							";
							if(!empty($goods['sketch']))
							{
								echo "
									<div class='sketch'>
										<a href='pictures/catalogue/sketch/".$goods['sketch']."' class='noBorder' rel='lightbox'><span class='sketchStyle'>������</span></a>
									</div>
								";
							}
							echo "
									</div>
								</div>
							";
						}
					}
					else
					{
						if(empty($_REQUEST['subcategory']))
						{
							$cNameResult = mysql_query("SELECT name FROM categories_new WHERE id = '".$_REQUEST['category']."'");
							$cName = mysql_fetch_array($cNameResult, MYSQL_NUM);
							echo "<span class='headerStyle'>".$cName[0]."</span><br /><br /><br />";
							
							$goodsResult = mysql_query("SELECT * FROM catalogue_new WHERE category = '".$_REQUEST['category']."' ORDER BY RAND() LIMIT ".$start.", 10");
							while($goods = mysql_fetch_array($goodsResult))
							{
								echo "
									<div class='goodBlock'>
										<div class='picture'>
											<a href='pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='pictures/catalogue/small/".$goods['small']."' class='noBorder' /></a>
										</div>
										<div class='goodContent'>
											<div class='goodTopLine'>
												<div class='redStripe'></div>
												<div class='goodName'>
													<span class='goodStyle'>".$goods['name']."</span>
												</div>
											</div>
											<div class='goodDescription'>
												<div class='goodDescriptionLeft'>
													<span class='basic'>".$goods['description']."</span>
													<br /><br />
													<div>
														<div class='goodCode'><span class='basic'><b>�������:</b> ".$goods['code']."</span></div>
														<div class='goodPrice'"; if($_SESSION['userID'] == 1) {echo " id='gp".$goods['id']."' onclick='showForm(\"gp".$goods['id']."\", \"".$goods['price']."\", \"".$goods['id']."\", \"".$rate[0]."\")' style='cursor: pointer;'";} echo "><span class='basic'><b>����:</b> ".ceil($goods['price']*$rate[0])." ���. ���.</span></div>
													</div>
												</div>
								";
								if(isset($_SESSION['userID']) and $_SESSION['userID'] != 1)
								{
									echo "
												<div class='goodDescriptionRight'>
													<form class='toBasketForm".$goods['id']."' method='post' style='z-index: 999;'>
														<label>����������:</label>
														<br />
														<div style='padding-top: 8px;'>
															<input type='text' value='1' class='catalogueInput' name='quantity".$goods['id']."' id='quantity".$goods['id']."' onKeyUp='validateQuantity(\"quantity".$goods['id']."\")' onChange='validateQuantity(\"quantity".$goods['id']."\")' />
															<div onclick='addToBasket(\"".$goods['id']."\")'><img src='pictures/system/toBasket.png' id='toBasket".$goods['id']."' class='toBasketSubmit' onmouseover='toBasket(1, \"toBasket{$goods['id']}\")' onmouseout='toBasket(0, \"toBasket{$goods['id']}\")' title='�������� � �������' /></div>
															<div id='note".$goods['id']."' class='basic' style='position: relative; float: left; margin-top: 7px;'></div>
														</div>
													</form>
												</div>
									";
								}
								echo "
											</div>
								";
								if(!empty($goods['sketch']))
								{
									echo "
										<div class='sketch'>
											<a href='pictures/catalogue/sketch/".$goods['sketch']."' class='noBorder' rel='lightbox'><span class='sketchStyle'>������</span></a>
										</div>
									";
								}
								echo "
										</div>
									</div>
								";
							}
						}
						else
						{
							$s2CountResult = mysql_query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$_REQUEST['subcategory']."'");
							$s2Count = mysql_fetch_array($s2CountResult, MYSQL_NUM);
							
							if($s2Count[0] != 0)
							{
								if(empty($_REQUEST['subcategory2']))
								{
									$sNameResult = mysql_query("SELECT name FROM subcategories_new WHERE id = '".$_REQUEST['subcategory']."'");
									$sName = mysql_fetch_array($sNameResult, MYSQL_NUM);
									echo "<span class='headerStyle'>".$sName[0]."</span><br /><br /><br />";
									
									$count = 0;
									$goodsResult = mysql_query("SELECT * FROM catalogue_new WHERE subcategory = '".$_REQUEST['subcategory']."' ORDER BY RAND() LIMIT ".$start.", 10");
									while($goods = mysql_fetch_array($goodsResult))
									{
										$count++;
										
										echo "
											<div class='goodBlock'>
												<div class='picture'>
													<a href='pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='pictures/catalogue/small/".$goods['small']."' class='noBorder' /></a>
												</div>
												<div class='goodContent'>
													<div class='goodTopLine'>
														<div class='redStripe'></div>
														<div class='goodName'>
															<span class='goodStyle'>".$goods['name']."</span>
														</div>
													</div>
													<div class='goodDescription'>
														<div class='goodDescriptionLeft'>
															<span class='basic'>".$goods['description']."</span>
															<br /><br />
															<div>
																<div class='goodCode'><span class='basic'><b>�������:</b> ".$goods['code']."</span></div>
																<div class='goodPrice'"; if($_SESSION['userID'] == 1) {echo " id='gp".$goods['id']."' onclick='showForm(\"gp".$goods['id']."\", \"".$goods['price']."\", \"".$goods['id']."\", \"".$rate[0]."\")' style='cursor: pointer;'";} echo "><span class='basic'><b>����:</b> ".ceil($goods['price']*$rate[0])." ���. ���.</span></div>
															</div>
														</div>
										";
										if(isset($_SESSION['userID']) and $_SESSION['userID'] != 1)
										{
											echo "
														<div class='goodDescriptionRight'>
															<form class='toBasketForm".$goods['id']."' method='post' style='z-index: 999;'>
																<label>����������:</label>
																<br />
																<div style='padding-top: 8px;'>
																	<input type='text' value='1' class='catalogueInput' name='quantity".$goods['id']."' id='quantity".$goods['id']."' onKeyUp='validateQuantity(\"quantity".$goods['id']."\")' onChange='validateQuantity(\"quantity".$goods['id']."\")' />
																	<div onclick='addToBasket(\"".$goods['id']."\")'><img src='pictures/system/toBasket.png' id='toBasket".$goods['id']."' class='toBasketSubmit' onmouseover='toBasket(1, \"toBasket{$goods['id']}\")' onmouseout='toBasket(0, \"toBasket{$good['id']}\")' title='�������� � �������' /></div>
																	<div id='note".$goods['id']."' class='basic' style='position: relative; float: left; margin-top: 7px;'></div>
																</div>
															</form>
														</div>
											";
										}
										echo "
													</div>
										";
										if(!empty($goods['sketch']))
										{
											echo "
												<div class='sketch'>
													<a href='pictures/catalogue/sketch/".$goods['sketch']."' class='noBorder' rel='lightbox'><span class='sketchStyle'>������</span></a>
												</div>
											";
										}
										echo "
												</div>
											</div>
										";
									}
									
									if($count == 0)
									{
										echo "<span class='basic'>������ ������ ���� ����. �������� ���� ���������.</span><br /><br />";
									}
								}
								else
								{
									$s2NameResult = mysql_query("SELECT name FROM subcategories2 WHERE id = '".$_REQUEST['subcategory2']."'");
									$s2Name = mysql_fetch_array($s2NameResult, MYSQL_NUM);
									echo "<span class='headerStyle'>".$s2Name[0]."</span><br /><br /><br />";
									
									$count = 0;
									$goodsResult = mysql_query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['subcategory2']."' ORDER BY priority LIMIT ".$start.", 10");
									while($goods = mysql_fetch_array($goodsResult))
									{
										$count++;
										
										echo "
											<div class='goodBlock'>
												<div class='picture'>
													<a href='pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='pictures/catalogue/small/".$goods['small']."' class='noBorder' /></a>
												</div>
												<div class='goodContent'>
													<div class='goodTopLine'>
														<div class='redStripe'></div>
														<div class='goodName'>
															<span class='goodStyle'>".$goods['name']."</span>
														</div>
													</div>
													<div class='goodDescription'>
														<div class='goodDescriptionLeft'>
															<span class='basic'>".$goods['description']."</span>
															<br /><br />
															<div>
																<div class='goodCode'><span class='basic'><b>�������:</b> ".$goods['code']."</span></div>
																<div class='goodPrice'"; if($_SESSION['userID'] == 1) {echo " id='gp".$goods['id']."' onclick='showForm(\"gp".$goods['id']."\", \"".$goods['price']."\", \"".$goods['id']."\", \"".$rate[0]."\")' style='cursor: pointer;'";} echo "><span class='basic'><b>����:</b> ".ceil($goods['price']*$rate[0])." ���. ���.</span></div>
															</div>
														</div>
											";
											if(isset($_SESSION['userID']) and $_SESSION['userID'] != 1)
											{
												echo "
														<div class='goodDescriptionRight'>
															<form class='toBasketForm".$goods['id']."' method='post' style='z-index: 999;'>
																<label>����������:</label>
																<br />
																<div style='padding-top: 8px;'>
																	<input type='text' value='1' class='catalogueInput' name='quantity".$goods['id']."' id='quantity".$goods['id']."' onKeyUp='validateQuantity(\"quantity".$goods['id']."\")' onChange='validateQuantity(\"quantity".$goods['id']."\")' />
																	<div onclick='addToBasket(\"".$goods['id']."\")'><img src='pictures/system/toBasket.png' id='toBasket".$goods['id']."' class='toBasketSubmit' onmouseover='toBasket(1, \"toBasket{$goods['id']}\")' onmouseout='toBasket(0, \"toBasket{$goods['id']}\")' title='�������� � �������' /></div>
																	<div id='note".$goods['id']."' class='basic' style='position: relative; float: left; margin-top: 7px;'></div>
																</div>
															</form>
														</div>
												";
											}
										echo "
													</div>
										";
										if(!empty($goods['sketch']))
										{
											echo "
												<div class='sketch'>
													<a href='pictures/catalogue/sketch/".$goods['sketch']."' class='noBorder' rel='lightbox'><span class='sketchStyle'>������</span></a>
												</div>
											";
										}
										echo "
												</div>
											</div>
										";
									}
									
									if($count == 0)
									{
										echo "<span class='basic'>������ ������ ���� ����. �������� ���� ���������.</span><br /><br />";
									}
								}
							}
							else
							{
								$sNameResult = mysql_query("SELECT name FROM subcategories_new WHERE id = '".$_REQUEST['subcategory']."'");
								$sName = mysql_fetch_array($sNameResult, MYSQL_NUM);
								echo "<span class='headerStyle'>".$sName[0]."</span><br /><br /><br />";
									
								$count = 0;
								$goodsResult = mysql_query("SELECT * FROM catalogue_new WHERE subcategory = '".$_REQUEST['subcategory']."' ORDER BY priority LIMIT ".$start.", 10");
								while($goods = mysql_fetch_array($goodsResult))
								{
									$count++;
										
									echo "
										<div class='goodBlock'>
											<div class='picture'>
												<a href='pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='pictures/catalogue/small/".$goods['small']."' class='noBorder' /></a>
											</div>
											<div class='goodContent'>
												<div class='goodTopLine'>
													<div class='redStripe'></div>
													<div class='goodName'>
														<span class='goodStyle'>".$goods['name']."</span>
													</div>
												</div>
												<div class='goodDescription'>
													<div class='goodDescriptionLeft'>
														<span class='basic'>".$goods['description']."</span>
														<br /><br />
														<div>
															<div class='goodCode'><span class='basic'><b>�������:</b> ".$goods['code']."</span></div>
															<div class='goodPrice'"; if($_SESSION['userID'] == 1) {echo " id='gp".$goods['id']."' onclick='showForm(\"gp".$goods['id']."\", \"".$goods['price']."\", \"".$goods['id']."\", \"".$rate[0]."\")' style='cursor: pointer;'";} echo "><span class='basic'><b>����:</b> ".ceil($goods['price']*$rate[0])." ���. ���.</span></div>
														</div>
													</div>
									";
									if(isset($_SESSION['userID']) and $_SESSION['userID'] != 1)
									{
										echo "
													<div class='goodDescriptionRight'>
														<form class='toBasketForm".$goods['id']."' method='post' style='z-index: 999;'>
															<label>����������:</label>
															<br />
															<div style='padding-top: 8px;'>
																<input type='text' value='1' class='catalogueInput' name='quantity".$goods['id']."' id='quantity".$goods['id']."' onKeyUp='validateQuantity(\"quantity".$goods['id']."\")' onChange='validateQuantity(\"quantity".$goods['id']."\")' />
																<div onclick='addToBasket(\"".$goods['id']."\")'><img src='pictures/system/toBasket.png' id='toBasket".$goods['id']."' class='toBasketSubmit' onmouseover='toBasket(1, \"toBasket{$goods['id']}\")' onmouseout='toBasket(0, \"toBasket{$goods['id']}\")' title='�������� � �������' /></div>
																<div id='note".$goods['id']."' class='basic' style='position: relative; float: left; margin-top: 7px;'></div>
															</div>
														</form>
													</div>
										";
									}
									echo "
												</div>
									";
									if(!empty($goods['sketch']))
									{
										echo "
											<div class='sketch'>
												<a href='pictures/catalogue/sketch/".$goods['sketch']."' class='noBorder' rel='lightbox'><span class='sketchStyle'>������</span></a>
											</div>
											";
									}
									echo "
											</div>
										</div>
									";
								}
									
								if($count == 0)
								{
									echo "<span class='basic'>������ ������ ���� ����. �������� ���� ���������.</span><br /><br />";
								}
							}
						}
					}
				}

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
                        	echo "<a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>����������</span></div></a>";
                        }

                        for($i = 1; $i <= $numbers; $i++)
                        {
                            if($_REQUEST['p'] != $i)
                            {
                                echo "<a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".$i."' class='noBorder'>";
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
                            echo "<a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>���������</span></div></a>";
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
                                echo "<a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>����������</span></div></a>";
                            }
                                                    
                            for($i = 1; $i <= 5; $i++)
                            {
                                if($_REQUEST['p'] != $i)
                                {
                                    echo "<a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".$i."' class='noBorder'>";
                                }

                                echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='goodStyleWhite'";} else {echo "class='goodStyleRed' id='pbt".$i."'";} echo ">".$i."</span></div>";

                                if($_REQUEST['p'] != $i)
                                {
                                    echo "</a>";
                                }
                            }

                            echo "<div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>";
                            echo "<a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='goodStyleRed' id='pbt".$numbers."'>".$numbers."</span></div></a>";

                            if($_REQUEST['p'] == $numbers)
                            {
                                echo "<div class='admPageNumberBlockSide' id='pbNext' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>���������</span></div>";
                            }
                            else
                            {
                            	echo "<a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>���������</span></div></a>";
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
                                        <a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>����������</span></div></a>
                                        <a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='goodStyleRed' id='pbt1'>1</span></div></a>
                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
                                        <a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] - 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")'><span class='goodStyleRed' id='pbt".($_REQUEST['p'] - 1)."'>".($_REQUEST['p'] - 1)."</span></div></a>
                                        <div class='admPageNumberBlockActive'><span class='goodStyleWhite'>".$_REQUEST['p']."</span></div>
                                        <a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] + 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")'><span class='goodStyleRed' id='pbt".($_REQUEST['p'] + 1)."'>".($_REQUEST['p'] + 1)."</span></div></a>
                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
                                        <a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='goodStyleRed' id='pbt".$numbers."'>".$numbers."</span></div></a>
                                        <a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>���������</span></div></a>
                                    </div>
                                ";
                            }
                            else
                            {
                                echo "
                                    <br /><br />
                                    <div id='pageNumbers'>
                                        <a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>����������</span></div></a>
                                        <a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='goodStyleRed' id='pbt1'>1</span></div></a>
                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
                                ";

                                for($i = ($numbers - 4); $i <= $numbers; $i++)
                                {
                                    if($_REQUEST['p'] != $i)
                                    {
                                        echo "<a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".$i."' class='noBorder'>";
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
                                    echo "<a href='catalogue.php?"; if(!empty($_REQUEST['type'])) {echo "&type=".$_REQUEST['type']."&";} if(!empty($_REQUEST['category'])) {echo "category=".$_REQUEST['category']."&";} if(!empty($_REQUEST['subcategory'])) {echo "subcategory=".$_REQUEST['subcategory']."&";} if(!empty($_REQUEST['subcategory2'])) {echo "subcategory2=".$_REQUEST['subcategory2']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>���������</span></div></a>";
                                }   
                            }
                        }
                    }
                }

                echo "</div>";

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
                	<a href='index.php' class='noBorder'><span class='headerStyleRed'>�����-��</span></a><span class='headerStyle'> &copy; 2008 - <?php echo date('Y'); ?></span>
            </div>
            <div id='web'>
				<span class='headerStyle'>�������� �����</span>
                <br />
                <a href='http://airlab.by/' class='noBorder'><span class='headerStyleRed'>������ AIR LAB</span></a>
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
		$(document).ready(function() {

			var nextRight = $('#pbNext').offset().left + $('#pbNext').width();
			var pnWidth = parseInt(nextRight - $('#pbPrev').offset().left);
			var cgWidth = $('#catalogueGoods').width();
			var pnShift = parseInt((cgWidth - pnWidth) / 2);

			$('#pageNumbers').css('margin-left', pnShift + 'px');

		});
	</script>

</body>

</html>