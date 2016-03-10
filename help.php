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

    <title>Аргос-ФМ | Помощь</title>

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
    <script type='text/javascript' src='js/jquery-1.8.3.min.js'></script>
    <script type='text/javascript' src='js/ajax.js'></script>
    <script type='text/javascript' src='js/functions.js'></script>
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

<div id='layout' <?php if((isset($_SESSION['login']) and $_SESSION['login'] != 1) or isset($_SESSION['recovery']) or isset($_SESSION['recovery_final']) or isset($_SESSION['registration']) or isset($_SESSION['activation']) or isset($_SESSION['activationFalse']) or isset($_SESSION['registration_cancel']) or isset($_SESSION['delete']) or isset($_SESSION['basket'])) {echo "style='display: block;'";} else {echo "style='display: none;'";} ?> onclick='resetBlocks();' onmousemove='resizeLayout()' onmousewheel='resizeLayout()'></div>

<?php
if(!empty($_SESSION['recovery']) and $_SESSION['recovery'] == 'sent')
{
    echo "
					<div id='notificationWindowOuter' style='display: block;'>
						<div id='notificationWindow'>
							<form id='recoveryNotificationForm'>
								<center><span class='headerStyleRed'>Восстановление пароля</span></center>
								<br /><br />
								<span class='basic'>Запрос на изменение пароля был отправлен на адрес, указанный при регистрации: <b>".$_SESSION['recovery_email']."</b>. Для продолжения перейдите по ссылке, находяцейся в письме.</span>
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
						<center><span class='headerStyleRed'>Восстановление пароля</span></center>
						<br /><br />
						<span class='basic'>Ваш пароль был изменён. Новый пароль был отправлен на адрес, указанный при регистрации: <b>".$_SESSION['recovery_email']."</b>.</span>
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
						<center><span class='headerStyleRed'>Восстановление пароля</span></center>
						<br /><br />
						<span class='basic'>К сожалению, при изменении вашего пароля произошла ошибка. Повторите попытку, либо свяжитесь с нами. Наши контактные данные указаны в разделе \"Контакты\".</span>
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
								<center><span class='headerStyleRed'>Регистрация почти завершена!</span></center>
								<br /><br />
								<span class='basic'>Поздравляем! Вы успешно зарегистрировались. Теперь вам необходимо подтвердить ваш электронный адрес. Для этого перейдите по ссылке из письма, которое мы вам отправили.</span>
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
        <label>Логин: </label><span class='hintText' id='hintLogin' title='Логин должен состоять минимум из 3 латинских букв, цифр или допустимых символов.'>(подсказка)</span>
        <br />
        <input type='text' class='admInput' name='userLogin' id='userLoginInput' <?php if(isset($_SESSION['registration_login'])){echo " value='".$_SESSION['registration_login']."'";} ?> />
        <br /><br />
        <label>Пароль: </label><span class='hintText' id='hintPassword' title='Пароль должен содержать минимум 5 символов.'>(подсказка)</span>
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
							<label>Название организации:</label>
							<br />
							<input type='text' class='admInput' name='organisation' id='organisationInput' "; if(isset($_SESSION['registration_organisation'])){echo " value='".$_SESSION['registration_organisation']."'";} echo "/>";
        }
        ?>
        <br /><br />
        <label>Контактное лицо:</label>
        <br />
        <input type='text' class='admInput' name='userName' id='userNameInput' <?php if(isset($_SESSION['registration_name'])){echo " value='".$_SESSION['registration_name']."'";} ?> />
        <br /><br />
        <label>Контактный телефон: </label><span class='hintText' id='hintPhone' title='Номер телефона желательно указывать в международном формате: +375 (XX) XXXXXXX'>(подсказка)</span>
        <br />
        <input type='text' class='admInput' name='userPhone' id='userPhoneInput' <?php if(isset($_SESSION['registration_phone'])){echo " value='".$_SESSION['registration_phone']."'";} ?> />
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
            <a href='index.php' class='noBorder' title='Вернуться на главную страницу'>
                <div id='mainPoint' onmouseover='menuVisual("1", "mpIMG", "mpTop")' onmouseout='menuDefault1()'>
                    <div id='mainPointCenter'>
                        <div id='mpTop'></div>
                        <div class='pBottom'>
                            <img src='pictures/system/mainText.png' id='mpIMG' class='noBorder'>
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

<div id='content_news'>
    <div id='content_news_inner'>
        <span class='bigHeaderStyle'>Помощь. Как работать с сайтом "</span><a href='index.php' class='noBorder'><h1 class='headerStyleRedHover' style='text-decoration: none;'>Аргос-ФМ</h1></a><span class='bigHeaderStyle'>"</span>
        <div style='height: 20px;'></div>
        <?php
            if($_SESSION['userID'] == 1) {

            } else {
                if(empty($_REQUEST['section'])) {
                    echo "
                        <h2 class='goodStyle'>Основные страницы сайта</h2>
                        <ol>
                            <li><a href='help.php?section=1' class='noBorder' id='hlp1' onmouseover='helpText(1, \"hlp1\")' onmouseout='helpText(0, \"hlp1\")'>Главная страница</a></li>
                            <li><a href='help.php?section=2' class='noBorder' id='hlp2' onmouseover='helpText(1, \"hlp2\")' onmouseout='helpText(0, \"hlp2\")'>Каталог</a></li>
                            <li><a href='help.php?section=3' class='noBorder' id='hlp3' onmouseover='helpText(1, \"hlp3\")' onmouseout='helpText(0, \"hlp3\")'>Новости и предложения</a></li>
                            <li><a href='help.php?section=4' class='noBorder' id='hlp4' onmouseover='helpText(1, \"hlp4\")' onmouseout='helpText(0, \"hlp4\")'>Контактная инфрмация</a></li>
                            <li><a href='help.php?section=5' class='noBorder' id='hlp5' onmouseover='helpText(1, \"hlp5\")' onmouseout='helpText(0, \"hlp5\")'>Страница поиска</a></li>
                            <li><a href='help.php?section=6' class='noBorder' id='hlp6' onmouseover='helpText(1, \"hlp6\")' onmouseout='helpText(0, \"hlp6\")'>Личный кабинет</a></li>
                            <li><a href='help.php?section=7' class='noBorder' id='hlp7' onmouseover='helpText(1, \"hlp7\")' onmouseout='helpText(0, \"hlp7\")'>Корзина</a></li>
                            <li><a href='help.php?section=8' class='noBorder' id='hlp8' onmouseover='helpText(1, \"hlp8\")' onmouseout='helpText(0, \"hlp8\")'>История заказов</a></li>
                        </ol>
                        <h2 class='goodStyle'>Блок регистрации и авторизации</h2>
                        <ol>
                            <li><a href='help.php?section=9' class='noBorder' id='hlp9' onmouseover='helpText(1, \"hlp9\")' onmouseout='helpText(0, \"hlp9\")'>Авторизация</a></li>
                            <li><a href='help.php?section=10' class='noBorder' id='hlp10' onmouseover='helpText(1, \"hlp10\")' onmouseout='helpText(0, \"hlp10\")'>Регистрация</a></li>
                            <li><a href='help.php?section=11' class='noBorder' id='hlp11' onmouseover='helpText(1, \"hlp11\")' onmouseout='helpText(0, \"hlp11\")'>Восстановление пароля</a></li>
                        </ol>
                        <h2 class='goodStyle'>Ценовая политика</h2>
                        <ol>
                            <li><a href='help.php?section=12' class='noBorder' id='hlp12' onmouseover='helpText(1, \"hlp12\")' onmouseout='helpText(0, \"hlp12\")'>Основные положения</a></li>
                            <li><a href='help.php?section=13' class='noBorder' id='hlp13' onmouseover='helpText(1, \"hlp13\")' onmouseout='helpText(0, \"hlp13\")'>Динамическое изменение цен в заказе</a></li>
                        </ol>
                        <h2 class='goodStyle'>Функции сайта</h2>
                        <ol>
                            <li><a href='help.php?section=14' class='noBorder' id='hlp14' onmouseover='helpText(1, \"hlp14\")' onmouseout='helpText(0, \"hlp14\")'>Оформление заказа</a></li>
                            <li><a href='help.php?section=15' class='noBorder' id='hlp15' onmouseover='helpText(1, \"hlp15\")' onmouseout='helpText(0, \"hlp15\")'>Изменение отправленной заявки</a></li>
                            <li><a href='help.php?section=16' class='noBorder' id='hlp16' onmouseover='helpText(1, \"hlp16\")' onmouseout='helpText(0, \"hlp16\")'>Редактирование личных данных</a></li>
                            <li><a href='help.php?section=17' class='noBorder' id='hlp17' onmouseover='helpText(1, \"hlp17\")' onmouseout='helpText(0, \"hlp17\")'>Изменения пароля</a></li>
                            <li><a href='help.php?section=18' class='noBorder' id='hlp18' onmouseover='helpText(1, \"hlp18\")' onmouseout='helpText(0, \"hlp18\")'>Удаление аккаунта</a></li>
                        </ol>
                    ";
                } else {
                    switch($_REQUEST['section']) {
                        case "1":
                            echo "
                                <h2 class='goodStyle'>Структура главной страницы</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalic'> > </span><a href='help.php?section=1' class='noBorder'><span class='catalogueItemTextItalic'>Главная страница</span></a></p>
                                <div class='helpBlock'>
                                    <p>Главная страница сайта <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>Аргос-ФМ</span></a> состоит из трёх конструктивных блоков:</p>
                                    <ol>
                                        <li><a href='pictures/help/index.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Навигационный блок</a></li>
                                        <li><a href='pictures/help/index_news.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Блок последних новостей</a></li>
                                        <li><a href='pictures/help/index_news.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Блок последних новостей</a></li>
                                        <li><a href='pictures/help/index_partners.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp3\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'>Ссылки на партнёров</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>Навигационный блок служит для быстрого перехода в интересующий вас раздел <a href='catalogue.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>каталога</span><span style='color: #3f3f3f;'> (<a href='catalogue.php?type=fa&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>мебельная фурнитура</span></a>, <a href='catalogue.php?type=em&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>кромочные материалы</span></a>, <a href='catalogue.php?type=ca&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>аксессуары для штор</span></a>) путём нажатия на соответствующую кнопку <b>\"В каталог\"</b> или на изображение.</span></p></div>
                               <div class='helpBlock'> <p>Блок последних новостей содержит в себе три последние новости, опубликованные на сайте. Прочитать полный текст новости можно, нажав на блок с названием и кратким описанием интересующей вас новости. Данный блок также содержит 2 ссылки: <b>\"Помощь\"</b> (ведёт на <a href='help.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>страницу помощи по сайту</span></a>) и <b>\"Все новости\"</b> (ведёт на <a href='news.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>страницу с полным списком новостей</span></a>).</p><p>Для помощи по странице новостей перейдите по <a href='help.php?section=3' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>данной ссылке</span></a>.</p></div>
                                <div class='helpBlock'><p>Партнёрский блок содержит ссылки на официальные сайты наших партнёров в виде их логотипов.</p></div>
                            ";
                            break;
                        case "2":
                            echo "
                                <h2 class='goodStyle'>Структура каталога</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalic'> > </span><a href='help.php?section=2' class='noBorder'><span class='catalogueItemTextItalic'>Каталог</span></a></p>
                                <div class='helpBlock'>
                                    <p>Страница каталога <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>Аргос-ФМ</span></a> состоит из двух конструктивных блоков:</p>
                                    <ol>
                                        <li><a href='pictures/help/catalogue_nav.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Навигационный блок</a></li>
                                        <li><a href='pictures/help/catalogue_content.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Блок с содержимым каталога</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>Навигационный блок состоит из трёх основных разделов: <a href='catalogue.php?type=fa&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>мебельная фурнитура</span></a>, <a href='catalogue.php?type=em&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>кромочные материалы</span></a>, <a href='catalogue.php?type=ca&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>аксессуары для штор</span></a>. Большинство разделов состоят из подразделов. Заходя в выбранный вами раздел <a href='pictures/help/catalogue_nav_selected.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>развернётся меню его подразделов</span></a>, где вам необходимо будет выбрать интересующий вас подраздел. У подразделов также <a href='pictures/help/catalogue_nav_selected2.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>могут быть подкатегории</span></a>.</p></div>
                                <div class='helpBlock'><p>В блоке с содержимым каталога выводятся товары по 10 наименований на одной странице. Если в выбраном разделе более 10 видов товаров, то перейти на следующую страницу можно при помощи <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>блока постраничной навигации</span></a>, расположенном внизу.</p></div>
                                <div class='helpBlock'><p>Логика построения списка товаров следующая: если вы не выбрали ни одна из трёх основных категорий, товары выстраиваются в случайном порядке, иначе в случайном порядке будут построены товары из выбранной категории. Если выбран раздел из категории и он содержит подразделы, товары из данного раздела и всех его подразделов будут выстроены в случайном порядке. Если раздел не содержит подразделов, то товары будут отображены и отсортированы согласно приоритету, указанному администратором в базе данных.</p></div>
                                <div class='helpBlock'><p>Гости и авторизованные пользователи видят каталог по-разному. <a href='pictures/help/catalogue_content.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Так страницу каталога видит авторизованный пользователь</span></a>, а <a href='pictures/help/catalogue_guest.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>так её видит гость</span></a>. Преимуществом зарегистрированных пользователей является возможность добавлять товары в корзину и оформлять онлайн-заказы.</p></div>
                                <div class='helpBlock'><p>Узнать, как добавить товар в корзину можно <a href='help.php?section=14' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>на этой странице</span></a>.</p></div>
                            ";
                            break;
                        case "3":
                            echo "
                                <h2 class='goodStyle'>Структура страницы новостей</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalic'> > </span><a href='help.php?section=3' class='noBorder'><span class='catalogueItemTextItalic'>Новости</span></a></p>
                                <div class='helpBlock'><p>На странице новостей показаны все новости и коммерческие предложения, которые когда-либо были опубликованы. На этой странице показано 10 новостей на одной странице. Они отсортированы по дате публикации. Для просмотра более ранних публикаций, необходимо воспользоваться <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>панелью постраничной навигации</span></a>, расположенной внизу страницы.</p></div>
                                <div class='helpBlock'><p>Поиск новостей может осуществляться по дате при помощи <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>календаря</span></a> в верхней правой части страницы и <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>панели поиска</span></a>.</p></div>
                                <div class='helpBlock'><p>Поиск по дате осуществляется путём выбора даты в календаре. Если в определённый день были публикации, дата в календаре будет написана <a href='pictures/help/news_calendar_date.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>красным цветом на сером фоне</span></a>. При выборе даты <a href='pictures/help/news_date.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>будут показаны новости</span></a>, написанные в этот день. Под календарём появится кнопка <b>\"Сбросить дату\"</b>, которая необходима для возврата к списку всех новостей.</p></div>
                                <div class='helpBlock'><p>Для поиска новости по заголовки, котороткому описанию или тексту новости, начните набирать текст <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>в поле для поиска</span></a>. В процессе набора вам будут показаны найденные совпадения <a href='pictures/help/news_search.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>в выпадающем блоке</span></a>. Если совпадений не будет найдено, вы увидите надпись <b>\"К сожалению, ничего похожего не найдено\"</b>. Чтобы закрыть выпадающий блок, нажмите на любое место на экране вне самого блока.</p></div>
                                <div class='helpBlock'><p>Для прочтения новости нажмите на блок с её заголовком и коротким описанием в общем списке новостей или в выпадающем блоке поиска.</p></div>
                            ";
                            break;
                        case "4":
                            echo "
                                <h2 class='goodStyle'>Структура страницы с контактной информацией</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalic'> > </span><a href='help.php?section=4' class='noBorder'><span class='catalogueItemTextItalic'>Контактная информация</span></a></p>
                                <div class='helpBlock'>
                                    <p>Страница с контактной информацией <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>Аргос-ФМ</span></a> состоит из двух конструктивных блоков:</p>
                                    <ol>
                                        <li><a href='pictures/help/contacts_map.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Блок с интерактивной картой</a></li>
                                        <li><a href='pictures/help/contacts.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Блок с контактной информацией</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>С интерактивной картой можно взаимодействовать: изменять масштаб, перемещаться, включать отображение пробок и построение маршрутов.</p></div>
                            ";
                            break;
                        case "5":
                            echo "
                                <h2 class='goodStyle'>Структура страницы поиска</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalic'> > </span><a href='help.php?section=5' class='noBorder'><span class='catalogueItemTextItalic'>Страница поиска</span></a></p>
                                <div class='helpBlock'><p>На странице поиска выводятся товары, название которых или артикул максимально похожи с введёнными вами данными. На странице <a href='pictures/help/search.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>отображено по 10 товаров</span></a>, если было найдено 10 или более совпадений. Для перехода на следующую страницу воспользуйтесь <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>блоком постраничной навигации</span></a>, расположенным внизу странцы.</p></div>
                                <div class='helpBlock'><p><a href='pictures/help/search_item.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Блок отдельного товара</span></a> на странице поиска состоит из панели навигации, фотографии, названия, описания, артикула и чертежа.</p></div>
                                <div class='helpBlock'><p><a href='pictures/help/search_nav.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Панель навигации товара</span></a> показывает, в каких разделах данный товар расположен.</p></div>
                            ";
                            break;
                        case "6":
                            echo "
                                <h2 class='goodStyle'>Структура личного кабинета</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalic'> > </span><a href='help.php?section=5' class='noBorder'><span class='catalogueItemTextItalic'>Личный кабинет</span></a></p>
                                <div class='helpBlock'>
                                    <p>Личный кабинет состоит из трёх конструктивных блоков:</p>
                                    <ol>
                                        <li><a href='pictures/help/settings_1.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Основные настройки</a></li>
                                        <li><a href='pictures/help/settings_2.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Изменение пароля</a></li>
                                        <li><a href='pictures/help/settings_3.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp3\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'>Удаление аккаунта</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>Помощь по изменению личной информации читайте в <a href='catalogue.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                                <div class='helpBlock'><p>Помощь по изменению пароля читайте в <a href='catalogue.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                                <div class='helpBlock'><p>Помощь по удалению вашего аккаунта читайте в <a href='catalogue.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                            ";
                            break;
                        case "7":
                            break;
                        case "8":
                            break;
                        case "9":
                            break;
                        case "10":
                            break;
                        case "11":
                            break;
                        case "12":
                            break;
                        case "13":
                            break;
                        case "14":
                            break;
                        case "15":
                            break;
                        case "16":
                            break;
                        case "17":
                            break;
                        case "18":
                            break;
                        default:
                            break;
                    }
                }
            }
        ?>
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
    $(window).load(function(e) {
        var fullHeight = $('#content_news').offset().top + $('#content_news').height() + 50;

        if($('footer').offset().top < fullHeight) {
            $('footer').offset({top: fullHeight});
        }
    });
</script>

</body>

</html>