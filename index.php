<?php

	session_start();
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
    <meta name='keywords' content='мебельная фурнитура, комплектующие для мебели, Аргос-ФМ, комплектующие для мебели Могилев, Могилев, кромочные материалы, кромка, кромка ПВХ, ручки мебельные, мебельная фурнитура Могилев, кромка ПВХ Могилев, лента кромочная, лента кромочная Могилев, ручки мебельные Могилев, кромка Могилев'>
    <meta name='description' content='Комплексные поставки всех видов мебельной фурнитуры импортного и отечественного производства. Республика Беларусь, г. Могилёв.'>
    
    <title>Аргос-ФМ | Главная</title>
    
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

	<div id='layout' <?php if((isset($_SESSION['login']) and $_SESSION['login'] != 1) or isset($_SESSION['recovery']) or isset($_SESSION['registration']) or isset($_SESSION['activation']) or isset($_SESSION['activationFalse']) or isset($_SESSION['registration_cancel']) or isset($_SESSION['delete']) or isset($_SESSION['basket'])) {echo "style='display: block;'";} else {echo "style='display: none;'";} ?> onclick='resetBlocks();'></div>
    
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
							<center><span class='headerStyleRed'>Корзина пуста</span></center>
							<br /><br />
							<span class='basic'>На данный момент ваша корзина пуста. Для оформления заказа добавьте в неё тоары из </span><a href='catalogue.php' title='Перейти в каталог'><span class='basic' style='text-decoration: underline; color: #3e94fe;'>каталога</span></a><span class='basic'>.</span>
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
							<center><span class='headerStyleRed'>Аккаунт не активирован.</span></center>
							<br /><br />
							<span class='basic'>Вы не сможете заходить на страницу личных настроек и совершать онлайн-заказы до тех пор, пока не активация не будет завершена. Для этого проверьте свою электронную почту.</span>
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
							<center><span class='headerStyleRed'>Удаление аккаунта</span></center>
							<br /><br />
							<span class='basic'>Ваш аккаунт был успешно удалён. Теперь вы не сможете совершать онлайн-заказы.</span>
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
							<center><span class='headerStyleRed'>Регистрация завершена!</span></center>
							<br /><br />
							<span class='basic'>Поздравляем! Вы успешно зарегистрировались. Теперь вы можете оформлять онлайн-заказы в </span><span class='basicRed'><a href='catalogue.php' class='noBorder'>каталоге</a></span><span class='basic'>.</span>
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
            	<center><span class='headerStyleRed'>Регистрация нового пользователя</span></center>
                <br /><br />
                <label>Тип пользователя:</label>
                <br />
                <input type='radio' name='userType' value='organisation' class='radio' onclick='registrationType(1)' <?php if(isset($_SESSION['registration_type'])){if($_SESSION['registration_type'] == '1'){echo "checked";}}else{echo "checked";} ?>><span class='mainIMGText'>Организация или ИП</span><br />
                <input type='radio' name='userType' value='person' class='radio' onclick='registrationType(2)' <?php if(isset($_SESSION['registration_type']) and $_SESSION['registration_type'] == '2'){echo "checked";} ?>><span class='mainIMGText'>Физическое лицо</span><br />
                <br />
                <label>Логин:</label>
                <br />
                <input type='text' name='userLogin' id='userLoginInput' <?php if(isset($_SESSION['registration_login'])){echo "value='".$_SESSION['registration_login']."'";} ?> />
                <br /><br />
                <label>Пароль:</label>
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
							<label>Название организации:</label>
							<br />
							<input type='text' name='organisation' id='organisationInput' ";
							if(isset($_SESSION['registration_organisation'])){echo "value='".$_SESSION['registration_organisation']."'";}
							
						echo "
							/>
						";
					}
				?>
                <br /><br />
                <label>Контактное лицо:</label>
                <br />
                <input type='text' name='userName' id='userNameInput' <?php if(isset($_SESSION['registration_name'])){echo "value='".$_SESSION['registration_name']."'";} ?> />
                <br /><br />
                <label>Контактный телефон:</label>
                <br />
                <input type='text' name='userPhone' id='userPhoneInput' <?php if(isset($_SESSION['registration_phone'])){echo "value='".$_SESSION['registration_phone']."'";} ?> />
                <?php 
					if(isset($_SESSION['registration']) and $_SESSION['registration'] != 'ok')
					{
						switch($_SESSION['registration'])
						{
							case "failed":
								echo "<br /><br /><span class='basicRed'>При регистрации произошла ошибка. Попробуйте снова.</span><br />";
								break;
							case "empty":
								echo "<br /><br /><span class='basicRed'>Для регистрации необходимо заполнить все поля.</span><br />";
								break;
							case "login":
								echo "<br /><br /><span class='basicRed'>Длина логина должна составлять от 3 до 25 символов. Спецсимволы не допускаются.</span>";
								break;
							case "password":
								echo "<br /><br /><span class='basicRed'>Длина пароля должна составлять от 5 до 25 символов.</span><br />";
								break;
							case "email":
								echo "<br /><br /><span class='basicRed'>Введён недопустимый e-mail.</span><br />";
								break;
							case "login_d":
								echo "<br /><br /><span class='basicRed'>Введённый вами логин уже существует.</span><br />";
								break;
							case "email_d":
								echo "<br /><br /><span class='basicRed'>Введённый вами e-mail уже существует.</span><br />";
								break;
							case "organisation_d":
								echo "<br /><br /><span class='basicRed'>Введённое вами название организации уже существует.</span><br />";
								break;
							case "phone_d":
								echo "<br /><br /><span class='basicRed'>Введённый вами номер телефона уже существует.</span><br />";
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
                <input type='submit' value='зарегистрироваться' id='registrationSubmit' />
                <input type='button' value='отмена' id='cancelButton' onclick='resetBlocks();' />
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
				<center><span class='headerStyleRed'>Восстановление пароля</span></center>
				<br /><br />
				<label>Введи логин или e-mail, указанный при регистрации:</label>
				<br />
				<input type='text' name='recovery' id='recoveryInput' />
				<br /><br />
				<?php
                    switch($_SESSION['recovery'])
                    {
                        case "empty":
                            echo "<span class='basicRed'>Вы не ввели свой логин или e-mail.</span><br /><br />";
                            break;
                        case "login":
                            echo "<span class='basicRed'>Вы ввели несуществующий логин или e-mail.</span><br /><br />";
                            break;
                        default:
                            break;
                    }
                ?>
                <input type='submit' value='продолжить' id='recoverySubmit' />
                <input type='button' value='отмена' id='cancelButton' onclick='resetBlocks();' />
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
            	<a href='index.php' class='noBorder' title='Вернуться на главную страницу'>
                    <div id='mainPoint'>
                        <div id='mainPointCenter'>
                            <div id='mpTopActive'></div>
                            <div class='pBottom'>
                                <img src='pictures/system/mainTextRed.png' id='mpIMG' class='noBorder'>
                            </div>
                        </div>
                    </div>
                </a>
                <a href='catalogue.php' class='noBorder' title='Каталог мебельной фурнитуры и комплектующих'>
                    <div id='cataloguePoint' onmouseover='menuVisual("1", "cpIMG", "cpTop")' onmouseout='menuDefault2()'>
                        <div id='cataloguePointCenter'>
                            <div id='cpTop'></div>
                            <div class='pBottom'>
                                <img src='pictures/system/catalogueText.png' id='cpIMG' class='noBorder'>
                            </div>
                        </div>
                    </div>
                </a>
                <a href='news.php' class='noBorder' title='Новости, акции и коммерческие предложения'>
                    <div id='offersPoint' onmouseover='menuVisual("1", "opIMG", "opTop")' onmouseout='menuDefault3()'>
                        <div id='offersPointCenter'>
                            <div id='opTop'></div>
                            <div class='pBottom'>
                                <img src='pictures/system/newsText.png' id='opIMG' class='noBorder'>
                            </div>
                        </div>
                    </div>
                </a>
                <a href='contacts.php' class='noBorder' title='Как с нами связаться'>
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
							
							<div id='loginBlockOuter' "; if(isset($_SESSION['login'])){echo "style='display: block;'";}else{echo "style='display: none;'";} echo ">
								<div id='loginBlock'>
									<form name='loginForm' id='loginForm' method='post' action='scripts/login.php'>
										<center><span class='headerStyleRed'>Авторизация</span></center>
										";
										if(isset($_SESSION['login']))
										{
											switch($_SESSION['login'])
											{
												case 'error':
													echo "<br /><span class='basicRed'>Неверное имя пользователя или пароль.</span><br /><br />";
													break;
												case 'empty':
													echo "<br /><span class='basicRed'>Заполните все поля.</span><br /><br />";
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
										<input type='text' id='userLogin' name='userLogin'"; if(isset($_SESSION['userLogin'])){echo "value='".$_SESSION['userLogin']."'";} echo " />
										<br /><br />
										<label>Пароль:</label>
										<br />
										<input type='password' id='userPassword' name='userPassword'"; if(isset($_SESSION['userPassword'])){echo "value='".$_SESSION['userPassword']."'";} echo " />
										<br /><br />
										<span class='redItalicHover' style='cursor: pointer;' onclick='registrationWindow();'>Ещё не зарегистрированы?</span>
										<br />
										<span class='redItalicHover' style='cursor: pointer;' onclick='recoveryWindow();'>Забыли пароль?</span>
										<br /><br />
										<input type='submit' value='войти' id='loginSubmit' class='button' />
										<input type='button' value='отмена' id='cancelButton' onclick='resetBlocks();' />
									</form>
								</div>
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
									<a href='order.php' class='noBorder'><img src='pictures/system/basketFull.png' class='noBorder' id='basketIcon' title='Корзина | Количество товаров: ".$orders."' onmouseover='changeBasketFullIcon(1)' onmouseout='changeBasketFullIcon(0)' /></a>
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
									<a href='order.php' class='noBorder'><img src='pictures/system/basketFull.png' class='noBorder' id='basketIcon' title='Корзина | Количество заявок: ".$orders."' onmouseover='changeBasketFullIcon(1)' onmouseout='changeBasketFullIcon(0)' /></a>
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
        	<h1 class='headerStyle'>мебелльная фурнитура</h1>
            <div class='mainIMGContainer'>
            	<a href='catalogue.php?type=fa' class='noBorder'><img src='pictures/system/fa.png' class='noBorder' title='В каталог' /></a>
            </div>
            <div class='mainIMGText'>
            	<h2 class='basic'>Основное направление деятельности компании — комплексные поставки мебельной фурнитуры, материалов и комплектующих импортного и отечественного производства для мебельных фабрик и производителей мебели. Работая на рынке мебельной фурнитуры, мы постоянно расширяем ассортимент предлагаемой продукции.</h2>
            </div>
            <a href='catalogue.php?type=fa' class='noBorder'>
                <div class='toCatalogueButton' style='margin-top: 17px;'>
                    <span class='toCatBText'>в каталог</span>
                </div>
            </a>
        </div>
        <div id='em_main_block'>
        	<h1 class='headerStyle'>кромочные материалы</h1>
            <div class='mainIMGContainer'>
            	<a href='catalogue.php?type=em' class='noBorder'><img src='pictures/system/em.png' class='noBorder' title='В каталог' /></a>
            </div>
            <div class='mainIMGText'>
            	<h2 class='basic'>Одним из ведущих направлений нашей деятельности является поставка кромочных материалов толщиной от 0.4мм до 2мм и шириной от 19мм до 42мм из высококачественного ПВХ. Складская программа насчитывает более 40 древоподобных и 20 однотонных декоров.</h2>
            </div>
            <a href='catalogue.php?type=em' class='noBorder'>
                <div class='toCatalogueButton' style='margin-top: 33px;'>
                    <span class='toCatBText'>в каталог</span>
                </div>
            </a>
        </div>
        <div id='ca_main_block'>
        	<h1 class='headerStyle'>аксессуары для штор</h1>
            <div class='mainIMGContainer'>
            	<a href='catalogue.php?type=ca' class='noBorder'><img src='pictures/system/ca.png' class='noBorder' title='В каталог' /></a>
            </div>
            <div class='mainIMGText'>
            	<h2 class='basic'>Предлагаем бахрому для штор, скатертей, подушек, обделочную тесьму для салфеток, мебели, окантовки краёв, декоративные шнуры, люверсы, кисти и подхваты для штор.</h2>
            </div>
            <a href='catalogue.php?type=ca' class='noBorder'>
                <div class='toCatalogueButton' style='margin-top: 60px;'>
                    <span class='toCatBText'>в каталог</span>
                </div>
            </a>
        </div>
        
        <div id='newsMain'>
        	<center>
            	<span class='bigHeaderStyle'>Последние новости</span>
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
						echo "<a href='news.php?id=".$news['id']."' clas='noBorder'><div class='newsEntry' title='Прочитать новость'>";
					}
					else
					{
						echo "<a href='news.php?id=".$news['id']."' clas='noBorder'><div class='newsEntry' style='margin-top: 10px;' title='Прочитать новость'>";
					}
					echo "
							<span class='newsHeadingFont'>".$news['header']."</span>
							<br />
							<span class='basic'>".$news['short']."</span>
							<br /><br />
							<span class='smallBasicRed'>Опубликовано ".$date." в ".$time."</span>
					
						</div></a>
					";
				}

					
			?>
            <div id='indexNewsButtons' style='float: right; margin-right: 20px; margin-top: 20px;'>
            	<span class='basic'>Показаны 3 последние новости</span>
            	&nbsp;&nbsp;&nbsp;
            	<?php
	            	if($_SESSION['userID'] == 1)
	        		{
	        			echo "
	        				<a href='admin/admin.php?section=users&action=addNews'>
	        					<span class='catalogueItemTextDecorated'>Написать новость</span>
	        				</a>
	        				&nbsp;&nbsp;&nbsp;
	        			";
	        		}
	            ?>
            	<a href='news.php' class='noBorder'><span class='catalogueItemTextDecorated'>Все новости</span></a>
            </div>

        </div>
        
        <div id='partners'>
        	<center>
            	<span class='bigHeaderStyle'>Наши партнеры</span>
                <br /><br /><br />
                <div style='position: relative; margin: 0 auto;'>
                	<a href='http://ozkardeslermetal.com.tr/en/' class='noBorder'><img src='pictures/system/partner_ozkm.png' class='noBorder' title='OZKARDESLER METAL, Турция' /></a>
                    <div class='tableSpace'></div>
                    <a href='http://www.boyard.biz/' class='noBorder' style='margin-left: 30px;'><img src='pictures/system/partner_boyard.png' class='noBorder' title='BOYARD, Россия' /></a>
                    <div class='tableSpace'></div>
                    <a href='http://aldi04.ru/' class='noBorder'><img src='pictures/system/partner_aldi.png' class='noBorder' title='АЛДИ, Россия' /></a>
                </div>
            </center>
        </div>

    </div>
    
    <footer>
		<div id='footerContent'>
        	<div id='location'>
            	<span class='headerStyle'>Республика Беларусь</span>
               	<br />
                <span class='headerStyle'>г. Могилев</span>
            </div>
            <div id='copyright'>
                <a href='index.php' class='noBorder'><span class='headerStyleRedHover'>Аргос-ФМ</span></a><span class='headerStyle'> &copy; 2008 - <?php echo date('Y'); ?></span>
            </div>
            <div id='web'>
				<span class='headerStyle'>создание сайта</span>
                <br />
                <a href='http://airlab.by/' class='noBorder'><span class='headerStyleRedHover'>студия AIR LAB</span></a>
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