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

    <?php
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false) {
            echo "<link rel='stylesheet' media='screen' type='text/css' href='css/styleOpera.css'>";
        }

        if(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
            echo "<link rel='stylesheet' type='text/css' href='js/shadowbox/source/shadowbox.css'>";
        } else {
            echo "<link rel='stylesheet' type='text/css' href='js/lightbox/css/lightbox.css'>";
        }
    ?>

    <script type='text/javascript' src='js/menu.js'></script>
    <script type='text/javascript' src='js/jquery-1.8.3.min.js'></script>
    <script type='text/javascript' src='js/ajax.js'></script>
    <script type='text/javascript' src='js/functions.js'></script>

    <?php
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
            echo "<script type='text/javascript' src='js/shadowbox/source/shadowbox.js'></script>";
        } else {
            echo "<script type='text/javascript' src='js/lightbox/js/lightbox.js'></script>";
        }
    ?>

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
							    <div  style='width: 100%; height: 20px;'></div>
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
        <input type='radio' name='userType' value='organisation' class='radio' onclick='registrationType(1)' <?php if(isset($_SESSION['registration_type'])){if($_SESSION['registration_type'] == '1'){echo "checked";}}else{echo "checked";} ?>><span class='mainIMGText'>Организация</span><br />
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
                            <li><a href='help.php?section=7' class='noBorder' id='hlp7' onmouseover='helpText(1, \"hlp7\")' onmouseout='helpText(0, \"hlp7\")'>Страница заявок</a></li>
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
                            <li><a href='help.php?section=20' class='noBorder' id='hlp20' onmouseover='helpText(1, \"hlp20\")' onmouseout='helpText(0, \"hlp20\")'>Изменение курса доллара</a></li>
                        </ol>
                        <h2 class='goodStyle'>Каталог</h2>
                        <ol>
                            <li><a href='help.php?section=8' class='noBorder' id='hlp8' onmouseover='helpText(1, \"hlp8\")' onmouseout='helpText(0, \"hlp8\")'>Изменение цены товара</a></li>
                        </ol>
                        <h2 class='goodStyle'>Страница заказов</h2>
                        <ol>
                            <li><a href='help.php?section=14' class='noBorder' id='hlp14' onmouseover='helpText(1, \"hlp14\")' onmouseout='helpText(0, \"hlp14\")'>Обработка заявок</a></li>
                            <li><a href='help.php?section=15' class='noBorder' id='hlp15' onmouseover='helpText(1, \"hlp15\")' onmouseout='helpText(0, \"hlp15\")'>Изменение полученной заявки</a></li>
                            <li><a href='help.php?section=16' class='noBorder' id='hlp16' onmouseover='helpText(1, \"hlp16\")' onmouseout='helpText(0, \"hlp16\")'>История заказов</a></li>
                        </ol>
                        <h2 class='goodStyle'>Страница новостей</h2>
                        <ol>
                            <li><a href='help.php?section=17' class='noBorder' id='hlp17' onmouseover='helpText(1, \"hlp17\")' onmouseout='helpText(0, \"hlp17\")'>Добавление новости</a></li>
                            <li><a href='help.php?section=18' class='noBorder' id='hlp18' onmouseover='helpText(1, \"hlp18\")' onmouseout='helpText(0, \"hlp18\")'>Редактирование новости</a></li>
                            <li><a href='help.php?section=19' class='noBorder' id='hlp19' onmouseover='helpText(1, \"hlp19\")' onmouseout='helpText(0, \"hlp19\")'>Поиск новостей</a></li>
                        </ol>
                        <h2 class='goodStyle'>Личный кабинет</h2>
                        <ol>
                            <li><a href='help.php?section=21' class='noBorder' id='hlp21' onmouseover='helpText(1, \"hlp21\")' onmouseout='helpText(0, \"hlp21\")'>Редактирование персональных данных</a></li>
                            <li><a href='help.php?section=22' class='noBorder' id='hlp22' onmouseover='helpText(1, \"hlp22\")' onmouseout='helpText(0, \"hlp22\")'>Изменение пароля</a></li>
                            <li><a href='help.php?section=20' class='noBorder' id='hlp20' onmouseover='helpText(1, \"hlp20\")' onmouseout='helpText(0, \"hlp20\")'>Изменение курса доллара</a></li>
                        </ol>
                        <h2 class='goodStyle'>Панель администрирования</h2>
                        <ol>
                            <li><a href='help.php?section=23' class='noBorder' id='hlp23' onmouseover='helpText(1, \"hlp23\")' onmouseout='helpText(0, \"hlp23\")'>Вход в панель администрирования</a></li>
                            <li><a href='help.php?section=24' class='noBorder' id='hlp24' onmouseover='helpText(1, \"hlp24\")' onmouseout='helpText(0, \"hlp24\")'>Структура панели администрирования</a></li>
                            <li><a href='help.php?section=25' class='noBorder' id='hlp25' onmouseover='helpText(1, \"hlp25\")' onmouseout='helpText(0, \"hlp25\")'>Добавление товара в каталог</a></li>
                            <li><a href='help.php?section=26' class='noBorder' id='hlp26' onmouseover='helpText(1, \"hlp26\")' onmouseout='helpText(0, \"hlp26\")'>Редактирование товара</a></li>
                            <li><a href='help.php?section=27' class='noBorder' id='hlp27' onmouseover='helpText(1, \"hlp27\")' onmouseout='helpText(0, \"hlp27\")'>Удаление товара из каталога</a></li>
                            <li><a href='help.php?section=28' class='noBorder' id='hlp28' onmouseover='helpText(1, \"hlp28\")' onmouseout='helpText(0, \"hlp28\")'>Добавление нового раздела</a></li>
                            <li><a href='help.php?section=29' class='noBorder' id='hlp29' onmouseover='helpText(1, \"hlp29\")' onmouseout='helpText(0, \"hlp29\")'>Редактирование раздела</a></li>
                            <li><a href='help.php?section=30' class='noBorder' id='hlp30' onmouseover='helpText(1, \"hlp30\")' onmouseout='helpText(0, \"hlp30\")'>Удаление раздела</a></li>
                            <li><a href='help.php?section=31' class='noBorder' id='hlp31' onmouseover='helpText(1, \"hlp31\")' onmouseout='helpText(0, \"hlp31\")'>Отправление e-mail рассылок</a></li>
                            <li><a href='help.php?section=32' class='noBorder' id='hlp32' onmouseover='helpText(1, \"hlp32\")' onmouseout='helpText(0, \"hlp32\")'>История e-mail рассылок</a></li>
                            <li><a href='help.php?section=33' class='noBorder' id='hlp33' onmouseover='helpText(1, \"hlp33\")' onmouseout='helpText(0, \"hlp33\")'>Структура страницы клиентской базы</a></li>
                            <li><a href='help.php?section=34' class='noBorder' id='hlp34' onmouseover='helpText(1, \"hlp34\")' onmouseout='helpText(0, \"hlp34\")'>Добавление записи в клиентскую базу</a></li>
                            <li><a href='help.php?section=35' class='noBorder' id='hlp35' onmouseover='helpText(1, \"hlp35\")' onmouseout='helpText(0, \"hlp35\")'>Редактирование записи из клиентской базы</a></li>
                            <li><a href='help.php?section=36' class='noBorder' id='hlp36' onmouseover='helpText(1, \"hlp36\")' onmouseout='helpText(0, \"hlp36\")'>Удаление записи из клиентской базы</a></li>
                            <li><a href='help.php?section=37' class='noBorder' id='hlp37' onmouseover='helpText(1, \"hlp37\")' onmouseout='helpText(0, \"hlp37\")'>Быстрый поиск адреса в клиентской базе</a></li>
                            <li><a href='help.php?section=38' class='noBorder' id='hlp38' onmouseover='helpText(1, \"hlp38\")' onmouseout='helpText(0, \"hlp38\")'>Поиск адреса из клиентской базы по первому символу</a></li>
                            <li><a href='help.php?section=39' class='noBorder' id='hlp39' onmouseover='helpText(1, \"hlp39\")' onmouseout='helpText(0, \"hlp39\")'>Список клиентов, удалившихся из рассылки</a></li>
                            <li><a href='help.php?section=40' class='noBorder' id='hlp40' onmouseover='helpText(1, \"hlp40\")' onmouseout='helpText(0, \"hlp40\")'>Список зарегистрированных пользователей</a></li>
                            <li><a href='help.php?section=41' class='noBorder' id='hlp41' onmouseover='helpText(1, \"hlp41\")' onmouseout='helpText(0, \"hlp41\")'>Изменение личных данных пользователей</a></li>
                            <li><a href='help.php?section=42' class='noBorder' id='hlp42' onmouseover='helpText(1, \"hlp42\")' onmouseout='helpText(0, \"hlp42\")'>Список опубликованных новостей</a></li>
                        </ol>
                    ";
                } else {
                    switch($_REQUEST['section']) {
                        case "1":
                            echo "
                                <h2 class='goodStyle'>Структура главной страницы</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=1' class='noBorder'><span class='catalogueItemTextItalic'>Главная страница</span></a></p>
                                <div class='helpBlock'>
                                    <p>Главная страница сайта <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>Аргос-ФМ</span></a> состоит из трёх конструктивных блоков:</p>
                                    <ol>
                                        <li><a href='pictures/help/index.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Навигационный блок</a></li>
                                        <li><a href='pictures/help/index_news_adm.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Блок последних новостей</a></li>
                                        <li><a href='pictures/help/index_partners.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp3\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'>Ссылки на сайты партнёров</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>Навигационный блок служит для быстрого перехода в интересующий вас раздел <a href='catalogue.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>каталога</span><span style='color: #3f3f3f;'> (<a href='catalogue.php?type=fa&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>мебельная фурнитура</span></a>, <a href='catalogue.php?type=em&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>кромочные материалы</span></a>, <a href='catalogue.php?type=ca&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>аксессуары для штор</span></a>) путём нажатия на соответствующую кнопку <b>\"В каталог\"</b> или на изображение.</span></p></div>
                               <div class='helpBlock'> <p>Блок последних новостей содержит в себе три последние новости, опубликованные на сайте. Прочитать полный текст новости можно, нажав на блок с названием и кратким описанием интересующей вас новости. Данный блок также содержит 3 ссылки: <b>\"Помощь\"</b> (ведёт на <a href='help.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>страницу помощи по сайту</span></a>), <b>\"Все новости\"</b> (ведёт на <a href='news.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>страницу с полным списком новостей</span></a>) и <b>\"Написать новость\"</b> (ведёт на <a href='admin/admin.php?section=users&action=addNews' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>страницу написания новости</span></a>).</p><p>Для помощи по странице новостей перейдите по <a href='help.php?section=3' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>данной ссылке</span></a>.</p></div>
                                <div class='helpBlock'><p>Партнёрский блок содержит ссылки на официальные сайты наших партнёров в виде их логотипов.</p></div>
                            ";
                            break;
                        case "2":
                            echo "
                                <h2 class='goodStyle'>Структура каталога</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=2' class='noBorder'><span class='catalogueItemTextItalic'>Каталог</span></a></p>
                                <div class='helpBlock'>
                                    <p>Страница каталога <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>Аргос-ФМ</span></a> состоит из двух конструктивных блоков:</p>
                                    <ol>
                                        <li><a href='pictures/help/catalogue_nav.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Навигационный блок</a></li>
                                        <li><a href='pictures/help/catalogue_content_adm.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Блок с содержимым каталога</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>Навигационный блок состоит из трёх основных разделов: <a href='catalogue.php?type=fa&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>мебельная фурнитура</span></a>, <a href='catalogue.php?type=em&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>кромочные материалы</span></a>, <a href='catalogue.php?type=ca&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>аксессуары для штор</span></a>. Большинство разделов состоят из подразделов. Заходя в выбранный вами раздел <a href='pictures/help/catalogue_nav_selected.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>развернётся меню его подразделов</span></a>, где вам необходимо будет выбрать интересующий вас подраздел. У подразделов также <a href='pictures/help/catalogue_nav_selected2.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>могут быть подкатегории</span></a>.</p></div>
                                <div class='helpBlock'><p>В блоке с содержимым каталога выводятся товары по 10 наименований на одной странице. Если в выбраном разделе более 10 видов товаров, то перейти на следующую страницу можно при помощи <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>блока постраничной навигации</span></a>, расположенном внизу.</p></div>
                                <div class='helpBlock'><p>Логика построения списка товаров следующая: если вы не выбрали ни одна из трёх основных категорий, товары выстраиваются в случайном порядке, иначе в случайном порядке будут построены товары из выбранной категории. Если выбран раздел из категории и он содержит подразделы, товары из данного раздела и всех его подразделов будут выстроены в случайном порядке. Если раздел не содержит подразделов, то товары будут отображены и отсортированы согласно приоритету, указанному администратором в базе данных.</p></div>
                                <div class='helpBlock'><p>Гости, администратор и авторизованные пользователи видят каталог по-разному. <a href='pictures/help/catalogue_content.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Так страницу каталога видит авторизованный пользователь</span></a>, а <a href='pictures/help/catalogue_guest.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>так её видит гость и администратор</span></a>. Преимуществом зарегистрированных пользователей является возможность добавлять товары в корзину и оформлять онлайн-заказы.</p></div>
                            ";
                            break;
                        case "3":
                            echo "
                                <h2 class='goodStyle'>Структура страницы новостей</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=3' class='noBorder'><span class='catalogueItemTextItalic'>Новости</span></a></p>
                                <div class='helpBlock'><p>На странице новостей показаны все новости и коммерческие предложения, которые когда-либо были опубликованы. Показано 10 новостей на одной странице. Они отсортированы по дате публикации. Для просмотра более ранних публикаций, необходимо воспользоваться <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>панелью постраничной навигации</span></a>, расположенной внизу страницы.</p></div>
                                <div class='helpBlock'><p>Поиск новостей может осуществляться по дате при помощи <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>календаря</span></a> в верхней правой части страницы и <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>панели поиска</span></a>.</p></div>
                                <div class='helpBlock'><p>Поиск по дате осуществляется путём выбора даты в календаре. Если в определённый день были публикации, дата в календаре будет написана <a href='pictures/help/news_calendar_date.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>красным цветом на сером фоне</span></a>. При выборе даты <a href='pictures/help/news_date.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>будут показаны новости</span></a>, написанные в этот день. Под календарём появится кнопка <b>\"Сбросить дату\"</b>, которая необходима для возврата к списку всех новостей.</p></div>
                                <div class='helpBlock'><p>Для поиска новости по заголовки, котороткому описанию или тексту новости, начните набирать текст <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>в поле для поиска</span></a>. В процессе набора вам будут показаны найденные совпадения <a href='pictures/help/news_search.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>в выпадающем блоке</span></a>. Если совпадений не будет найдено, вы увидите надпись <b>\"К сожалению, ничего похожего не найдено\"</b>. Чтобы закрыть выпадающий блок, нажмите на любое место на экране вне самого блока.</p></div>
                                <div class='helpBlock'><p>Для прочтения новости нажмите на блок с её заголовком и коротким описанием в общем списке новостей или в выпадающем блоке поиска.</p></div>
                                <div class='helpBlock'><p>В нижней правой части страницы расположена <a href='admin/admin.php?section=users&action=addNews' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>ссылка</span></a>, ведущая на <a href='help.php?section=17' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>страницу написания новости</span></a>.</p></div>
                            ";
                            break;
                        case "4":
                            echo "
                                <h2 class='goodStyle'>Структура страницы с контактной информацией</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=4' class='noBorder'><span class='catalogueItemTextItalic'>Контактная информация</span></a></p>
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
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=5' class='noBorder'><span class='catalogueItemTextItalic'>Страница поиска</span></a></p>
                                <div class='helpBlock'><p>На странице поиска выводятся товары, название которых или артикул максимально похожи с введёнными вами данными. На странице <a href='pictures/help/search.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>отображено по 10 товаров</span></a>, если было найдено 10 или более совпадений. Для перехода на следующую страницу воспользуйтесь <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>блоком постраничной навигации</span></a>, расположенным внизу странцы.</p></div>
                                <div class='helpBlock'><p><a href='pictures/help/search_item.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Блок отдельного товара</span></a> на странице поиска состоит из панели навигации, фотографии, названия, описания, артикула, цены и чертежа.</p></div>
                                <div class='helpBlock'><p><a href='pictures/help/search_nav.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Панель навигации товара</span></a> показывает, в каких разделах данный товар расположен.</p></div>
                            ";
                            break;
                        case "6":
                            echo "
                                <h2 class='goodStyle'>Структура личного кабинета</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=6' class='noBorder'><span class='catalogueItemTextItalic'>Личный кабинет</span></a></p>
                                <div class='helpBlock'>
                                    <p>Личный кабинет состоит из трёх конструктивных блоков:</p>
                                    <ol>
                                        <li><a href='pictures/help/settings_1_adm.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Основные настройки</a></li>
                                        <li><a href='pictures/help/settings_2.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Изменение пароля</a></li>
                                        <li><a href='pictures/help/settings_3_adm.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp3\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'>Управление сайтом</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>Помощь по изменению личной информации читайте в <a href='help.php?section=21' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                                <div class='helpBlock'><p>Помощь по изменению пароля читайте в <a href='help.php?section=22' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                                <div class='helpBlock'><p>Помощь по изменению курса доллара читайте в <a href='help.php?section=20' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                            ";
                            break;
                        case "7":
                            echo "
                                <h2 class='goodStyle'>Структура страницы заявок</h2>
                                    <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=7' class='noBorder'><span class='catalogueItemTextItalic'>Страница заявок</span></a></p>
                                    <div class='helpBlock'>
                                        <p>Страница заявок состоит из двух конструктивных блоков:</p>
                                        <ol>
                                            <li><a href='pictures/help/orders.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Активные заявки</a></li>
                                            <li><a href='pictures/help/orders_history.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>История заказов</a></li>
                                        </ol>
                                    </div>
                                    <div class='helpBlock'><p>На <a href='pictures/help/orders.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'><span class='basicBlue'>странице активных заявок</span></a> отображаются все непринятые заказы. Об обработке заказов читайте в <a href='help.php?section=14' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                                    <div class='helpBlock'><p>На <a href='pictures/help/orders_history.jpg' class='noBorder' rel='shadowbox' id='hlp4' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp4\")' style='color: #008fe1;'><span class='basicBlue'>странице с историей заказов</span></a> отображаются все принятые заявки. Помощь по странице принятых заказов вы найдёт в <a href='help.php?section=16' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                            ";
                            break;
                        case "8":
                            echo "
                                <h2 class='goodStyle'>Изменение стоимости товара в каталоге</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=14' class='noBorder'><span class='catalogueItemTextItalic'>Изменение стоимости товара в каталоге</span></a></p>
                                <div class='helpBlock'><p>Для изменения стоимости товара необходимо нажать на его цену в каталоге. На месте цены <a href='pictures/help/catalogue_price.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>появится поле для ввода</span></a>, в котором уже будет указана стоимость в долларах.</p></div>
                                <div class='helpBlock'><p>Новую стоимость можно написать цифрами, либо изменить её при помощи стрелок в правой части поля ввода. Одно нажатие на стрелку изменяет цену на $0.001.</p></div>
                                <div class='helpBlock'><p>После того, как вы введёте новую стоимость, нажмите на любое место страницы вне поля ввода. Поле ввода исчезнет, а цена автоматически пересчитается в белорусские рубли согласно указанному в разделе управленя сайтом курсу.</p></div>
                            ";
                            break;
                        case "9":
                            echo "
                                <h2 class='goodStyle'>Авторизация</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=9' class='noBorder'><span class='catalogueItemTextItalic'>Авторизация</span></a></p>
                                <div class='helpBlock'><p><a href='pictures/help/auth.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Блок авторизации</span></a> представляет собой окно, расположенное поверх страницы сайта. Окно содержит поля ввода логина и пароля, а также ссылки на разделы регистрации и восстановления пароля. О разделах <a href='help.php?section=10' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>регистрации</span></a> и <a href='help.php?section=11' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>восстановления пароля</span></a> вы можете прочитать в соответствующих ветках.</p></div>
                                <div class='helpBlock'><p>Попасть в раздел авторизации можно путём нажатия на <a href='pictures/help/header.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>иконку в меню сайта</span></a>, находящуюся между блоком с названиями разделов и блоком поиска.</p></div>
                                <div class='helpBlock'><p>Авторизация необходима для оформления онлайн-заявок.</p></div>
                                <div class='helpBlock'><p>Для авторизации введите свой логин и пароль и  кнопку <b>\"Войти\"</b>.</p></div>
                            ";
                            break;
                        case "10":
                            echo "
                                <h2 class='goodStyle'>Регистрация нового пользователя</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=10' class='noBorder'><span class='catalogueItemTextItalic'>Регистрация</span></a></p>
                                <div class='helpBlock'>
                                    <p>Раздел регистрации нового пользователя представляет собой окно, расположенное поверх страницы сайт, разделённый на два блока:</p>
                                    <ol>
                                        <li><a href='pictures/help/reg_org.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Регистрация для организаций</a></li>
                                        <li><a href='pictures/help/reg_person.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Регистрация для физических лиц</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>Попасть в раздел регистрации можно путём нажатия на <a href='pictures/help/header.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>иконку в меню сайта</span></a>, находящуюся между блоком с названиями разделов и блоком поиска. Далее необходимо нажать на надпись <b>\"Ещё не зарегистрированы?\"</b> внизу появившегося <a href='pictures/help/auth.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>блока авторизации</span></a>.</p></div>
                                <div class='helpBlock'><p>Для совершения регистрации на сайта сначала необходимо выбрать тип, который для вас подходит: <b>организация</b> или <b>физическое лицо</b>.</p></div>
                                <div class='helpBlock'><p>В поле ввода логина необходимо ввести свой логин, который может состоять из латинских букв, цифр, символов \"тире\"<b>-</b>, нижнего подчёркивания <b>_</b> и точки <b>.</b> Минимальная длина логина: 3 символа. Если выбранный вами логин уже занят, система сообщит вам об этом, изменив цвет поля ввода логина на красный.</p></div>
                                <div class='helpBlock'><p>Пароль должен состоять минимум из 5 символов.</p></div>
                                <div class='helpBlock'><p>Необходимо ввести рабочий e-mail адрес, так как на него будет отправлено письмо с подтверждением регистрации. Без подтверждения ваша регистрация не будет считаться действительной. Если ваш электронный адрес уже был указан при регистрации, система сообщит вам об этом, изменив цвет поля ввода e-mail адреса на красный.</p></div>
                                <div class='helpBlock'><p>Если вы выбрали тип пользователя <b>\"Организация\"</b> вам будет необходимо ввести название организации, которую вы представляете. Если ваша организация уже зарегистрирована, система сообщит вам об этом, изменив цвет поля ввода логина на красный.</p></div>
                                <div class='helpBlock'><p>В поле для ввода контактного лица, укажите имя того человека, с кем мы будем связываться в случае оформления онлайн-заявки.</p></div>
                                <div class='helpBlock'><p>Номер телефона желательно указывать в международном формате: +375(XX)XXXXXXX со всеми кодами. Это облегчит нам работу с вами. Если введённый вами номер телефона уже был указан при регистрации, система сообщит вам об этом, изменив цвет поля ввода логина на красный.</p></div>
                                <div class='helpBlock'><p>После ввода всей информации нажмите на кнопку <b>\"Зарегистрироваться\"</b>.</p></div>
                                <div class='helpBlock'><p>Если всё было заполнено верно, вы увидите <a href='pictures/help/reg_1.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>окно</span></a>, сообщающее, что первый этап регистрации успешно завершён. После этого необходимо зайти в вашу электронную почту и перейти по ссылке из письма, чтобы активировать аккаунт. После активации вы увидите такое <a href='pictures/help/reg_2.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>окно</span></a>. Это значит, что регистрация успешно завершена и вы теперь можете оставлять онлайн-заявки.</p></div>
                            ";
                            break;
                        case "11":
                            echo "
                                <h2 class='goodStyle'>Восстановление пароля</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=11' class='noBorder'><span class='catalogueItemTextItalic'>Восстановление пароля</span></a></p>
                                <div class='helpBlock'><p><a href='pictures/help/recovery.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Блок восстановления пароля</span></a> представляет собой окно, расположенное поверх страницы сайта.</p></div>
                                <div class='helpBlock'><p>Попасть в раздел регистрации можно путём нажатия на <a href='pictures/help/header.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>иконку в меню сайта</span></a>, находящуюся между блоком с названиями разделов и блоком поиска. Далее необходимо нажать на надпись <b>\"Забыли пароль?\"</b> внизу появившегося <a href='pictures/help/auth.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>блока авторизации</span></a>.</p></div>
                                <div class='helpBlock'><p>Для восстановления забытого пароля вам необходимо ввести ваш логин или e-mail, указанный при регистрации, после чего на ваш электронный адрес придёт письмо со ссылкой, перейдя по которой для вас сгенерируется новый случайный пароль. Изменить его можно будет в <a href='help.php?section=6' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>личном кабинете</span></a> после <a href='help.php?section=9' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>авторизации</span></a>.</p></div>
                            ";
                            break;
                        case "12":
                            echo "
                                <h2 class='goodStyle'>Основные положения ценовой политики</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=12' class='noBorder'><span class='catalogueItemTextItalic'>Основные положения ценовой политики</span></a></p>
                                <div class='helpBlock'><p>Цены на сайте <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>Аргос-ФМ</span></a> отображаются в белорусских рублях, однако они привязаны к курсу доллара, указанном в <a href='settings.php?s=3' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>разделе управления сайтом</span></a>.</p></div>
                                <div class='helpBlock'><p>Процесс изменения курса доллара на сайте описан в <a href='help.php?section=20' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                                <div class='helpBlock'><p>После установки нового курса цены пересчитываются автоматически.</p></div>
                            ";
                            break;
                        case "13":
                            echo "
                                <h2 class='goodStyle'>Об изменении цен в оформленном заказе</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=13' class='noBorder'><span class='catalogueItemTextItalic'>Динамическое изменение цен в заказе</span></a></p>
                                <div class='helpBlock'><p>Поскольку <a href='help.php?section=12' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>цены на товары привязаны к курсу доллара, указанному в разделе управления сайтом</span></a>, сумма в белорусских рублях в оформленном заказе также будет изменяться, если по какой-либо причине заявка не была принята.</p></div>
                                <div class='helpBlock'><p>Исходя из вышеописанного, имеет смысл обработать все заявки до изменения курса доллара в разделе управления сайтом.</p></div>
                            ";
                            break;
                        case "14":
                            echo "
                                <h2 class='goodStyle'>Обработка заявок</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=14' class='noBorder'><span class='catalogueItemTextItalic'>Обработка заявок</span></a></p>
                                <div class='helpBlock'><p><b>Все заявки необходимо утверждать только после согласования с заказчиком.</b></p></div>
                                <div class='helpBlock'><p>Необработанные заявки находятся во вкладке \"<a href='pictures/help/orders.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Активные заявки</span></a>\" в <a href='order.php?s=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>разделе заказов</span></a>.</p></div>
                                <div class='helpBlock'><p>Каждая заявка располагается в отдельной строке таблицы, содержащей в себе ячейки с номером и датой заявки, с кнопками управления и информацией о заказчике.</p></div>
                                <div class='helpBlock'><p>Информацию о заказчике можно посмотреть, нажав на <a href='pictures/help/order_buttons.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>кнопку \"<b>Заказчик</b>\"</span></a>, расположенную в верхней правой части шапки заявки. После нажатия откроется <a href='pictures/help/order_person.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>окно с детальной информацией</span></a> о человеке и (если указана) организации.</p></div>
                                <div class='helpBlock'><p>Для детального просмотра заявки необходимо нажать на ячейку с её номером и датой подачи. После нажатия заявка развернётся и вы увидите <a href='pictures/help/orders_detailed.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>полный список товаров</span></a>, которые она в себе содержит.</p></div>
                                <div class='helpBlock'><p>Если после согласования с заказчиком заявка подтверждается, то необходимо нажать на <a href='pictures/help/order_buttons.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>кнопку \"<b>Принять</b>\"</span></a>. Иначе — на <a href='pictures/help/order_buttons.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>кнопку \"<b>Отклонить</b>\"</span></a>.</p></div>
                            ";
                            break;
                        case "15":
                            echo "
                                <h2 class='goodStyle'>Обработка заявок</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=15' class='noBorder'><span class='catalogueItemTextItalic'>Обработка заявок</span></a></p>
                                <div class='helpBlock'><p>Каждый заказ содержит в себе группы товаров. Группа товаров состоит из фотографии, названия, описание, цены за единицу, цены за всю группу данного товара и количества. В ещё не принятых заказах количество товаров в группах можно изменять, нажав на количество. После этого появится <a href='pictures/help/orders_detailed_q.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>поле для ввода</span></a>. После того, как необходимое количество будет установлено, нажмите на любое место страницы вне поля ввода. Поле заменится строкой, а цена за группу и общая стоимость заказа автоматически пересчитаются.</p></div>
                                <div class='helpBlock'><p>Любую группу товаров можно удалить из неприянтого заказа. Для этого необходимо нажать на крест в верхней правой части группы товаров. При этом общая стоимость заказа автоматически пересчитается.</p></div>
                            ";
                            break;
                        case "16":
                            echo "
                                <h2 class='goodStyle'>История заявок</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=16' class='noBorder'><span class='catalogueItemTextItalic'>История заявок</span></a></p>
                                <div class='helpBlock'><p>Историю заявок можно посмотреть во вкладке \"<a href='pictures/help/orders_history.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>История заказов</span></a>\" в <a href='order.php?s=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>разделе заказов</span></a>.</p></div>
                                <div class='helpBlock'><p>Любую заявку можно просмотреть детально. Как это сделать, описано в <a href='help.php?section=14' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>ветке помощи по оформлению заявки</span></a>.</p></div>
                                <div class='helpBlock'><p>Для просмотра всех заявок конкретного покупателя необходимо найти его и выбрать в <a href='pictures/help/orders_history_customers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>выпадающем списке</span></a>.</p></div>
                                <div class='helpBlock'><p>Если пользователь удалил свой аккаунт, история его заказов остаётся и в выпадающем списке её можно найти по названию его организации с префиксом \"<b>[удалён]</b>\".</p></div>
                            ";
                            break;
                        case "17":
                            echo "
                                <h2 class='goodStyle'>Добавление новости</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=17' class='noBorder'><span class='catalogueItemTextItalic'>Добавление новости</span></a></p>
                                <div class='helpBlock'>
                                    <p>Войти в раздел добавления новостей можно следующими способами:</p>
                                    <ol>
                                        <li>Через <a href='pictures/help/index_news_adm.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>блок новостей на главной странице</a></li>
                                        <li>Через <a href='pictures/help/news_adm.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>страницу новостей</a></li>
                                        <li>Через <a href='admin/admin.php?section=users&action=addNews' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>раздел в панели администрирования</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>Раздел добавления новости выгляит <a href='pictures/help/news_add.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>так</span></a>.</p></div>
                                <div class='helpBlock'><p>Для добавления новости необходимо ввести заголовок, краткое описание и текст. При помощи встроенного текстового редактора можно форматиповать текст, вставлять изображения, изменять выравнивание текста и т.д.</p></div>
                            ";
                            break;
                        case "18":
                            echo "
                                <h2 class='goodStyle'>Редактирование новости</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=18' class='noBorder'><span class='catalogueItemTextItalic'>Редактирование новости</span></a></p>
                                <div class='helpBlock'>
                                    <p>Перейти к редактированию новости можно двумя способами:</p>
                                    <ol>
                                        <li>Через <a href='pictures/help/news_text.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>страницу с текстом новости.</a> Для редактирования необходимо нажать на иконку карандаша в верхней правой части блока с новостью.</li>
                                        <li>Через <a href='pictures/help/admin_news.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>раздел \"<b>Новости</b>\" в панели администрирования</a>. Для редактирования необходимо нажать на иконку справа отс соответствующей новости.</li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>После нажатия откроется <a href='pictures/help/admin_news_edit.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>раздел редактирования новости</span></a>.</p></div>
                                <div class='helpBlock'><p>Редактирование новости происходит по тем же правилам, что и добавление. Прочитать об этом можно в <a href='help.php?section=17' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                                <div class='helpBlock'><p>При необходимости новости можно удалять. Для этого нажмите на крестик в верхней правой части <a href='pictures/help/news_text.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>страницы с текстом новости</span></a> или на крестик справа от соответствующей новости в <a href='pictures/help/admin_news.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>разделе новостей в панели администрирования</span></a>. После удаления новость невозможно будет восстановить.</p></div>
                            ";
                            break;
                        case "19":
                            echo "
                                <h2 class='goodStyle'>Поиск новостей</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=19' class='noBorder'><span class='catalogueItemTextItalic'>Поиск новостей</span></a></p>
                                <div class='helpBlock'><p>Поиск новостей может осуществляться по дате при помощи <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>календаря</span></a> в верхней правой части страницы и <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>панели поиска</span></a>.</p></div>
                                <div class='helpBlock'><p>Поиск по дате осуществляется путём выбора даты в календаре. Если в определённый день были публикации, дата в календаре будет написана <a href='pictures/help/news_calendar_date.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>красным цветом на сером фоне</span></a>. При выборе даты <a href='pictures/help/news_date.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>будут показаны новости</span></a>, написанные в этот день. Под календарём появится кнопка <b>\"Сбросить дату\"</b>, которая необходима для возврата к списку всех новостей.</p></div>
                                <div class='helpBlock'><p>Для поиска новости по заголовки, котороткому описанию или тексту новости, начните набирать текст <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>в поле для поиска</span></a>. В процессе набора вам будут показаны найденные совпадения <a href='pictures/help/news_search.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>в выпадающем блоке</span></a>. Если совпадений не будет найдено, вы увидите надпись <b>\"К сожалению, ничего похожего не найдено\"</b>. Чтобы закрыть выпадающий блок, нажмите на любое место на экране вне самого блока.</p></div>
                                <div class='helpBlock'><p>Для прочтения новости нажмите на блок с её заголовком и коротким описанием в общем списке новостей или в выпадающем блоке поиска.</p></div>
                                <div class='helpBlock'><p>Кроме того, новости можно искать при помощи панели поиска в <a href='pictures/help/admin_news.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>разделе новостей в панели администрирования</span></a>.</p></div>
                            ";
                            break;
                        case "20":
                            echo "
                                <h2 class='goodStyle'>Изменение курса доллара</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=20' class='noBorder'><span class='catalogueItemTextItalic'>Изменение курса доллара</span></a></p>
                                <div class='helpBlock'><p>Для изменения курса доллара нееобходимо перейти во <a href='pictures/help/settings_3_adm.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>вкладку \"<b>Управление сайтом</b>\"</span></a>, которая находится в <a href='settings.php?s=3' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>личном кабинете</span></a>.</p></div>
                                <div class='helpBlock'><p>Для установки нового курса введите новое значение в поле для ввода и нажмите на кнопку \"<b>Внести изменения</b>\". Цены в каталоге будут пересчитаны автоматически.</p></div>
                            ";
                            break;
                        case "21":
                            echo "
                                <h2 class='goodStyle'>Редактирование персональных данных</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=21' class='noBorder'><span class='catalogueItemTextItalic'>Редактирование персональных данных</span></a></p>
                                <div class='helpBlock'><p>Раздел редактирования личных данных находится в <a href='settings.php?s=1' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>личном кабинете</span></a>.</p></div>
                                <div class='helpBlock'><p>Администратору изменять свои личные данные смысла нет, потому что их никто не видит. Изначально все  поля пустые.</p></div>
                            ";
                            break;
                        case "22":
                            echo "
                                <h2 class='goodStyle'>Изменение пароля</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=22' class='noBorder'><span class='catalogueItemTextItalic'>Изменение пароля</span></a></p>
                                <div class='helpBlock'><p>Раздел изменения пароля находится в <a href='settings.php?s=2' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>личном кабинете</span></a>.</p></div>
                                <div class='helpBlock'><p>Без согласования пароль лучше не менять.</p></div>
                                <div class='helpBlock'><p>При изменении пароля на сайте также изменится пароль в панели администрирования.</p></div>
                            ";
                            break;
                        case "23":
                            echo "
                                <h2 class='goodStyle'>Вход в панель администрирования</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=23' class='noBorder'><span class='catalogueItemTextItalic'>Вход в панель администрирования</span></a></p>
                                <div class='helpBlock'><p>Войти в панель администрирования можно через личный кабинет (вкладка \"Управление сайтом\"). Там в <a href='pictures/help/settings_3_adm.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>нижней части блока</span></a> находится кнопка \"<b>Войти в панель администрирования</b>\", а также по прямой ссылке: <a href='http://argos-fm.by/admin/' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>http://argos-fm.by/admin/</span></a></p></div>
                                <div class='helpBlock'><p>Вход в панель администрирования представляет собой <a href='pictures/help/admin_enter.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>окно с полями для ввода логина и пароля</span></a>.</p></div>
                                <div class='helpBlock'><p>Введите логин и пароль администратора в соответствующие поля ввода и нажмите кнопку \"<b>Войти</b>\".</p></div>
                            ";
                            break;
                        case "24":
                            echo "
                                <h2 class='goodStyle'>Структура панели администрирования</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=24' class='noBorder'><span class='catalogueItemTextItalic'>Структура панели администрирования</span></a></p>
                                <div class='helpBlock'>
                                    <p>Панель администрирования состоит из 11 основных модулей с нескольих вспомогательных:</p>
                                    <ol>
                                        <li><a href='pictures/help/admin_main.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Главная страница</a></li>
                                        <li>
                                            Раздел управления товарами:
                                            <ol>
                                                <li><a href='pictures/help/admin_goods_add.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Добавление товаров</a>. Помощь по разделу добавления товаров ищите в <a href='help.php?section=25' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</li>
                                                <li><a href='pictures/help/admin_goods_edit.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp3\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'>Редактирование товаров</a>. Помощь по разделу редактирования товаров ищите в <a href='help.php?section=26' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</li>
                                                <li><a href='pictures/help/admin_goods_delete.jpg' class='noBorder' rel='shadowbox' id='hlp4' onmouseover='helpTextB(1, \"hlp4\")' onmouseout='helpTextB(0, \"hlp4\")' style='color: #008fe1;'>Удаление товаров</a>. Помощь по разделу удаления товаров ищите в <a href='help.php?section=27' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</li>
                                            </ol>
                                        </li>
                                        <li>
                                            Управления разделами:
                                            <ol>
                                                <li><a href='pictures/help/admin_categories_add.jpg' class='noBorder' rel='shadowbox' id='hlp5' onmouseover='helpTextB(1, \"hlp5\")' onmouseout='helpTextB(0, \"hlp5\")' style='color: #008fe1;'>Добавление разделов</a>. Помощь по разделу добавления разделов ищите в <a href='help.php?section=28' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</li>
                                                <li><a href='pictures/help/admin_categories_edit.jpg' class='noBorder' rel='shadowbox' id='hlp6' onmouseover='helpTextB(1, \"hlp6\")' onmouseout='helpTextB(0, \"hlp6\")' style='color: #008fe1;'>Редактирование разделов</a>. Помощь по разделу редактирования разделов ищите в <a href='help.php?section=29' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</li>
                                                <li><a href='pictures/help/admin_categories_delete.jpg' class='noBorder' rel='shadowbox' id='hlp7' onmouseover='helpTextB(1, \"hlp7\")' onmouseout='helpTextB(0, \"hlp7\")' style='color: #008fe1;'>Удаление разделов</a>. Помощь по удалению разделов ищите в <a href='help.php?section=30' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</li>
                                            </ol>
                                        </li>
                                        <li><a href='pictures/help/admin_email.jpg' class='noBorder' rel='shadowbox' id='hlp8' onmouseover='helpTextB(1, \"hlp8\")' onmouseout='helpTextB(0, \"hlp8\")' style='color: #008fe1;'>Центр e-mail рассылок</a>. Помощь по e-mail рассылкам ищите в <a href='help.php?section=31' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</li>
                                        <li><a href='pictures/help/admin_clients.jpg' class='noBorder' rel='shadowbox' id='hlp9' onmouseover='helpTextB(1, \"hlp9\")' onmouseout='helpTextB(0, \"hlp9\")' style='color: #008fe1;'>Клиентская база</a>. Помощь по структуре клиентской базы ищите в <a href='help.php?section=33' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</li>
                                        <li><a href='pictures/help/admin_users.jpg' class='noBorder' rel='shadowbox' id='hlp10' onmouseover='helpTextB(1, \"hlp10\")' onmouseout='helpTextB(0, \"hlp10\")' style='color: #008fe1;'>Раздел управления пользователями</a>. Помощь по разделу управления пользователями ищите в <a href='help.php?section=40' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</li>
                                        <li><a href='pictures/help/admin_news.jpg' class='noBorder' rel='shadowbox' id='hlp11' onmouseover='helpTextB(1, \"hlp11\")' onmouseout='helpTextB(0, \"hlp11\")' style='color: #008fe1;'>Раздел управления новостями</a>. Помощь по разделу управлениями новостями ищите в <a href='help.php?section=42' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</li>
                                    </ol>
                                </div>
                                <div class='helpBlock'>
                                    <p>Главная страница панели управления состоит из:</p>
                                    <ol>
                                        <li><a href='pictures/help/admin_menu.jpg' class='noBorder' rel='shadowbox' id='hlp12' onmouseover='helpTextB(1, \"hlp12\")' onmouseout='helpTextB(0, \"hlp12\")' style='color: #008fe1;'>Панели навигации</a></li>
                                        <li><a href='pictures/help/admin_main.jpg' class='noBorder' rel='shadowbox' id='hlp13' onmouseover='helpTextB(1, \"hlp13\")' onmouseout='helpTextB(0, \"hlp13\")' style='color: #008fe1;'>Блока с основной информацией</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>В <a href='pictures/help/admin_main.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>блоке с основной информацией</span></a> продублированы ссылки из <a href='pictures/help/admin_menu.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>меню</span></a>.</p></div>
                            ";
                            break;
                        case "25":
                            echo "
                                <h2 class='goodStyle'>Добавление товара в каталог</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=25' class='noBorder'><span class='catalogueItemTextItalic'>Добавление товара в каталог</span></a></p>
                                <div class='helpBlock'><p>Для добавления товара необходимо зайти в <a href='pictures/help/admin_goods_add.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>раздел добавления товаров</span></a>; выбрать его будующее расположение, то есть все категории; ввести название; выбрать артикул или нажать на ссылку \"<b>установить первый незанятый</b>\"; указать цену в долларах, написав цену вручную, либо при помощи стрелок в правой части поля ввода цены; установить позицию в разделе (по умолчнию товар ставится в конец раздела); ввести описание и выбрать фотографию и, если есть, чертёж товара.</p></div>
                                <div class='helpBlock'><p>Размер фотографии не имеет большого значения. Однако, желательно, чтобы размер был не менее, чем 100 пикселей по ширине и высоте. Обрезка фотографии и изменение пропорций происходит автоматически.</p></div>
                                <div class='helpBlock'><p>При выборе фотографии товара желательно выбирать квадратную, чтобы избежать возможных неточностей в обрезке и масштабировании.</p></div>
                            ";
                            break;
                        case "26":
                            echo "
                                <h2 class='goodStyle'>Редактирование товара</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=26' class='noBorder'><span class='catalogueItemTextItalic'>Редактирование товара</span></a></p>
                                <div class='helpBlock'><p>Для редактирование товара необходимо зайти в <a href='pictures/help/admin_goods_edit.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>раздел редактирования товаров</span></a>; выбрать его расположение, то есть все категории, изменить текст в необходимых местах и, если необходимо, выбрать новую фотографию и чертёж. Если не выбирать новые фотографии, старые удалены не будут.</p></div>
                                <div class='helpBlock'><p>Размер фотографии не имеет большого значения. Однако, желательно, чтобы размер был не менее, чем 100 пикселей по ширине и высоте. Обрезка фотографии и изменение пропорций происходит автоматически.</p></div>
                                <div class='helpBlock'><p>При выборе фотографии товара желательно выбирать квадратную, чтобы избежать возможных неточностей в обрезке и масштабировании.</p></div>
                            ";
                            break;
                        case "27":
                            echo "
                                <h2 class='goodStyle'>Удаление товара</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=27' class='noBorder'><span class='catalogueItemTextItalic'>Удаление товара</span></a></p>
                                <div class='helpBlock'><p>Для удаления товара необходимо зайти в <a href='pictures/help/admin_goods_delete.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>раздел удаления товаров</span></a>, выбрать его расположение, то есть все категории и нажать на кнопку \"<b>Удалить</b>\".</p></div>
                                <div class='helpBlock'><p>После удаления товар восстановить будет невозможно. Ошибочно удлённые товары придётся добавлять заново.</p></div>
                            ";
                            break;
                        case "28":
                            echo "
                                <h2 class='goodStyle'>Добавление нового раздела</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=28' class='noBorder'><span class='catalogueItemTextItalic'>Добавление нового раздела</span></a></p>
                                <div class='helpBlock'><p>Для добавления нового раздела необходимо зайти в <a href='pictures/help/admin_categories_add.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>секцию добавления новых разделов</span></a> и выбрать его расположение, то есть все категории.</p></div>
                                <div class='helpBlock'><p>Если добавляется категория первого уровня, то помимо названия необходимо выбрать чёрную и красную пиктограммы. Их размер должен равняться строго 21x21 пиксель. Если необходимо, чтобы добавляемая категория впоследствии не содержала разделов и подразделов, то есть при выборе её в каталоге товары выводились сразу без выбора разделов и подразделов, необходимо поставить галочку \"<b>Категория без разедлов</b>\".</p></div>
                                <div class='helpBlock'><p>Если добавляется раздел или подраздел, необходимо ввести только его название.</p></div>
                            ";
                            break;
                        case "29":
                            echo "
                                <h2 class='goodStyle'>Редактирование раздела</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=29' class='noBorder'><span class='catalogueItemTextItalic'>Редактирование раздела</span></a></p>
                                <div class='helpBlock'><p>Для редактирования раздела необходимо зайти в <a href='pictures/help/admin_categories_edit.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>секцию редактирования разделов</span></a> и выбрать его расположение, то есть все категории.</p></div>
                                <div class='helpBlock'><p>Если редактируется категория первого уровня, то помимо изменения названия можно изменить её иконик. Для этого необходимо выбрать чёрную и красную пиктограммы. Их размер должен равняться строго 21x21 пиксель.</p></div>
                                <div class='helpBlock'><p>Если редактируется раздел или подраздел, необходимо ввести только его новое название.</p></div>
                            ";
                            break;
                        case "30":
                            echo "
                                <h2 class='goodStyle'>Удаление раздела</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=30' class='noBorder'><span class='catalogueItemTextItalic'>Удаление раздела</span></a></p>
                                <div class='helpBlock'><p>Для удаления раздела необходимо зайти в <a href='pictures/help/admin_categories_delete.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>секцию удаления разделов</span></a> и выбрать его расположение, то есть все категории.</p></div>
                                <div class='helpBlock'><p>Также при удалении категории, раздела или подраздела желательно ставить галочку \"<b>Удалить раздел вместе с товарами</b>\", поскольку эти товары останутся неучтёнными — их нельзя будет увидеть в каталоге, поскольку у них не будет своего раздела. Оставлять товары при удалении разделов нужно только в том случае, если позже они вручную будут перенесены в другой раздел напрямую в базе данных.</p></div>
                            ";
                            break;
                        case "31":
                            echo "
                                <h2 class='goodStyle'>Отправление e-mail рассылок</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=31' class='noBorder'><span class='catalogueItemTextItalic'>Отправление e-mail рассылок</span></a></p>
                                <div class='helpBlock'><p>Рассылки можно отправлять как <a href='pictures/help/admin_email_all.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>всем пользователям</span></a> сразу, так и <a href='pictures/help/admin_email_group.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>по областям</span></a>, и <a href='pictures/help/admin_email_one.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>одному клиенту</span></a>.</p></div>
                                <div class='helpBlock'><p>Для отправления рассылки всем клиентам необходимо выбрать пункт \"<b>Отправить по всем адресам из клиентской базы</b>\", написать тему рассылки и её текст. Если необходимо, можно добавить неограниченное число файлов любых типов, нажав на кнопку \"<b>Выбрать файлы</b>\".</p></div>
                                <div class='helpBlock'><p>Для отправления рассылки всем клиентам из определённой области необходимо выбрать пункт \"<b>Отправить всем из выбранной области</b>\". Ниже появится <a href='pictures/help/admin_email_group_list.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>выпадающий список с областями</span></a>. По умолчанию выбрана Могилёвская область. После выбора области необхоимо написать тему рассылки и её текст. Если необходимо, можно добавить неограниченное число файлов любых типов, нажав на кнопку \"<b>Выбрать файлы</b>\".</p></div>
                                <div class='helpBlock'><p>Для отправления рассылки (письма) одному клиентам необходимо выбрать пункт \"<b>Отправить одному клиенту</b>\". Ниже появится <a href='pictures/help/admin_email_one_field.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>поле ввода email-адреса</span></a>. После ввода адреса необходимо написать тему рассылки и её текст. Если необходимо, можно добавить неограниченное число файлов любых типов, нажав на кнопку \"<b>Выбрать файлы</b>\".</p></div>
                                <div class='helpBlock'><p>Отправлять рассылки следует аккуратно, потому что после нажатия кнопки \"<b>Отправить</b>\" процесс уже будет отменить невозможно.</p></div>
                            ";
                            break;
                        case "32":
                            echo "
                                <h2 class='goodStyle'>История e-mail рассылок</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=32' class='noBorder'><span class='catalogueItemTextItalic'>История e-mail рассылок</span></a></p>
                                <div class='helpBlock'><p>В раздел истории рассылок можно попасть через <a href='admin/admin.php?section=users&action=mail' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>страницу отправления рассылок</span></a>. На ней необходимо нажать на кнопку \"<b>История рассылок</b>\" в верхнем правом углу.</p></div>
                                <div class='helpBlock'><p>История рассылок <a href='pictures/help/admin_email_history.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>представляет собой таблицу</span></a>, в которой указаны тема рассылки, её текст, получатели и дата отправления.</p></div>
                                <div class='helpBlock'><p>Посмотреть текст рассылки можно нажав на строку \"<b>Текст рассылки</b>\". После нажатия откроется <a href='pictures/help/admin_email_history_text.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>окно с текстом</span></a>.</p></div>
                            ";
                            break;
                        case "33":
                            echo "
                                <h2 class='goodStyle'>Структура страницы клиентской базы</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=33' class='noBorder'><span class='catalogueItemTextItalic'>Структура страницы клиентской базы</span></a></p>
                                <div class='helpBlock'>
                                    <p><a href='pictures/help/admin_clients.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Страница управления клиентской базой</span></a> состоит из 5-ти блоков:</p>
                                    <ol>
                                        <li><a href='pictures/help/admin_clients_switch.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Блок переключения</a> между активными и отписавшимися от рассылки клиентами. Подробнее в <a href='help.php?section=39' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>этой ветке</span></a>.</li>
                                        <li><a href='pictures/help/admin_clients_search_letter.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Блок выбора адресов</a> по первому символу. Подробнее в <a href='help.php?section=37' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>этой ветке</span></a>.</li>
                                        <li><a href='pictures/help/admin_clients_add.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp3\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'>Блок добавления новой записи</a> в клиентскую базу. Подробнее в <a href='help.php?section=34' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>этой ветке</span></a>.</li>
                                        <li><a href='pictures/help/admin_clients_search.jpg' class='noBorder' rel='shadowbox' id='hlp4' onmouseover='helpTextB(1, \"hlp4\")' onmouseout='helpTextB(0, \"hlp4\")' style='color: #008fe1;'>Блок быстрого поиска адреса</a>. Подробнее в <a href='help.php?section=37' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>этой ветке</span></a>.</li>
                                        <li><a href='pictures/help/admin_clients_table.jpg' class='noBorder' rel='shadowbox' id='hlp5' onmouseover='helpTextB(1, \"hlp5\")' onmouseout='helpTextB(0, \"hlp5\")' style='color: #008fe1;'>Таблица с записями из клиентской базы с меню постраничной навигации</a>. О функциях таблицы читайте в <a href='help.php?section=35' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>этой</span></a> и <a href='help.php?section=36' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>этой ветке</span></a>.</li>
                                    </ol>
                                </div>
                            ";
                            break;
                        case "34":
                            echo "
                                <h2 class='goodStyle'>Добавление записи в клиентскую базу</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=34' class='noBorder'><span class='catalogueItemTextItalic'>Добавление записи в клиентскую базу</span></a></p>
                                <div class='helpBlock'><p>Для добавления новой записи в клиентскую базу необходимо ввести корректный email-адрес клиента, его имя или название организации (можно добавлять и свои пометки), а также из <a href='pictures/help/admin_clients_add_list.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>выпадающего списка</span></a> выбрать область, в которой он находится. По умолчанию выбрана группа \"<b>Не определено</b>\". После ввода всей инофрмации необходимо нажать на кнопку \"<b>Добавить</b>\", после чего, если вся информация была введена корректно, появится <a href='pictures/help/admin_clients_add_success.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>сообщение</span></a> об успешном добавлении новой записи.</p></div>
                                <div class='helpBlock'><p>В момент ввода email-адреса адреса происходит мгновенная валидация. Если введённый вами адрес не корректен или уже существует в дазе данных, поле ввода окрасится в красный цвет. Как только ошибка ввода будет исправлена или будет введён адрес, которого ещё нет в базе данных, поле для ввода примет нормальный вид.</p></div>
                            ";
                            break;
                        case "35":
                            echo "
                                <h2 class='goodStyle'>Редактирование записи из клиентской базы</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=35' class='noBorder'><span class='catalogueItemTextItalic'>Редактирование записи из клиентской базы</span></a></p>
                                <div class='helpBlock'><p>Записи из клиентской базы редактируются в <a href='pictures/help/admin_clients_table.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>таблице</span></a>, которая находится на <a href='admin/admin.php?section=users&action=maillist&active=true&p=1' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>странице управления клиентской базой</span></a>.</p></div>
                                <div class='helpBlock'><p>Редактировать можно email-адрес, имя или название организации и выбирать область.</p></div>
                                <div class='helpBlock'><p>Для редактирования email-адреса нужно нажать на адрес, после чего на его месте появится <a href='pictures/help/admin_clients_edit_address.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>поле для ввода</span></a> с текущим email-адресом. В момент ввода email-адреса адреса происходит мгновенная валидация. Если введённый вами адрес не корректен или уже существует в базе данных, <a href='pictures/help/admin_clients_edit_address_incorrect.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>поле ввода окрасится в красный цвет</span></a>. Как только ошибка ввода будет исправлена или будет введён адрес, которого ещё нет в базе данных, поле для ввода примет нормальный вид. Для сохранения результата щёлкните мышью в любое место на экране вне поля ввода, и, если формат ввода был верным, поле для ввода исчезнет, а на его месте появится введённый вами email-адрес, иначе поле для ввода останется и будет подсвечено красным цветом.</p></div>
                                <div class='helpBlock'><p>Для редактирования имени клиента или названия его организации необходимо нажать на соответствующую ему ячейку, после чего появится <a href='pictures/help/admin_clients_edit_name.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>поле для ввода</span></a>, содержащее в себе имя, которое было введено ранее. Если ячейка была пустой, поле для ввода будет пустым. Если вы оставите поле пустым, оно <a href='pictures/help/admin_clients_edit_name_incorrect.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>подсветится красным цветом</span></a> и не даст сохранить результат. Для сохранения необъодимо щёлкнуть мышью на любое место на экране вне поля ввода, после чего поле исчезнет и на его месте появится введённая вами информация.</p></div>
                                <div class='helpBlock'><p>Для редактирования области необходимо нажать на название области, после чего на его месте появится <a href='pictures/help/admin_clients_edit_location.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>выпадающий список</span></a>, который содержит в себе все области, указанные в базе данных и доступные для выбора. По умолчаниюв нём будет выбрана та область, которая на данный момент указана у выбранного клиента. Для изменения области выберите необходимую, после чего выпадающий список исчезнет, а на его месте появится название выбранной вами области.</p></div>
                            ";
                            break;
                        case "36":
                            echo "
                                <h2 class='goodStyle'>Удаление записи из клиентской базы</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=36' class='noBorder'><span class='catalogueItemTextItalic'>Редактирование записи из клиентской базы</span></a></p>
                                <div class='helpBlock'><p>Для удаления записи из клиентской базы необходимо нажать на крестик в соответствующей строке <a href='pictures/help/admin_clients_table.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>таблицы клиентов</span></a>.</p></div>
                                <div class='helpBlock'><p>Обратите внимание, что после удаления восстановить запись будет уже невозможно.</p></div>
                            ";
                            break;
                        case "37":
                            echo "
                                <h2 class='goodStyle'>Быстрый поиск адреса в клиентской базе</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=37' class='noBorder'><span class='catalogueItemTextItalic'>Быстрый поиск адреса в клиентской базе</span></a></p>
                                <div class='helpBlock'><p>Чтобы проверить, существует ли email-адрес в клиентской базе, можно написать его в <a href='pictures/help/admin_clients_search.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>поле для поиска</span></a>. Если адрес есть, то вы увидите его в появившемся блоке. Если нет, то в появившемся блоке будет строка \"<b>К сожалению, ничего не найдено.</b>\"</p></div>
                                <div class='helpBlock'><p>Если нажать на адрес в появившемся блоке, его можно редактировать. О редактировании адресов читайте в <a href='help.php?section=35' class='noBorder' target='_blank'><span class='basicRed' style='font-size: 14px;'>этой ветке</span></a>.</p></div>
                                <div class='helpBlock'><p>Адреса можно искать по первому символу. Для этого выберите в <a href='pictures/help/admin_clients_search_letter.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>выпадающем списке</span></a> соответствующий символ. После выбора <a href='pictures/help/admin_clients_table.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>таблица с клиентской базой</span></a> автоматически перестроится.</p></div>
                            ";
                            break;
                        case "38":
                            break;
                        case "39":
                            break;
                        case "40":
                            break;
                        case "41":
                            break;
                        case "42":
                            break;
                        default:
                            echo "
                                <div class='helpBlock'><p>Такой страницы не существует. Вернитесь к <a href='help.php' class='noBorder'><span class='basicRed' style='font-size: 14px;'>списку разделов</span>.</p></div>
                            ";
                            break;
                    }
                }
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
                            <li><a href='help.php?section=7' class='noBorder' id='hlp7' onmouseover='helpText(1, \"hlp7\")' onmouseout='helpText(0, \"hlp7\")'>Корзина и страница заказов</a></li>
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
                            <li><a href='help.php?section=8' class='noBorder' id='hlp18' onmouseover='helpText(1, \"hlp18\")' onmouseout='helpText(0, \"hlp18\")'>Удаление аккаунта</a></li>
                        </ol>
                    ";
                } else {
                    switch($_REQUEST['section']) {
                        case "1":
                            echo "
                                <h2 class='goodStyle'>Структура главной страницы</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=1' class='noBorder'><span class='catalogueItemTextItalic'>Главная страница</span></a></p>
                                <div class='helpBlock'>
                                    <p>Главная страница сайта <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>Аргос-ФМ</span></a> состоит из трёх конструктивных блоков:</p>
                                    <ol>
                                        <li><a href='pictures/help/index.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Навигационный блок</a></li>
                                        <li><a href='pictures/help/index_news.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Блок последних новостей</a></li>
                                        <li><a href='pictures/help/index_partners.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp3\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'>Ссылки на сайты партнёров</a></li>
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
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=2' class='noBorder'><span class='catalogueItemTextItalic'>Каталог</span></a></p>
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
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=3' class='noBorder'><span class='catalogueItemTextItalic'>Новости</span></a></p>
                                <div class='helpBlock'><p>На странице новостей показаны все новости и коммерческие предложения, которые когда-либо были опубликованы. Показано 10 новостей на одной странице. Они отсортированы по дате публикации. Для просмотра более ранних публикаций, необходимо воспользоваться <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>панелью постраничной навигации</span></a>, расположенной внизу страницы.</p></div>
                                <div class='helpBlock'><p>Поиск новостей может осуществляться по дате при помощи <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>календаря</span></a> в верхней правой части страницы и <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>панели поиска</span></a>.</p></div>
                                <div class='helpBlock'><p>Поиск по дате осуществляется путём выбора даты в календаре. Если в определённый день были публикации, дата в календаре будет написана <a href='pictures/help/news_calendar_date.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>красным цветом на сером фоне</span></a>. При выборе даты <a href='pictures/help/news_date.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>будут показаны новости</span></a>, написанные в этот день. Под календарём появится кнопка <b>\"Сбросить дату\"</b>, которая необходима для возврата к списку всех новостей.</p></div>
                                <div class='helpBlock'><p>Для поиска новости по заголовки, котороткому описанию или тексту новости, начните набирать текст <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>в поле для поиска</span></a>. В процессе набора вам будут показаны найденные совпадения <a href='pictures/help/news_search.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>в выпадающем блоке</span></a>. Если совпадений не будет найдено, вы увидите надпись <b>\"К сожалению, ничего похожего не найдено\"</b>. Чтобы закрыть выпадающий блок, нажмите на любое место на экране вне самого блока.</p></div>
                                <div class='helpBlock'><p>Для прочтения новости нажмите на блок с её заголовком и коротким описанием в общем списке новостей или в выпадающем блоке поиска.</p></div>
                            ";
                            break;
                        case "4":
                            echo "
                                <h2 class='goodStyle'>Структура страницы с контактной информацией</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=4' class='noBorder'><span class='catalogueItemTextItalic'>Контактная информация</span></a></p>
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
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=5' class='noBorder'><span class='catalogueItemTextItalic'>Страница поиска</span></a></p>
                                <div class='helpBlock'><p>На странице поиска выводятся товары, название которых или артикул максимально похожи с введёнными вами данными. На странице <a href='pictures/help/search.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>отображено по 10 товаров</span></a>, если было найдено 10 или более совпадений. Для перехода на следующую страницу воспользуйтесь <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>блоком постраничной навигации</span></a>, расположенным внизу странцы.</p></div>
                                <div class='helpBlock'><p><a href='pictures/help/search_item.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Блок отдельного товара</span></a> на странице поиска состоит из панели навигации, фотографии, названия, описания, артикула, цены и чертежа.</p></div>
                                <div class='helpBlock'><p><a href='pictures/help/search_nav.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Панель навигации товара</span></a> показывает, в каких разделах данный товар расположен.</p></div>
                            ";
                            break;
                        case "6":
                            echo "
                                <h2 class='goodStyle'>Структура личного кабинета</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=6' class='noBorder'><span class='catalogueItemTextItalic'>Личный кабинет</span></a></p>
                                <div class='helpBlock'>
                                    <p>Личный кабинет состоит из трёх конструктивных блоков:</p>
                                    <ol>
                                        <li><a href='pictures/help/settings_1.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Основные настройки</a></li>
                                        <li><a href='pictures/help/settings_2.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Изменение пароля</a></li>
                                        <li><a href='pictures/help/settings_3.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp3\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'>Удаление аккаунта</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>Помощь по изменению личной информации читайте в <a href='help.php?section=16' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                                <div class='helpBlock'><p>Помощь по изменению пароля читайте в <a href='help.php?section=17' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                                <div class='helpBlock'><p>Помощь по удалению вашего аккаунта читайте в <a href='help.php?section=8' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                            ";
                            break;
                        case "7":
                                echo "
                                    <h2 class='goodStyle'>Структура страницы заказов</h2>
                                    <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=7' class='noBorder'><span class='catalogueItemTextItalic'>Страница заказов</span></a></p>
                                    <div class='helpBlock'>
                                        <p>Страница заказов состоит из двух конструктивных блоков:</p>
                                        <ol>
                                            <li><a href='pictures/help/order_basket.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Текущий заказ или Корзина</a></li>
                                            <li><a href='pictures/help/order_history.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>История заказов</a></li>
                                        </ol>
                                    </div>
                                    <div class='helpBlock'><p>На странице текущего заказа изображены все товары, которые вы добавили в коризну в <a href='catalogue.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>каталоге</span></a>. Под списком товаров находится общая сумма заказа в белорусских рублях. Подробно о функциях корзины читайте <a href='help.php?section=14' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>в соответствующей ветке</span></a>.</p></div>
                                    <div class='helpBlock'><p>На вкладке <b>\"История заказов\"</b> в скобках написано общее число совершённых вами заказов.</p></div>
                                    <div class='helpBlock'><p>
                                        Страница <b>\"История заказов\"</b> делится на два блока:
                                        <ol>
                                            <li style='color: #3f3f3f;'>Необработанные заказы</li>
                                            <li style='color: #3f3f3f;'>Обработанные заказы</li>
                                        </ol>
                                    </p></div>
                                    <div class='helpBlock'><p>В разделе необработанных заказов содержатся ваши заявки, которые уже были отправлены менеджерам на рассмотрение, но ещё не отданы на сборку.</p></div>
                                    <div class='helpBlock'><p>В разделе обработанных заказов содержится вся история ваших заявок. Обработанные заявки отображаются по 10 на странице. Для перехода на другую страницу, воспользуйтесь <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>блоком постраничной навигации</span></a>, расположенным внизу.</p></div>
                                ";
                            break;
                        case "9":
                            echo "
                                <h2 class='goodStyle'>Авторизация</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=9' class='noBorder'><span class='catalogueItemTextItalic'>Авторизация</span></a></p>
                                <div class='helpBlock'><p><a href='pictures/help/auth.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Блок авторизации</span></a> представляет собой окно, расположенное поверх страницы сайта. Окно содержит поля ввода логина и пароля, а также ссылки на разделы регистрации и восстановления пароля. О разделах <a href='help.php?section=10' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>регистрации</span></a> и <a href='help.php?section=11' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>восстановления пароля</span></a> вы можете прочитать в соответствующих ветках.</p></div>
                                <div class='helpBlock'><p>Попасть в раздел авторизации можно путём нажатия на <a href='pictures/help/header.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>иконку в меню сайта</span></a>, находящуюся между блоком с названиями разделов и блоком поиска.</p></div>
                                <div class='helpBlock'><p>Авторизация необходима для оформления онлайн-заявок.</p></div>
                                <div class='helpBlock'><p>Для авторизации введите свой логин и пароль и  кнопку <b>\"Войти\"</b>.</p></div>
                            ";
                            break;
                        case "10":
                            echo "
                                <h2 class='goodStyle'>Регистрация нового пользователя</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=10' class='noBorder'><span class='catalogueItemTextItalic'>Регистрация</span></a></p>
                                <div class='helpBlock'>
                                    <p>Раздел регистрации нового пользователя представляет собой окно, расположенное поверх страницы сайт, разделённый на два блока:</p>
                                    <ol>
                                        <li><a href='pictures/help/reg_org.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>Регистрация для организаций</a></li>
                                        <li><a href='pictures/help/reg_person.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>Регистрация для физических лиц</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>Попасть в раздел регистрации можно путём нажатия на <a href='pictures/help/header.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>иконку в меню сайта</span></a>, находящуюся между блоком с названиями разделов и блоком поиска. Далее необходимо нажать на надпись <b>\"Ещё не зарегистрированы?\"</b> внизу появившегося <a href='pictures/help/auth.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>блока авторизации</span></a>.</p></div>
                                <div class='helpBlock'><p>Для совершения регистрации на сайта сначала необходимо выбрать тип, который для вас подходит: <b>организация</b> или <b>физическое лицо</b>.</p></div>
                                <div class='helpBlock'><p>В поле ввода логина необходимо ввести свой логин, который может состоять из латинских букв, цифр, символов \"тире\"<b>-</b>, нижнего подчёркивания <b>_</b> и точки <b>.</b> Минимальная длина логина: 3 символа. Если выбранный вами логин уже занят, система сообщит вам об этом, изменив цвет поля ввода логина на красный.</p></div>
                                <div class='helpBlock'><p>Пароль должен состоять минимум из 5 символов.</p></div>
                                <div class='helpBlock'><p>Необходимо ввести рабочий e-mail адрес, так как на него будет отправлено письмо с подтверждением регистрации. Без подтверждения ваша регистрация не будет считаться действительной. Если ваш электронный адрес уже был указан при регистрации, система сообщит вам об этом, изменив цвет поля ввода e-mail адреса на красный.</p></div>
                                <div class='helpBlock'><p>Если вы выбрали тип пользователя <b>\"Организация\"</b> вам будет необходимо ввести название организации, которую вы представляете. Если ваша организация уже зарегистрирована, система сообщит вам об этом, изменив цвет поля ввода логина на красный.</p></div>
                                <div class='helpBlock'><p>В поле для ввода контактного лица, укажите имя того человека, с кем мы будем связываться в случае оформления онлайн-заявки.</p></div>
                                <div class='helpBlock'><p>Номер телефона желательно указывать в международном формате: +375(XX)XXXXXXX со всеми кодами. Это облегчит нам работу с вами. Если введённый вами номер телефона уже был указан при регистрации, система сообщит вам об этом, изменив цвет поля ввода логина на красный.</p></div>
                                <div class='helpBlock'><p>После ввода всей информации нажмите на кнопку <b>\"Зарегистрироваться\"</b>.</p></div>
                                <div class='helpBlock'><p>Если всё было заполнено верно, вы увидите <a href='pictures/help/reg_1.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>окно</span></a>, сообщающее, что первый этап регистрации успешно завершён. После этого необходимо зайти в вашу электронную почту и перейти по ссылке из письма, чтобы активировать аккаунт. После активации вы увидите такое <a href='pictures/help/reg_2.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>окно</span></a>. Это значит, что регистрация успешно завершена и вы теперь можете оставлять онлайн-заявки.</p></div>
                            ";
                            break;
                        case "11":
                            echo "
                                <h2 class='goodStyle'>Восстановление пароля</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=11' class='noBorder'><span class='catalogueItemTextItalic'>Восстановление пароля</span></a></p>
                                <div class='helpBlock'><p><a href='pictures/help/recovery.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Блок восстановления пароля</span></a> представляет собой окно, расположенное поверх страницы сайта.</p></div>
                                <div class='helpBlock'><p>Попасть в раздел регистрации можно путём нажатия на <a href='pictures/help/header.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>иконку в меню сайта</span></a>, находящуюся между блоком с названиями разделов и блоком поиска. Далее необходимо нажать на надпись <b>\"Забыли пароль?\"</b> внизу появившегося <a href='pictures/help/auth.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>блока авторизации</span></a>.</p></div>
                                <div class='helpBlock'><p>Для восстановления забытого пароля вам необходимо ввести ваш логин или e-mail, указанный при регистрации, после чего на ваш электронный адрес придёт письмо со ссылкой, перейдя по которой для вас сгенерируется новый случайный пароль. Изменить его можно будет в <a href='help.php?section=6' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>личном кабинете</span></a> после <a href='help.php?section=9' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>авторизации</span></a>.</p></div>
                            ";
                            break;
                        case "12":
                            echo "
                                <h2 class='goodStyle'>Основные положения ценовой политики</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=12' class='noBorder'><span class='catalogueItemTextItalic'>Основные положения ценовой политики</span></a></p>
                                <div class='helpBlock'><p>Цены на сайте <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>Аргос-ФМ</span></a> отображаются в белорусских рублях, однако они привязаны к официальным курсам валют Национального Банка Республики Беларусь.</p></div>
                                <div class='helpBlock'><p>Как правило, изменения в ценах происходят сразу после опубликования официальных курсов валют на сайте Национального Банка Республики Беларусь.</p></div>
                                <div class='helpBlock'><p>С изменениями официальных курсов будут меняться и цены в белорусских рублях в каталоге.</p></div>
                            ";
                            break;
                        case "13":
                            echo "
                                <h2 class='goodStyle'>Об изменении цен в оформленном заказе</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=13' class='noBorder'><span class='catalogueItemTextItalic'>Динамическое изменение цен в заказе</span></a></p>
                                <div class='helpBlock'><p>Поскольку <a href='help.php?section=12' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>цены на товары привязаны к официальным курсам валют Национального Банка Республики Беларусь</span></a>, сумма в белорусских рублях в оформленном заказе также может изменяться, если по какой-либо причине ваш заказ не был выполнен. Если новая цена вас не устроит, вы будете вправе отказаться от оформленного заказа без каких-либо штрафов либо оговорить с менеджером возможность приобретения товаров по цене, сформированной на момент оформления заказа.</p></div>
                                <div class='helpBlock'><p>Процент скидки от итоговой цены оговаривается напрямую с менеджером в зависимости от объёма заказа или от вашей личной скидки.</p></div>
                            ";
                            break;
                        case "14":
                            echo "
                                <h2 class='goodStyle'>Как оформить заказ</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=14' class='noBorder'><span class='catalogueItemTextItalic'>Оформление заказа</span></a></p>
                                <div class='helpBlock'><p>Для совершения заказа необходимо <a href='help.php?section=9' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>авторизоваться на сайте</span></a> и перейти в <a href='catalogue.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>каталог</span></a>. После того, как вы найдёте необходимый вам товар, <a href='pictures/help/good_basket.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>укажите его количество в поле для ввода и нажмите на кнопку добавления товара в корзину</span></a>. После добавления товара в корзину, её иконка изменится с <a href='pictures/help/icons_basket_empty.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>пустой</span></a> корзины на <a href='pictures/help/icons_basket_full.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>полную</span></a>. Если вы нажмёте ещё раз на кнопку добавления в корзину уже добавленного товара, то количество данного товара в корзине пополнится на количество, указанное в соответствующем ему поле ввода. После нажатия в увидите <a href='pictures/help/good_basket_add.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>сообщение</span></a> об изменении количества этого товара в корзине.</p></div>
                                <div class='helpBlock'><p>Добавьте таким образом все интересующие вас товары в корзину.</p></div>
                                <div class='helpBlock'><p>После того, как все товары будут добавлены, перейдите в вашу <a href='order.php?s=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>корзину</span></a>. Сделать это можно при помощи нажатия на <a href='pictures/help/icons_basket_full.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>иконку корзины</span></a> в верхнем меню сайта или перейдя по <a href='pictures/help/good_basket_link.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>появившейся ссылке</span></a> после добавления товара в корзину.</p></div>
                                <div class='helpBlock'><p>Во вкладке \"<a href='pictures/help/basket.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Текущий заказ</span></a>\" вы найдёте все добавленные вами товары. Если вы добавляли товары и ранее, но не оформляли заказ, добавленные товары также будут находиться в вашей корзине до тех пор, пока вы не оформите ваш заказ или не удалите их из корзины.</p></div>
                                <div class='helpBlock'><p>Если вы хотите изменить количесто товаров в определённой группе, просто введите новое количество в <a href='pictures/help/basket_edit.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>поле для ввода</span></a>. Оно автоматически изменится, а также автоматически пересчитается стоимость редактируемой группы товаров и общая стоимость товаров в корзине.</p></div>
                                <div class='helpBlock'><p>Для удаления группы товаров из корзины, необходимо нажать на <a href='pictures/help/basket_edit.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>иконку удаления товара</span></a>. После нажатия вы увидите <a href='pictures/help/basket_delete.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>сообщение</span></a>, подтверждающие удаление группы товаров.</p></div>
                                <div class='helpBlock'><p>Для удаления всех товаров из корзины, необходимо нажать на кнопку \"<a href='pictures/help/basket.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Очистить корзину</span></a>\", расположенную в нижнем правом углу блока. После нажатия вы увидите <a href='pictures/help/basket_cleared.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>сообщение</span></a>, которое говорит о том, что ваша корзина была успешно очищена.</p></div>
                                <div class='helpBlock'><p>После того, как вы убедитесь, что все товары выбраны верно и верно указано их количество, нажмите на кнопку \"<a href='pictures/help/basket.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Отправить заказ менеджеру</span></a>\", расположенную в нижнем левом углу блока. После нажатия вы увидите <a href='pictures/help/basket_sent.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>сообщение</span></a>, говорящее о том, что заказ успешно оформлен и теперь его смогут увидеть менеджеры и связаться с вами для подтверждения заказа.</p></div>
                            ";
                            break;
                        case "15":
                            echo "
                                <h2 class='goodStyle'>Как редактировать отправленную заявку</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=15' class='noBorder'><span class='catalogueItemTextItalic'>Изменение отправленной заявки</span></a></p>
                                <div class='helpBlock'><p>Посмотреть отправленные заявки вы можете во вкладке \"<a href='order.php?s=2&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>История заказов</span></a>\" в вашей корзине. Как попасть в корзину, читайте в <a href='help.php?section=14' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>соответствующей ветке</span></a>.</p></div>
                                <div class='helpBlock'>
                                    <p>Вкладка \"<a href='order.php?s=2&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>История заказов</span></a>\" разделена на 2 блока:</p>
                                    <ol>
                                            <li><span style='font-family: Cambria, Arial, Helvetica, sans-serif; font-size: 14px; color: #3f3f3f;'>Необработанные заказы (это те заказы, которые ещё не были подтверждены и отданы на сборку)</span></li>
                                            <li><span style='font-family: Cambria, Arial, Helvetica, sans-serif; font-size: 14px; color: #3f3f3f;'>Обработанные заказы (это те заказы, которые вы уже забрали, либо те, которые уже собраны для вас)</span></li>
                                        </ol>
                                </div>
                                <div class='helpBlock'><p>Обработанные заказы вы можете только просматривать, нажав на его номер. После нажатия откроется <a href='pictures/help/order_completed.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>детальная информация</span></a> о заказе.</p></div>
                                <div class='helpBlock'><p>До тех пор, пока менеджер не примет ваш заказ, у вас есть возможность его отредактировать: удалить любые группы товаров. Для этого вам необходимо нажать на номер заказа, после чего откроется <a href='pictures/help/order_sent_detailed.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>детальная информация</span></a> о выбранном заказе. Нажимая на крестик в верхнем правом углу выбранный группы товаров, вы удалите выбранную группу товаров. При этом общая стоимость вашего заказа пересчитается автоматически.</p></div>
                            ";
                            break;
                        case "16":
                            echo "
                                <h2 class='goodStyle'>Редактирование личных данных</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=16' class='noBorder'><span class='catalogueItemTextItalic'>Редактирование личных данных</span></a></p>
                                <div class='helpBlock'><p>Для редактирования личных данных вам нужно <a href='help.php?section=9' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>авторизоваться</span></a> на сайте и войти в <a href='settings.php?s=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>личный кабинет</span></a>.</p></div>
                                <div class='helpBlock'><p>Раздел редактирования личных данных находится во вкладке \"<a href='pictures/help/settings_personal.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Основные настройки</span></a>\".</p></div>
                                <div class='helpBlock'><p>Для редактирования своих личных данных введите новую информацию о себе и нажмите на кнопку \"<b>Внести изменения</b>\".</p></div>
                            ";
                            break;
                        case "17":
                            echo "
                                <h2 class='goodStyle'>Изменения пароля</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=17 class='noBorder'><span class='catalogueItemTextItalic'>Изменение пароля</span></a></p>
                                <div class='helpBlock'><p>Для изменения пароля вам нужно <a href='help.php?section=9' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>авторизоваться</span></a> на сайте и войти в <a href='settings.php?s=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>личный кабинет</span></a>.</p></div>
                                <div class='helpBlock'><p>Раздел изменения пароля находится во вкладке \"<a href='pictures/help/settings_password.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Изменение пароля</span></a>\".</p></div>
                                <div class='helpBlock'><p>Для изменения пароля введите новый пароль и его подтверждение в соответствующие поля ввода, затем нажмите на кнопку \"<b>Изменить пароль</b>\".</p></div>
                            ";
                            break;
                        case "8":
                            echo "
                                <h2 class='goodStyle'>Удаление аккаунта</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>Помощь</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=17 class='noBorder'><span class='catalogueItemTextItalic'>Удаление аккаунта</span></a></p>
                                <div class='helpBlock'><p>Для удаления аккаунта вам нужно <a href='help.php?section=9' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>авторизоваться</span></a> на сайте и войти в <a href='settings.php?s=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>личный кабинет</span></a>.</p></div>
                                <div class='helpBlock'><p>Раздел удаления находится во вкладке \"<a href='pictures/help/settings_delete.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>Удаление аккаунта</span></a>\".</p></div>
                                <div class='helpBlock'><p>Для удаления аккаунта нажмите на кнопку \"<b>Удалить аккаунт</b>\". При удалении имейте ввиду, что аккаунт будет удалён безвозвратно без возможности восстановления. Все личные данные также будут удалены.
</p></div>
                            ";
                            break;
                        default:
                            echo "
                                <div class='helpBlock'><p>Такой страницы не существует. Вернитесь к <a href='help.php' class='noBorder'><span class='basicRed' style='font-size: 14px;'>списку разделов</span>.</p></div>
                            ";
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