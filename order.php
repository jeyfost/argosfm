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

		$_SESSION['s'] = $_REQUEST['s'];
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

	if(!empty($_REQUEST['customer']) and $_REQUEST['customer'] != 'all') {
		$cResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE id = '".$_REQUEST['customer']."'");
		$c = $cResult->fetch_array(MYSQLI_NUM);

		if($c[0] == 0) {
			$cResult = $mysqli->query("SELECT COUNT(id) FROM users_deleted WHERE id = '".$_REQUEST['customer']."'");
			$c = $cResult->fetch_array(MYSQLI_NUM);
			if($c[0] == 0) {
				header("Location: order.php?s=2&customer=all&p=1");
			}
		}
	}

	if($_SESSION['userID'] == 1 and $_REQUEST['s'] == '2' and empty($_REQUEST['customer']))
	{
		header("Location: order.php?s=2&customer=all&p=1");
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
				if($_REQUEST['customer'] == 'all') {
					$ordersResult = $mysqli->query("SELECT * FROM orders_date WHERE status = '1'");
				} else {
					$ordersResult = $mysqli->query("SELECT * FROM orders_date WHERE status = '1' AND user_id = '".$_REQUEST['customer']."'");
				}

				$quantity = $ordersResult->num_rows;
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
				$ordersResult = $mysqli->query("SELECT * FROM orders_date WHERE status = '1' AND user_id = '".$_SESSION['userID']."'");
				$quantity = $ordersResult->num_rows;
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
    <meta name='keywords' content='мебельная фурнитура, комплектующие для мебели, Аргос-ФМ, комплектующие для мебели Могилев, Могилев, кромочные материалы, кромка, кромка ПВХ, ручки мебельные, мебельная фурнитура Могилев, кромка ПВХ Могилев, лента кромочная, лента кромочная Могилев, ручки мебельные Могилев, кромка Могилев'>
    
    <title>Аргос-ФМ | <?php if($_SESSION['userID'] == 1) {echo "Заявки";} else {echo "Корзина";} ?></title>
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

<div id='layout' <?php if((isset($_SESSION['login']) and $_SESSION['login'] != 1) or isset($_SESSION['recovery']) or isset($_SESSION['registration']) or isset($_SESSION['activation']) or isset($_SESSION['activationFalse']) or isset($_SESSION['registration_cancel']) or isset($_SESSION['delete']) or isset($_SESSION['basket'])) {echo "style='display: block;'";} else {echo "style='display: none;'";} ?> onclick='resetBlocks();' onmousemove='resizeLayout()' onmousewheel='resizeLayout()'></div>

<?php
if(!empty($_SESSION['recovery']) and $_SESSION['recovery'] == 'sent')
{
	echo "
				<div id='notificationWindowOuter' style='display: block;'>
					<div id='notificationWindow'>
						<form id='recoveryNotificationForm'>
							<center><span class='headerStyleRed'>Восстановление пароля</span></center>
							<br /><br />
							<span class='basic'>Ваш пароль был изменён. Чтобы узнать новый пароль, прочтите письмо по адресу, указанному при регистрации: <b>".$_SESSION['recovery_email']."</b></span>
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
							<center><span class='headerStyleRed'>Корзина пуста</span></center>
							<br /><br />
							<span class='basic'>На данный момент ваша корзина пуста. Для оформления заказа добавьте в неё тоары из </span><a href='catalogue.php' title='Перейти в каталог'><span class='basic' style='text-decoration: underline; color: #3e94fe;'>каталога</span></a><span class='basic'>.</span>
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
							<center><span class='headerStyleRed'>Аккаунт не активирован.</span></center>
							<br /><br />
							<span class='basic'>Вы не сможете заходить на страницу личных настроек и совершать онлайн-заказы до тех пор, пока не активация не будет завершена. Для этого проверьте свою электронную почту.</span>
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
							<center><span class='headerStyleRed'>Удаление аккаунта</span></center>
							<br /><br />
							<span class='basic'>Ваш аккаунт был успешно удалён. Теперь вы не сможете совершать онлайн-заказы.</span>
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
							<center><span class='headerStyleRed'>Завершение регистрации</span></center>
							<br /><br />
			";

	switch($_SESSION['activation'])
	{
		case "ok":
			echo "<span class='basic'>Регистрация завершена успешно. Ваш аккаунт активирован.</span>";
			break;
		case "failed":
			echo "<span class='basicRed'>При активации аккаунта произошла ошибка. Попробуйте снова.</span>";
			break;
		case "hash":
			echo "<span class='basicRed'>Вы перешли по несуществующей ссылке при активации аккаунта. Перейдите по ссылке, высланной на вашу электронную почту.</span>";
			break;
		case "no_activation":
			echo "<span class='basicRed'>Ваша учётная запись ещё не активирована. Вы не сможете заходить в настройки до тех пор, пока не активация не будет завершена. Для этого проверьте свою электронную почту.</span>";
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
							<center><span class='headerStyleRed'>Отмена регистрации</span></center>
							<br /><br />
			";

	switch($_SESSION['registration_cancel'])
	{
		case "ok":
			echo "<span class='basic'>Регистрация отменена успешно. Аккаунт с адресом вашей электронной почты удалён.</span>";
			break;
		case "failed":
			echo "<span class='basicRed'>При аннулировании регистрации произошла ошибка. Попробуйте снова.</span>";
			break;
		case "hash":
			echo "<span class='basicRed'>Вы перешли по несуществующей ссылке при аннулировании аккаунта. Перейдите по ссылке, высланной на вашу электронную почту.</span>";
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
							<center><span class='headerStyleRed'>Регистрация завершена!</span></center>
							<br /><br />
							<span class='basic'>Поздравляем! Вы успешно зарегистрировались. Теперь вы можете оформлять онлайн-заказы в </span><span class='basicRed'><a href='catalogue.php' class='noBorder'>каталоге</a></span><span class='basic'>.</span>
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
		<center><span class='headerStyleRed'>Регистрация нового пользователя</span></center>
		<br /><br />
		<?php
		if(isset($_SESSION['registration']) and $_SESSION['registration'] != 'ok')
		{
			switch($_SESSION['registration'])
			{
				case "failed":
					echo "<div class='notification'><span class='basicRed'>При регистрации произошла ошибка. Попробуйте снова.</span></div><br />";
					break;
				case "empty":
					echo "<div class='notification'><span class='basicRed'>Для регистрации необходимо заполнить все поля.</span></div><br />";
					break;
				case "login":
					echo "<div class='notification'><span class='basicRed'>Длина логина должна составлять от 3 до 25 символов. Спецсимволы не допускаются.</span></div><br />";
					break;
				case "password":
					echo "<div class='notification'><span class='basicRed'>Длина пароля должна составлять от 5 до 25 символов.</span></div><br />";
					break;
				case "email":
					echo "<div class='notification'><span class='basicRed'>Введён недопустимый e-mail.</span></div><br />";
					break;
				case "login_d":
					echo "<div class='notification'><span class='basicRed'>Введённый вами логин уже существует.</span></div><br />";
					break;
				case "email_d":
					echo "<div class='notification'><span class='basicRed'>Введённый вами e-mail уже существует.</span></div><br />";
					break;
				case "organisation_d":
					echo "<div class='notification'><span class='basicRed'>Введённое вами название организации уже существует.</span></div><br />";
					break;
				case "phone_d":
					echo "<div class='notification'><span class='basicRed'>Введённый вами номер телефона уже существует.</span></div><br />";
					break;
				default:
					break;
			}

			echo "<br />";
		}
		?>
		<label>Тип пользователя:</label>
		<br />
		<input type='radio' name='userType' value='organisation' class='radio' onclick='registrationType(1)' <?php if(isset($_SESSION['registration_type'])){if($_SESSION['registration_type'] == '1'){echo "checked";}}else{echo "checked";} ?>><span class='mainIMGText'>Организация или ИП</span><br />
		<input type='radio' name='userType' value='person' class='radio' onclick='registrationType(2)' <?php if(isset($_SESSION['registration_type']) and $_SESSION['registration_type'] == '2'){echo "checked";} ?>><span class='mainIMGText'>Физическое лицо</span><br />
		<br />
		<label>Логин:</label>
		<br />
		<input type='text' class='admInput' name='userLogin' id='userLoginInput' <?php if(isset($_SESSION['registration_login'])){echo "value='".$_SESSION['registration_login']."'";} ?> />
		<br /><br />
		<label>Пароль:</label>
		<br />
		<input type='password' class='admInput' name='userPassword' id='userPasswordInput' <?php if(isset($_SESSION['registration_password'])){echo "value='".$_SESSION['registration_password']."'";} ?> />
		<br /><br />
		<label>E-mail:</label>
		<br />
		<input type='text' class='admInput' name='userEmail' id='userEmailInput' <?php if(isset($_SESSION['registration_email'])){echo "value='".$_SESSION['registration_email']."'";} ?> />
		<?php
		if((isset($_SESSION['registration_type']) and $_SESSION['registration_type'] == 1) or !isset($_SESSION['registration_type']))
		{
			echo "
							<br /><br />
							<label>Название организации:</label>
							<br />
							<input type='text' class='admInput' name='organisation' id='organisationInput' ";
			if(isset($_SESSION['registration_organisation'])){echo "value='".$_SESSION['registration_organisation']."'";}

			echo "
							/>
						";
		}
		?>
		<br /><br />
		<label>Контактное лицо:</label>
		<br />
		<input type='text' class='admInput' name='userName' id='userNameInput' <?php if(isset($_SESSION['registration_name'])){echo "value='".$_SESSION['registration_name']."'";} ?> />
		<br /><br />
		<label>Контактный телефон:</label>
		<br />
		<input type='text' class='admInput' name='userPhone' id='userPhoneInput' <?php if(isset($_SESSION['registration_phone'])){echo "value='".$_SESSION['registration_phone']."'";} ?> />
		<br /><br />
		<input type='submit' class='windowSubmit' value='Зарегистрироваться' id='registrationSubmit' />
		<input type='button' class='windowSubmit' value='Отмена' id='loginCancel' onclick='resetBlocks();' />
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
		<center><span class='headerStyleRed'>Восстановление пароля</span></center>
		<br /><br />
		<?php
		switch($_SESSION['recovery'])
		{
			case "empty":
				echo "<div class='notification'><span class='basicRed'>Вы не ввели свой логин или e-mail.</span></div><br />";
				break;
			case "login":
				echo "<div class='notification'><span class='basicRed'>Вы ввели несуществующий логин или e-mail.</span></div><br />";
				break;
			default:
				break;
		}
		?>
		<label>Введите логин или e-mail, указанный при регистрации:</label>
		<br />
		<input type='text' class='admInput' name='recovery' id='recoveryInput' <?php if(isset($_SESSION['recovery_email'])) {echo " value=".$_SESSION['recovery_email'];} ?> />
		<br /><br />
		<input type='submit' class='windowSubmit'  value='Продолжить' id='loginSubmit' />
		<input type='button' class='windowSubmit' value='Отмена' id='loginCancel' onclick='resetBlocks();' />
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
                <a href='news.php' class='noBorder' title='Новости, акции и коммерческие предложени'>
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
								<img src='pictures/system/login.png' class='noBorder' id='loginIcon' title='Войти в личный кабинет' />
							</div>

								<div id='loginBlock' onmousemove='resizeLayout()' onmousewheel='resizeLayout()' "; if(isset($_SESSION['login'])){echo "style='display: block;'";}else{echo "style='display: none;'";} echo ">
									<form name='loginForm' id='loginForm' method='post' action='scripts/login.php'>
										<center><span class='headerStyleRed'>Авторизация</span></center>
										";
					if(isset($_SESSION['login']))
					{
						switch($_SESSION['login'])
						{
							case 'error':
								echo "<div class='notification'><span class='basicRed'>Неверное имя пользователя или пароль.</span></div><br />";
								break;
							case 'empty':
								echo "<div class='notification'><span class='basicRed'>Заполните все поля.</span></div><br />";
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
										<label>Логин:</label>
										<br />
										<input type='text' class='windowInput' id='userLogin' name='userLogin'"; if(isset($_SESSION['userLogin'])){echo "value='".$_SESSION['userLogin']."'";} echo " />
										<br /><br />
										<label>Пароль:</label>
										<br />
										<input type='password' class='windowInput' id='userPassword' name='userPassword'"; if(isset($_SESSION['userPassword'])){echo "value='".$_SESSION['userPassword']."'";} echo " />
										<br /><br />
										<span class='redItalicHover' style='cursor: pointer;' onclick='registrationWindow();'>Ещё не зарегистрированы?</span>
										<br />
										<span class='redItalicHover' style='cursor: pointer;' onclick='recoveryWindow();'>Забыли пароль?</span>
										<br /><br />
										<input type='submit' class='windowSubmit' value='Войти' id='loginSubmit' class='button' />
										<input type='button' class='windowSubmit' value='Отмена' id='loginCancel' onclick='resetBlocks();' />
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
								<a href='settings.php?s=1' class='noBorder'><img src='pictures/system/user.png' class='noBorder' id='userIcon' title='".$user['login']." | Персональная страница' onmouseover='changeUserIcon(1)' onmouseout='changeUserIcon(0)' /></a>
							";

					if($_SESSION['userID'] != 1)
					{
						$ordersResult = $mysqli->query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND status = '0'");
						if($ordersResult->num_rows < 1)
						{
							echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basket.png' class='noBorder' id='basketIcon' title='Корзина' onmouseover='changeBasketIcon(1)' onmouseout='changeBasketIcon(0)' /></a>
								";
						}
						else
						{
							echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basketFull.png' class='noBorder' id='basketIcon' title='Корзина | Количество товаров: ".$ordersResult->num_rows."' onmouseover='changeBasketFullIcon(1)' onmouseout='changeBasketFullIcon(0)' /></a>
								";
						}
					}
					else
					{
						$ordersResult = $mysqli->query("SELECT * FROM orders_date WHERE status = '0'");
						if($ordersResult->num_rows < 1)
						{
							echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basket.png' class='noBorder' id='basketIcon' title='Заявки' onmouseover='changeBasketIcon(1)' onmouseout='changeBasketIcon(0)' /></a>
								";
						}
						else
						{
							echo "
									<a href='order.php' class='noBorder'><img src='pictures/system/basketFull.png' class='noBorder' id='basketIcon' title='Заказы | Количество заявок: ".$ordersResult->num_rows."' onmouseover='changeBasketFullIcon(1)' onmouseout='changeBasketFullIcon(0)' /></a>
								";
						}
					}

					echo "
								<a href='scripts/exit.php' class='noBorder'><img src='pictures/system/exit.png' class='noBorder' id='exitIcon' title='Выход из аккаунта' onmouseover='changeExitIcon(1)' onmouseout='changeExitIcon(0)' /></a>
							</div>
						";
				}

				unset($_SESSION['login']);
				unset($_SESSION['userLogin']);
				unset($_SESSION['userPassword']);
				?>
				<div id='searchBG'>
					<form name='searchForm' id='searchForm' method='post' action='scripts/search.php'>
						<input type='text' id='searchField' name='searchQuery' placeholder='Поиск...' onfocus='if(this.value=="Поиск...") {this.value = "";}' onblur='if(this.value == "") {this.value = "Поиск...";}' value='Поиск...' onkeyup='lookup(this.value)'>
						<input type='submit' id='searchSubmit' value='' title='Найти'>
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
						$ordersQuantityResult = $mysqli->query("SELECT COUNT(id) FROM orders_date WHERE status = '0'");
						$ordersQuantity = $ordersQuantityResult->fetch_array(MYSQLI_NUM);

						switch($_REQUEST['s'])
						{
							case "1":
								echo "
									<div class='basketPointActive'><span class='headerStyleWhite'>Активные заявки (".$ordersQuantity[0].")</span></div>
									<a href='order.php?s=2' class='noBorder'><div class='basketPoint' id='bp2' onmouseover='settingsMenuButton(\"1\", \"bp2\", \"bp2t\")' onmouseout='settingsMenuButton(\"0\", \"bp2\", \"bp2t\")'><span class='headerStyle' id='bp2t'>История заказов</span></div></a>
								";
								break;
							case "2":
								echo "
									<a href='order.php?s=1' class='noBorder'><div class='basketPoint' id='bp1' onmouseover='settingsMenuButton(\"1\", \"bp1\", \"bp1t\")' onmouseout='settingsMenuButton(\"0\", \"bp1\", \"bp1t\")'><span class='headerStyle' id='bp1t'>Активные заявки (".$ordersQuantity[0].")</span></div></a>
									<div class='basketPointActive'><span class='headerStyleWhite'>История заказов</span></div>
								";
								break;
							default:
								break;
						}
					}
					else
					{
						$ordersQuantityResult = $mysqli->query("SELECT COUNT(id) FROM orders_date WHERE user_id = '".$_SESSION['userID']."' AND status = '1'");
						$ordersQuantity = $ordersQuantityResult->fetch_array(MYSQLI_NUM);

						switch($_REQUEST['s'])
						{
							case "1":
								echo "
									<div class='basketPointActive'><span class='headerStyleWhite'>Текущий заказ</span></div>
									<a href='order.php?s=2' class='noBorder'><div class='basketPoint' id='bp2' onmouseover='settingsMenuButton(\"1\", \"bp2\", \"bp2t\")' onmouseout='settingsMenuButton(\"0\", \"bp2\", \"bp2t\")'><span class='headerStyle' id='bp2t'>История заказов (".$ordersQuantity[0].")</span></div></a>
								";
								break;
							case "2":
								echo "
									<a href='order.php?s=1' class='noBorder'><div class='basketPoint' id='bp1' onmouseover='settingsMenuButton(\"1\", \"bp1\", \"bp1t\")' onmouseout='settingsMenuButton(\"0\", \"bp1\", \"bp1t\")'><span class='headerStyle' id='bp1t'>Текущий заказ</span></div></a>
									<div class='basketPointActive'><span class='headerStyleWhite'>История заказов (".$ordersQuantity[0].")</span></div>
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
						$ordersResult = $mysqli->query("SELECT * FROM orders_date WHERE status = '0' ORDER BY date");

						$number = 0;
						
						if($ordersResult->num_rows == 0)
						{
							echo "<span class='basic'>На данный момент заявок нет.</span>";
						}
						else
						{
							while($orders = $ordersResult->fetch_array(MYSQLI_ASSOC))
							{
								$number++;	
								$customerResult = $mysqli->query("SELECT * FROM users WHERE id = '".$orders['user_id']."'");
								$customer = $customerResult->fetch_assoc();
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
											<span class='tableStyleDecorated' id='order".$orders['id']."' title='Помотреть подробно' style='float: left;' onclick='showDetails(\"".$orders['id']."\", \"order".$orders['id']."\", \"tableGood".$orders['id']."\")'>Заказ №".$orders['id']." от ".$orders['date']."</span>
											<span class='tableStyleDecorated' id='deleteOrder".$orders['id']."' title='Отменить данный заказ' style='float: right; margin-right: -30px;' onclick='cancelOrder(\"".$orders['id']."\")'>Отклонить</span>
											<span class='tableStyleDecorated' id='acceptOrder".$orders['id']."' title='Принять данный заказ' style='float: right; margin-right: 10px;' onclick='acceptOrder(\"".$orders['id']."\")'>Принять</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderStatus'>
											<span class='tableStyleDecorated' title='Заказчик: ".$info."' style='float: right; margin-right: 10px; cursor: pointer;' onclick='showCustomer(\"".$orders['user_id']."\")'>Заказчик</span>
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
						echo "<div style='height: 30px;'></div>";
						echo "<div style='padding: auto 20px;'></div>";
						echo "<span class='headerStyle'>Показать заказы:</span><br /><br />";

						$customersList = array();
						$idList = array();

						$customersResult = $mysqli->query("SELECT DISTINCT user_id FROM orders_date WHERE status = '1'");
						while($customers = $customersResult->fetch_array(MYSQLI_NUM)) {
							$customerResult = $mysqli->query("SELECT * FROM users WHERE id = '".$customers[0]."'");
							$customer = $customerResult->fetch_assoc();

							if(empty($customer)) {
								$customerResult = $mysqli->query("SELECT * FROM users_deleted WHERE id = '".$customers[0]."'");
								$customer = $customerResult->fetch_assoc();

								if(!empty($customer['organisation'])) {
									$name = "[удалён] ".$customer['organisation'];
								} else {
									$name = "[удалён] ".$customer['person'];
								}
							} else {
								if(!empty($customer['organisation'])) {
									$name = $customer['organisation'];
								} else {
									$name = $customer['person'];
								}
							}

							array_push($customersList, $name);
							array_push($idList, $customer['id']);
						}

						array_multisort($customersList, $idList);

						echo "
							<form name='chooseCustomerForm' id='chooseCustomerForm' method='post' action='scripts/chooseCustomer.php'>
								<select name='customerSelect' class='admSelect' onchange='this.form.submit()'>
								<option value='all'>- Всех покупателей -</option>
						";

						for($i = 0; $i < count($customersList); $i++) {
							echo "<option value='".$idList[$i]."'"; if($idList[$i] == $_REQUEST['customer']) {echo " selected ";} echo ">".$customersList[$i]."</option>";
						}

						echo "
								</select>
							</form>
						";

						if($_REQUEST['customer'] == 'all') {
							$ordersResult = $mysqli->query("SELECT * FROM orders_date WHERE status = '1' ORDER BY date DESC LIMIT ".$start.", 10");
						}else {
							$ordersResult = $mysqli->query("SELECT * FROM orders_date WHERE status = '1' AND user_id = '".$_REQUEST['customer']."' ORDER BY date DESC LIMIT ".$start.", 10");
						}
						
						echo "<div style='height: 30px;'></div>";
						echo "<div style='padding: auto 20px;'></div>";
						echo "<span class='headerStyle'>Обработанные заказы</span><br /><br />";
						echo "<div id='newOrders'>";
						echo "
							<div id='ordersContent'>
								<div class='tableVSpace'></div>
								<div class='ordersTable'>
									<div class='line'>
										<div class='orderNumberTop'>
										<span class='goodStyle'>№</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderNameTop'>
											<span class='goodStyle'>Название заказа</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderStatusTop'>
											<span class='goodStyle'>Статус</span>
										</div>
									</div>
							";

						$number = $_REQUEST['p'] * 10 - 10;
							
						while($orders = $ordersResult->fetch_assoc())
						{
							$number++;	
							$status = "Обработан";
								
							echo "
								<div class='tableVSpace'></div>
								<div class='line'>
									<div class='orderNumber'>
										<span class='tableStyle'>".$number."</span>
									</div>
									<div class='tableSpace'></div>
									<div class='orderName'>
										<span class='tableStyleDecorated' id='order".$orders['id']."' title='Помотреть подробно' style='float: left;' onclick='showDetails(\"".$orders['id']."\", \"order".$orders['id']."\", \"tableGood".$orders['id']."\")'>Заказ №".$orders['id']." от ".$orders['date']."</span>
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
		                        	echo "<div class='admPageNumberBlockSide' id='pbPrev' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>Предыдущая</span></div>";
		                        }
		                        else
		                        {
		                        	echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>Предыдущая</span></div></a>";
		                        }

		                        for($i = 1; $i <= $numbers; $i++)
		                        {
		                            if($_REQUEST['p'] != $i)
		                            {
		                                echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".$i."' class='noBorder'>";
		                            }

		                            echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='goodStyleWhite'";} else {echo "class='goodStyleRed' id='pbt".$i."'";} echo ">".$i."</span></div>";

		                            if($_REQUEST['p'] != $i)
		                            {
		                                echo "</a>";
		                            }
		                        }

		                        if($_REQUEST['p'] == $numbers)
		                        {
		                        	echo "<div class='admPageNumberBlockSide' id='pbNext' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>Следующая</span></div>";
		                        }
		                        else
		                        {
		                            echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>Следующая</span></div></a>";
		                        }

		                        echo "</div>";

		                    }
		                    else
		                    {
		                        if($_REQUEST['p'] < 5)
		                        {
		                            if($_REQUEST['p'] == 1)
		                            {
		                                echo "<div class='admPageNumberBlockSide' id='pbPrev' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>Предыдущая</span></div>";
		                            }
		                            else
		                            {
		                                echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>Предыдущая</span></div></a>";
		                            }
		                                                    
		                            for($i = 1; $i <= 5; $i++)
		                            {
		                                if($_REQUEST['p'] != $i)
		                                {
		                                    echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".$i."' class='noBorder'>";
		                                }

		                                echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='goodStyleWhite'";} else {echo "class='goodStyleRed' id='pbt".$i."'";} echo ">".$i."</span></div>";

		                                if($_REQUEST['p'] != $i)
		                                {
		                                    echo "</a>";
		                                }
		                            }

		                            echo "<div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>";
		                            echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='goodStyleRed' id='pbt".$numbers."'>".$numbers."</span></div></a>";

		                            if($_REQUEST['p'] == $numbers)
		                            {
		                                echo "<div class='admPageNumberBlockSide' id='pbNext' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>Следующая</span></div>";
		                            }
		                            else
		                            {
		                            	echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>Следующая</span></div></a>";
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
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>Предыдущая</span></div></a>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='goodStyleRed' id='pbt1'>1</span></div></a>
		                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] - 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")'><span class='goodStyleRed' id='pbt".($_REQUEST['p'] - 1)."'>".($_REQUEST['p'] - 1)."</span></div></a>
		                                        <div class='admPageNumberBlockActive'><span class='goodStyleWhite'>".$_REQUEST['p']."</span></div>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] + 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")'><span class='goodStyleRed' id='pbt".($_REQUEST['p'] + 1)."'>".($_REQUEST['p'] + 1)."</span></div></a>
		                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='goodStyleRed' id='pbt".$numbers."'>".$numbers."</span></div></a>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>Следующая</span></div></a>
		                                    </div>
		                                ";
		                            }
		                            else
		                            {
		                                echo "
		                                    <br /><br />
		                                    <div id='pageNumbers'>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>Предыдущая</span></div></a>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='goodStyleRed' id='pbt1'>1</span></div></a>
		                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
		                                ";

		                                for($i = ($numbers - 4); $i <= $numbers; $i++)
		                                {
		                                    if($_REQUEST['p'] != $i)
		                                    {
		                                        echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".$i."' class='noBorder'>";
		                                    }

		                                    echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='goodStyleWhite'";} else {echo "class='goodStyleRed' id='pbt".$i."'";} echo ">".$i."</span></div>";

		                                    if($_REQUEST['p'] != $i)
		                                    {
		                                        echo "</a>";
		                                    }
		                                }

		                                if($_REQUEST['p'] == $numbers)
		                                {
		                                    echo "<div class='admPageNumberBlockSide' id='pbNext' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>Следующая</span></div>";
		                                }
		                                else
		                                {
		                                    echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "customer=".$_REQUEST['customer']."&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>Следующая</span></div></a>";
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
									echo "<div class='notificationOK'><span class='basicGreen'>Товар успешно удалён из вашей корзины.</span></div>";
									break;
								case 'failed':
									echo "<div class='notification'><span class='basicGreen'>При удалении товара из корзины произошла ошибка. Попробуйте снова.</span></div>";
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
									echo "<div class='notificationOK'><span class='basicGreen'>Корзина очищена.</span></div>";
									break;
								case 'failed':
									echo "<div class='notification'><span class='basicGreen'>При очистке корзины произошла ошибка. Попробуйте снова.</span></div>";
									break;
								case 'empty':
									echo "<div class='notification'><span class='basicGreen'>Коризна не может быть очищена, так как уже пуста.</span></div>";
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
									echo "<span class='basic'>Ваш заказ передан менеджеру на обработку. Мы скоро вам перезвоним. Статус вашего заказа можно отслеживать в <a href='order.php?s=2' class='noBorder'><span class='catalogueItemTextDecorated'>истории заказов</span></a>.</span>";
									break;
								case 'failed':
								echo "<span class='basicRed'>При оформлении заказа возникла ошибка. Попробуйте снова.</span>";
									break;
								default: break;
							}
							echo "<br /><br /><br />";
							unset($_SESSION['orderComplete']);
						}
						echo "</div>";
						$totalPrice = 0;
						$rateResult = $mysqli->query("SELECT rate FROM currency WHERE code = 'usd'");
						$rate = $rateResult->fetch_array(MYSQLI_NUM);
						$goodsResult = $mysqli->query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND status = '0'");
						if($goodsResult->num_rows == 0)
						{
							echo "<span class='basic'>На данный момент ваша корзина пуста. Чтобы оформить заказ, посетите <a href='catalogue.php' class='noBorder'><span class='catalogueItemTextDecorated'>каталог</span></a> и выберите необходимые вам товары.</span>";
						}
						else
						{
							while ($goods = $goodsResult->fetch_assoc())
							{
								$count++;
								$goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$goods['good_id']."'");
								$good = $goodResult->fetch_assoc();
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
														<span class='basic'><b>Артикул: </b>".$good['code']."</span>
													</div>
													<div class='basketGoodPrice'>
														<span class='basic'><b>Цена за ед.: </b>".($good['price']*$rate[0])." бел. руб.</span>
														<br />
														<span class='basic'><b>Общая цена данной группы товаров: </b><span id='price".$good['id']."'>".($goods['quantity']*$good['price']*$rate[0])."</span> бел. руб.</span>
													</div>
												</div>
											</div>
										</div>
										<div class='basketEditBlock'>
											<form id='deleteFromBasketForm".$good['id']."' method='post' action='scripts/deleteFromBasket.php?id=".$good['id']."'>
												<img src='pictures/system/x.png' id='x".$good['id']."' class='noBorder' onmouseover='changeX(\"1\", \"x".$good['id']."\")' onmouseout='changeX(\"0\", \"x".$good['id']."\")' onclick='document.getElementById(\"deleteFromBasketForm".$good['id']."\").submit(); return false;' title='Убрать эту группу товаров из корзины' style='float: right; cursor: pointer;' />
											</form>
											<form method='post' style='padding-top: 25px;'>
												<label>Количество</label>
												<br /><br />
												<input type='text' class='catalogueInput' id='quantity".$good['id']."' name='quantity".$good['id']."' value='".$goods['quantity']."' onKeyUp='editBasketGood(\"".$good['id']."\", \"".$good['price']."\", \"".$rate[0]."\", \"".$totalPrice."\", \"".$goods['quantity']."\")' />
											</form>
											<div id='note".$good['id']."' style='padding-top: 40px;' class='basketNote'></div>
										</div>
									</div>
								";
							}
							echo "
								<form name='clearBasketForm' id='clearBasketForm' method='post' action='scripts/clearBasket.php'>
									<br /><br />
									<input type='submit' value='Очистить корзину' id='clearBasketSubmit' />
								</form>
								<form name='completeOrderForm' id='completeOrderForm' method='post' action='scripts/checkout.php'>
									<label style='font-size: 16px;'><b>Общая сумма заказа: </b><span id='totalPrice'>".$totalPrice."</span> бел. руб.</label>
									<br /><br />
									<input type='submit' id='completeOrderSubmit' value='Отправить заказ менеджеру' />
								</form>
							";
						}
					}
					
					if($_REQUEST['s'] == 2)
					{
						echo "<div style='height: 30px;'></div>";
						echo "<div style='padding: auto 20px;'></div>";
						
						echo "<span class='headerStyle'>Необработанные заказы</span><br /><br />";
						echo "<div id='newOrders'>";
						$orders1Result = $mysqli->query("SELECT * FROM orders_date WHERE user_id = '".$_SESSION['userID']."' AND status = '0' ORDER BY id");
						if($orders1Result->num_rows == 0)
						{
							echo "<span class='basic'>На данный момент у вас нет необработанных заказов. Для оформления нового заказа перейдите в <a href='catalogue.php' class='noBorder'><span class='catalogueItemTextDecorated'>каталог</span></a> и выберите необходимые вам товары.</span>";
						}
						else
						{
							echo "
								<div class='ordersTable'>
									<div class='line'>
										<div class='orderNumberTop'>
											<span class='goodStyle'>№</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderNameTop'>
											<span class='goodStyle'>Название заказа</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderStatusTop'>
											<span class='goodStyle'>Статус</span>
										</div>
									</div>
								";
								
							$number_n = 0;
								
							while($orders1 = $orders1Result->fetch_assoc())
							{
								$number_n++;	
								$status = "Не обработан";
								
								echo "
									<div class='tableVSpace'></div>
									<div class='line'>
										<div class='orderNumber'>
											<span class='tableStyle'>".$number_n."</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderName'>
											<span class='tableStyleDecorated' id='order".$orders1['id']."' title='Помотреть подробно' style='float: left;' onclick='showDetails(\"".$orders1['id']."\", \"order".$orders1['id']."\", \"tableGood".$orders1['id']."\")'>Заказ №".$orders1['id']." от ".$orders1['date']."</span>
											<span class='tableStyleDecorated' id='deleteOrder".$orders1['id']."' title='Отменить данный заказ' style='float: right; margin-right: 5px;' onclick='deleteOrder(\"".$orders1['id']."\")'>Удалить</span>
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
							
						echo "<span class='headerStyle' style='padding-top: 30px;'>Обработанные заказы</span><br /><br />";
						echo "<div id='oldOrders'>";
						
						$orders1Result = $mysqli->query("SELECT * FROM orders_date WHERE user_id = '".$_SESSION['userID']."' AND status = '1' ORDER BY date DESC LIMIT ".$start.", 10");
						if($orders1Result->num_rows == 0)
						{
							echo "<span class='basic'>К сожалению, у вас ещё нет обработанных заказов.</span>";
						}
						else
						{
							echo "
								<div class='ordersTable'>
									<div class='line'>
										<div class='orderNumberTop'>
											<span class='goodStyle'>№</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderNameTop'>
											<span class='goodStyle'>Название заказа</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderStatusTop'>
											<span class='goodStyle'>Статус</span>
										</div>
									</div>
								";
									
							$number = $_REQUEST['p'] * 10 - 10;
									
							while($orders1 = $orders1Result->fetch_assoc())
							{
								$number++;	
								$status = "Обработан";
								$proceedDate = "";
								
								switch (date('w', strtotime(substr($orders1['proceed_date'], 0, 10))))
								{
									case "1":
										$day = "в понедельник ";
										break;
									case "2":
										$day = "во вторник ";
										break;
									case "3":
										$day = "в среду ";
										break;
									case "4":
										$day = "в четверг ";
										break;
									case "5":
										$day = "в пятницу ";
										break;
									case "6":
										$day = "в субботу ";
										break;
									case "7":
										$day = "в воскресенье ";
										break;
									default:
										break;
								}

								$proceedDate .= $day;
								if(substr($orders1['proceed_date'], 8, 1) == 0)
								{
									$proceedDate .= substr($orders1['proceed_date'], 9, 1);
								}
								else
								{
									$proceedDate .= substr($orders1['proceed_date'], 8, 2);
								}

								switch(substr($orders1['proceed_date'], 5, 2))
								{
									case "01":
										$month = " января ";
										break;
									case "02":
										$month = " февраля ";
										break;
									case "03":
										$month = " марта ";
										break;
									case "04":
										$month = " апреля ";
										break;
									case "05":
										$month = " мая ";
										break;
									case "06":
										$month = " июня ";
										break;
									case "07":
										$month = " июля ";
										break;
									case "08":
										$month = " августа ";
										break;
									case "09":
										$month = " сентября ";
										break;
									case "10":
										$month = " октября ";
										break;
									case "11":
										$month = " ноября ";
										break;
									case "12":
										$month = " декабря ";
										break;
									default:
										break;
								}
								$proceedDate .= $month;
								$proceedDate .= substr($orders1['proceed_date'], 0, 4)." года в ";
								$proceedDate .= substr($orders1['proceed_date'], 11, 8);
										
								echo "
									<div class='tableVSpace'></div>
									<div class='line'>
										<div class='orderNumber'>
											<span class='tableStyle'>".$number."</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderName'>
											<span class='tableStyleDecorated' id='order".$orders1['id']."' title='Помотреть подробно' style='float: left;' onclick='showDetails(\"".$orders1['id']."\", \"order".$orders1['id']."\", \"tableGood".$orders1['id']."\")'>Заказ №".$orders1['id']." от ".$orders1['date']."</span>
										</div>
										<div class='tableSpace'></div>
										<div class='orderStatus'>
											<span class='tableStyle' style='cursor: help;' title='Заказ обработан ".$proceedDate."'>".$status."</span>
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
		                        	echo "<div class='admPageNumberBlockSide' id='pbPrev' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>Предыдущая</span></div>";
		                        }
		                        else
		                        {
		                        	echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>Предыдущая</span></div></a>";
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
		                        	echo "<div class='admPageNumberBlockSide' id='pbNext' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>Следующая</span></div>";
		                        }
		                        else
		                        {
		                            echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>Следующая</span></div></a>";
		                        }

		                        echo "</div>";

		                    }
		                    else
		                    {
		                        if($_REQUEST['p'] < 5)
		                        {
		                            if($_REQUEST['p'] == 1)
		                            {
		                                echo "<div class='admPageNumberBlockSide' id='pbPrev' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>Предыдущая</span></div>";
		                            }
		                            else
		                            {
		                                echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>Предыдущая</span></div></a>";
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
		                                echo "<div class='admPageNumberBlockSide' id='pbNext' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>Следующая</span></div>";
		                            }
		                            else
		                            {
		                            	echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>Следующая</span></div></a>";
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
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>Предыдущая</span></div></a>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='goodStyleRed' id='pbt1'>1</span></div></a>
		                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] - 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")'><span class='goodStyleRed' id='pbt".($_REQUEST['p'] - 1)."'>".($_REQUEST['p'] - 1)."</span></div></a>
		                                        <div class='admPageNumberBlockActive'><span class='goodStyleWhite'>".$_REQUEST['p']."</span></div>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] + 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")'><span class='goodStyleRed' id='pbt".($_REQUEST['p'] + 1)."'>".($_REQUEST['p'] + 1)."</span></div></a>
		                                        <div class='admPageNumberBlock' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='goodStyle'>...</span></div>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='goodStyleRed' id='pbt".$numbers."'>".$numbers."</span></div></a>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>Следующая</span></div></a>
		                                    </div>
		                                ";
		                            }
		                            else
		                            {
		                                echo "
		                                    <br /><br />
		                                    <div id='pageNumbers'>
		                                        <a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='goodStyleRed' id='pbtPrev'>Предыдущая</span></div></a>
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
		                                    echo "<div class='admPageNumberBlockSide' id='pbNext' style='cursor: url(\"pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>Следующая</span></div>";
		                                }
		                                else
		                                {
		                                    echo "<a href='order.php?"; if(!empty($_REQUEST['s'])) {echo "&s=".$_REQUEST['s']."&";} echo "p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='goodStyleRed' id='pbtNext'>Следующая</span></div></a>";
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
            	<span class='headerStyle'>Республика Беларусь</span>
               	<br />
                <span class='headerStyle'>г. Могилев</span>
            </div>
            <div id='copyright'>
                	<a href='index.php' class='noBorder'><span class='headerStyleRed'>Аргос-ФМ</span></a><span class='headerStyle'> &copy; 2008 - <?php echo date('Y'); ?></span>
            </div>
            <div id='web'>
				<span class='headerStyle'>создание сайта</span>
                <br />
                <a href='http://airlab.by/' class='noBorder'><span class='headerStyleRed'>студия AIR LAB</span></a>
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