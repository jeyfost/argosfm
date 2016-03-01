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
            <a href='index.php' class='noBorder' title='Вернуться на главную страницу'>
                <div id='mainPoint'>
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
									<a href='order.php' class='noBorder'><img src='pictures/system/basketFull.png' class='noBorder' id='basketIcon' title='Корзина | Количество заявок: ".$ordersResult->num_rows."' onmouseover='changeBasketFullIcon(1)' onmouseout='changeBasketFullIcon(0)' /></a>
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
        <span class='bigHeaderStyle'>Помощь. Как работать с сайтом "</span><a href='index.php' class='noBorder'><h1 class='headerStyleRedHover'>Аргос-ФМ</h1></a><span class='bigHeaderStyle'>"</span>
        <div style='height: 20px;'></div>

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
        var counter = 0;

        if($('footer').offset().top < fullHeight) {
            $('footer').offset({top: fullHeight});
        }
    });
</script>

</body>

</html>