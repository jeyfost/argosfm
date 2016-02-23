<?php
	
	session_start();
	
	if(empty($_SESSION['userID']))
	{
		header("Location: index.php");
	}
	
	if(empty($_REQUEST['s']))
	{
		header("Location: settings.php?s=1");
	}
	else
	{
		if($_REQUEST['s'] != '1' and $_REQUEST['s'] != '2' and $_REQUEST['s'] != '3')
		{
			header("Location: settings.php?s=1");
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
	
	$userResult = $mysqli->query("SELECT * FROM users WHERE id = '".$_SESSION['userID']."'");
	$user = $userResult->fetch_assoc();
	
	if($user['activated'] == '0')
	{
		$_SESSION['activationFalse'] = 'no';
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

<!doctype html>

<html>

<head>

    <meta charset="windows-1251">
    <meta name='keywords' content='мебельная фурнитура, комплектующие для мебели, Аргос-ФМ, комплектующие для мебели Могилев, Могилев, кромочные материалы, кромка, кромка ПВХ, ручки мебельные, мебельная фурнитура Могилев, кромка ПВХ Могилев, лента кромочная, лента кромочная Могилев, ручки мебельные Могилев, кромка Могилев'>
    
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

<body onresize='footerPos()'>


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
							<span class='basic'>Ваша учётная запись ещё не активирована. Вы не сможете заходить в настройки до тех пор, пока не активация не будет завершена. Для этого проверьте свою электронную почту.</span>
							<br /><br />
							<center><input type='button' onclick='closeNotification()' value='OK' id='cancelButton' style='float: none;' /></center>
						</form>
					</div>
				</div>
			";
			unset($_SESSION['activationFalse;']);
		}
		
		if(!empty($_SESSION['activation']))
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
							/>;
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
								<a href='settings.php?s=1' class='noBorder'><img src='pictures/system/userRed.png' class='noBorder' id='userIcon' title='".$user['login']." | Персональная страница' /></a>
							";
						
						if($_SESSION['userID'] != 1)
						{	
							$ordersResult = $mysqli->query("SELECT * FROM basket WHERE user_id = '".$_SESSION['userID']."' AND status = '0'");
							$ordersResult->num_rows;
							if($orders < 1)
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
							$ordersResult->num_rows;
							if($orders < 1)
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
 	
    <div id='content'>
    	<div id='settingsBlock'>
        	<div id='settingsMenu'>
            	<?php
				
					if($_REQUEST['s'] == '1')
					{
						echo "
							<a href='settings.php?s=1' class='noBorder'>
								<div class='settingsMenuButtonActive'>
									<span class='headerStyleWhite'>Основные настройки</span>
								</div>
							</a>
						";
					}
					else
					{
						echo "
							<a href='settings.php?s=1' class='noBorder'>
								<div class='settingsMenuButton' id='sb1' onmouseover='settingsMenuButton(1, \"sb1\", \"tsb1\")' onmouseout='settingsMenuButton(0, \"sb1\", \"tsb1\")'>
									<span class='headerStyle' id='tsb1'>Основные настройки</span>
								</div>
							</a>
						";
					}
					
					if($_REQUEST['s'] == '2')
					{
						echo "
							<a href='settings.php?s=2' class='noBorder'>
								<div class='settingsMenuButtonActive'>
									<span class='headerStyleWhite'>Изменение пароля</span>
								</div>
							</a>
						";
					}
					else
					{
						echo "
							<a href='settings.php?s=2' class='noBorder'>
								<div class='settingsMenuButton' id='sb2' onmouseover='settingsMenuButton(1, \"sb2\", \"tsb2\")' onmouseout='settingsMenuButton(0, \"sb2\", \"tsb2\")'>
									<span class='headerStyle' id='tsb2'>Изменение пароля</span>
								</div>
							</a>
						";
					}
					
					if($_REQUEST['s'] == '3')
					{
						if($_SESSION['userID'] != 1)
						{
							echo "
								<a href='settings.php?s=3' class='noBorder'>
									<div class='settingsMenuButtonActive'>
										<span class='headerStyleWhite'>Удаление аккаунта</span>
									</div>
								</a>
							";
						}
						else
						{
							echo "
								<a href='settings.php?s=3' class='noBorder'>
									<div class='settingsMenuButtonActive'>
										<span class='headerStyleWhite'>Управление сайтом</span>
									</div>
								</a>
							";
						}
					}
					else
					{
						if($_SESSION['userID'] != '1')
						{
							echo "
								<a href='settings.php?s=3' class='noBorder'>
									<div class='settingsMenuButton' id='sb3' onmouseover='settingsMenuButton(1, \"sb3\", \"tsb3\")' onmouseout='settingsMenuButton(0, \"sb3\", \"tsb3\")'>
										<span class='headerStyle' id='tsb3'>Удаление аккаунта</span>
									</div>
								</a>
							";
						}
						else
						{
							echo "
								<a href='settings.php?s=3' class='noBorder'>
									<div class='settingsMenuButton' id='sb3' onmouseover='settingsMenuButton(1, \"sb3\", \"tsb3\")' onmouseout='settingsMenuButton(0, \"sb3\", \"tsb3\")'>
										<span class='headerStyle' id='tsb3'>Управление сайтом</span>
									</div>
								</a>
							";
						}
					}
				
				?>
                
            </div>
        	<div id='settingsBlockInner'>
                
				<?php
				
					switch($_REQUEST['s'])
					{
						case "1":
							echo "<br /><br /><br /><br />";
							if(isset($_SESSION['settingsChange']))
							{
								switch($_SESSION['settingsChange'])
								{
									case "ok":
										echo "<span class='basicGreen'>Ваши данные были успешно изменены.</span>";
										break;
									case "failed":
										echo "<span class='basicRed'>При внесении изменений произошла ошибка. Попробуйте снова.</span>";
										break;
									case "empty":
										echo "<span class='basicRed'>Все поля должны быть заполнены.</span>";
										break;
									default:
										break;
								}
								
								echo "<br /><br />";
								unset($_SESSION['settingsChange']);
							}
							
							echo "
								<form name='settings1Form' id='settings1Form' method='post' action='scripts/changeUserSettings.php'>
									<label>Контактное лицо:</label>
									<br />
									<input type='text' name='settingsName' value='".$user['person']."' />
									<br /><br />
							";
							if(!empty($user['organisation']))
							{
								echo "
									<label>Название организации:</label>
									<br />
									<input type='text' name='settingsOrganisation' value='".$user['organisation']."' />
									<br /><br />
								";	
							}
							else
							{
								echo "
									<label>Название организации:</label>
									<br />
									<input type='text' id='inputReadOnly' readonly />
									<br /><br />
								";
							}
							echo "
									<label>Контактный телефон:</label>
									<br />
									<input type='text' name='settingsPhone' value='".$user['phone']."' />
									<br /><br />
									<input type='submit' id='settings1Submit' value='внести изменения' />
								</form>
							";
							break;
						case "2":
							echo "<br /><br /><br /><br />";
							if(isset($_SESSION['settingsChange']))
							{
								switch($_SESSION['settingsChange'])
								{
									case "ok":
										echo "<span class='basicGreen'>Ваш пароль успешно изменён.</span>";
										break;
									case "failed":
										echo "<span class='basicRed'>При изменении пароля произошла ошибка. Попробуйте снова.</span>";
										break;
									case "empty":
										echo "<span class='basicRed'>Введите новый пароль в оба текстовых поля.</span>";
										break;
									case "length":
										echo "<span class='basicRed'>Длина пароля должна составлять от 5 до 25 символов.</span>";
										break;
									case "different":
										echo "<span class='basicRed'>Новый пароль и контрольный пароль не совпадают. Повторите ввод более внимательно.</span>";
										break;
									default:
										break;
								}
								echo "<br /><br />";
								unset($_SESSION['settingsChange']);
							}
							echo "
								<form name='settings2Form' id='settings2Form' method='post' action='scripts/changeUserPassword.php'>
									<label>Введите новый пароль:</label>
									<br />
									<input type='password' name='newPassword' />
									<br /><br />
									<label>Введите новый пароль ещё раз:</label>
									<br />
									<input type='password' name='newPasswordRepeat'>
									<br /><br />
									<input type='submit' id='newPasswordSubmit' value='Изменить пароль' />
								</form>
							";
							break;
						case "3":
							if($_SESSION['userID'] != '1')
							{
								echo "<br /><br /><br /><br />";
								if(isset($_SESSION['delete']) and $_SESSION['delete'] == 'ok')
								{
									echo "<span class='basicRed'>При удалении аккаунта возникла ошибка. Попробуйте повторить попытку.</span><br /><br />";
									unset($_SESSION['delete']);
								}
								echo "
									<form name='settings3Form' id='settings3Form' method='post' action='scripts/deleteAccount.php'>
										<label>Если вы уверены, что хотите удалить аккаунт, нажмите на кнопку ниже. Обратите внимание, что аккаунт будет удалён безвозвратно без возможности восстановления. Все личные данные также будут удалены.</label>
										<br /><br />
										<center><input type='submit' id='deleteAccountSubmit' value='Удалить аккаунт' /></center>
									</form>
								";
							}
							else
							{
								if(!empty($_SESSION['exchange']))
								{
									switch($_SESSION['exchange'])
									{
										case 'ok':
											echo "<div style='position: absolute; top: 50px; left: 30px;'><span class='basicGreen'>Курс доллара успешно изменён.</span></div>";
											break;
										case 'false':
											echo "<span class='basicRed'>При изменении курса произошла ошибка. Попробуйте снова.</span>";
											break;
										case 'empty':
											echo "<span class='basicRed'>Вы не ввели курс доллара.</span>";
											break;
										case 'format':
											echo "<span class='basicRed'>Курс доллара может состоять только из цифр.</span>";
											break;
										default:
											break;
									};
									unset($_SESSION['exchange']);
								}
								
								$rateResult = $mysqli->query("SELECT * FROM currency WHERE code = 'usd'");
								$rate = $rateResult->fetch_assoc();
								
								echo "
									<br />
									<form id='siteSettingsForm' action='scripts/changeSiteSettings.php' method='post'>
										<label>Курс доллара:</label>
										<br />
										<input type='text' id='exchangeRate' value='".$rate['rate']."' name='exchangeRate' />
										<br /><br />
										<input type='submit' value='Внести изменения' id='siteSettingsSubmit' />
									</form>
									
									<a href='admin/index.php' class='noBorder'>
										<div id='toAdminButton'>
											Войти в панель администрирования
										</div>
									</a>
								";
							}
							break;
						default:
							break;
					}
				
				?>
                
            </div>
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
    
</body>

</html>