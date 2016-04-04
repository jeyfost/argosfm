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
    <meta name='keywords' content='��������� ���������, ������������� ��� ������, �����-��, ������������� ��� ������ �������, �������, ��������� ���������, ������, ������ ���, ����� ���������, ��������� ��������� �������, ������ ��� �������, ����� ���������, ����� ��������� �������, ����� ��������� �������, ������ �������'>

    <title>�����-�� | ������</title>

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
							    <div  style='width: 100%; height: 20px;'></div>
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
            <a href='index.php' class='noBorder' title='��������� �� ������� ��������'>
                <div id='mainPoint' onmouseover='menuVisual("1", "mpIMG", "mpTop")' onmouseout='menuDefault1()'>
                    <div id='mainPointCenter'>
                        <div id='mpTop'></div>
                        <div class='pBottom'>
                            <img src='pictures/system/mainText.png' id='mpIMG' class='noBorder'>
                        </div>
                    </div>
                </div>
            </a>
            <a href='catalogue.php' class='noBorder' title='������� ��������� ��������� � �������������'>
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
            <a href='contacts.php' class='noBorder' title='��� � ���� ���������'>
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

<div id='content_news'>
    <div id='content_news_inner'>
        <span class='bigHeaderStyle'>������. ��� �������� � ������ "</span><a href='index.php' class='noBorder'><h1 class='headerStyleRedHover' style='text-decoration: none;'>�����-��</h1></a><span class='bigHeaderStyle'>"</span>
        <div style='height: 20px;'></div>
        <?php
            if($_SESSION['userID'] == 1) {
                if(empty($_REQUEST['section'])) {
                    echo "
                        <h2 class='goodStyle'>�������� �������� �����</h2>
                        <ol>
                            <li><a href='help.php?section=1' class='noBorder' id='hlp1' onmouseover='helpText(1, \"hlp1\")' onmouseout='helpText(0, \"hlp1\")'>������� ��������</a></li>
                            <li><a href='help.php?section=2' class='noBorder' id='hlp2' onmouseover='helpText(1, \"hlp2\")' onmouseout='helpText(0, \"hlp2\")'>�������</a></li>
                            <li><a href='help.php?section=3' class='noBorder' id='hlp3' onmouseover='helpText(1, \"hlp3\")' onmouseout='helpText(0, \"hlp3\")'>������� � �����������</a></li>
                            <li><a href='help.php?section=4' class='noBorder' id='hlp4' onmouseover='helpText(1, \"hlp4\")' onmouseout='helpText(0, \"hlp4\")'>���������� ���������</a></li>
                            <li><a href='help.php?section=5' class='noBorder' id='hlp5' onmouseover='helpText(1, \"hlp5\")' onmouseout='helpText(0, \"hlp5\")'>�������� ������</a></li>
                            <li><a href='help.php?section=6' class='noBorder' id='hlp6' onmouseover='helpText(1, \"hlp6\")' onmouseout='helpText(0, \"hlp6\")'>������ �������</a></li>
                            <li><a href='help.php?section=7' class='noBorder' id='hlp7' onmouseover='helpText(1, \"hlp7\")' onmouseout='helpText(0, \"hlp7\")'>�������� ������</a></li>
                        </ol>
                        <h2 class='goodStyle'>���� ����������� � �����������</h2>
                        <ol>
                            <li><a href='help.php?section=9' class='noBorder' id='hlp9' onmouseover='helpText(1, \"hlp9\")' onmouseout='helpText(0, \"hlp9\")'>�����������</a></li>
                            <li><a href='help.php?section=10' class='noBorder' id='hlp10' onmouseover='helpText(1, \"hlp10\")' onmouseout='helpText(0, \"hlp10\")'>�����������</a></li>
                            <li><a href='help.php?section=11' class='noBorder' id='hlp11' onmouseover='helpText(1, \"hlp11\")' onmouseout='helpText(0, \"hlp11\")'>�������������� ������</a></li>
                        </ol>
                        <h2 class='goodStyle'>������� ��������</h2>
                        <ol>
                            <li><a href='help.php?section=12' class='noBorder' id='hlp12' onmouseover='helpText(1, \"hlp12\")' onmouseout='helpText(0, \"hlp12\")'>�������� ���������</a></li>
                            <li><a href='help.php?section=13' class='noBorder' id='hlp13' onmouseover='helpText(1, \"hlp13\")' onmouseout='helpText(0, \"hlp13\")'>������������ ��������� ��� � ������</a></li>
                            <li><a href='help.php?section=20' class='noBorder' id='hlp20' onmouseover='helpText(1, \"hlp20\")' onmouseout='helpText(0, \"hlp20\")'>��������� ����� �������</a></li>
                        </ol>
                        <h2 class='goodStyle'>�������</h2>
                        <ol>
                            <li><a href='help.php?section=8' class='noBorder' id='hlp8' onmouseover='helpText(1, \"hlp8\")' onmouseout='helpText(0, \"hlp8\")'>��������� ���� ������</a></li>
                        </ol>
                        <h2 class='goodStyle'>�������� �������</h2>
                        <ol>
                            <li><a href='help.php?section=14' class='noBorder' id='hlp14' onmouseover='helpText(1, \"hlp14\")' onmouseout='helpText(0, \"hlp14\")'>��������� ������</a></li>
                            <li><a href='help.php?section=15' class='noBorder' id='hlp15' onmouseover='helpText(1, \"hlp15\")' onmouseout='helpText(0, \"hlp15\")'>��������� ���������� ������</a></li>
                            <li><a href='help.php?section=16' class='noBorder' id='hlp16' onmouseover='helpText(1, \"hlp16\")' onmouseout='helpText(0, \"hlp16\")'>������� �������</a></li>
                        </ol>
                        <h2 class='goodStyle'>�������� ��������</h2>
                        <ol>
                            <li><a href='help.php?section=17' class='noBorder' id='hlp17' onmouseover='helpText(1, \"hlp17\")' onmouseout='helpText(0, \"hlp17\")'>���������� �������</a></li>
                            <li><a href='help.php?section=18' class='noBorder' id='hlp18' onmouseover='helpText(1, \"hlp18\")' onmouseout='helpText(0, \"hlp18\")'>�������������� �������</a></li>
                            <li><a href='help.php?section=19' class='noBorder' id='hlp19' onmouseover='helpText(1, \"hlp19\")' onmouseout='helpText(0, \"hlp19\")'>����� ��������</a></li>
                        </ol>
                        <h2 class='goodStyle'>������ �������</h2>
                        <ol>
                            <li><a href='help.php?section=21' class='noBorder' id='hlp21' onmouseover='helpText(1, \"hlp21\")' onmouseout='helpText(0, \"hlp21\")'>�������������� ������������ ������</a></li>
                            <li><a href='help.php?section=22' class='noBorder' id='hlp22' onmouseover='helpText(1, \"hlp22\")' onmouseout='helpText(0, \"hlp22\")'>��������� ������</a></li>
                            <li><a href='help.php?section=20' class='noBorder' id='hlp20' onmouseover='helpText(1, \"hlp20\")' onmouseout='helpText(0, \"hlp20\")'>��������� ����� �������</a></li>
                        </ol>
                        <h2 class='goodStyle'>������ �����������������</h2>
                        <ol>
                            <li><a href='help.php?section=23' class='noBorder' id='hlp23' onmouseover='helpText(1, \"hlp23\")' onmouseout='helpText(0, \"hlp23\")'>���� � ������ �����������������</a></li>
                            <li><a href='help.php?section=24' class='noBorder' id='hlp24' onmouseover='helpText(1, \"hlp24\")' onmouseout='helpText(0, \"hlp24\")'>��������� ������ �����������������</a></li>
                            <li><a href='help.php?section=25' class='noBorder' id='hlp25' onmouseover='helpText(1, \"hlp25\")' onmouseout='helpText(0, \"hlp25\")'>���������� ������ � �������</a></li>
                            <li><a href='help.php?section=26' class='noBorder' id='hlp26' onmouseover='helpText(1, \"hlp26\")' onmouseout='helpText(0, \"hlp26\")'>�������������� ������</a></li>
                            <li><a href='help.php?section=27' class='noBorder' id='hlp27' onmouseover='helpText(1, \"hlp27\")' onmouseout='helpText(0, \"hlp27\")'>�������� ������ �� ��������</a></li>
                            <li><a href='help.php?section=28' class='noBorder' id='hlp28' onmouseover='helpText(1, \"hlp28\")' onmouseout='helpText(0, \"hlp28\")'>���������� ������ �������</a></li>
                            <li><a href='help.php?section=29' class='noBorder' id='hlp29' onmouseover='helpText(1, \"hlp29\")' onmouseout='helpText(0, \"hlp29\")'>�������������� �������</a></li>
                            <li><a href='help.php?section=30' class='noBorder' id='hlp30' onmouseover='helpText(1, \"hlp30\")' onmouseout='helpText(0, \"hlp30\")'>�������� �������</a></li>
                            <li><a href='help.php?section=31' class='noBorder' id='hlp31' onmouseover='helpText(1, \"hlp31\")' onmouseout='helpText(0, \"hlp31\")'>�������� e-mail ��������</a></li>
                            <li><a href='help.php?section=32' class='noBorder' id='hlp32' onmouseover='helpText(1, \"hlp32\")' onmouseout='helpText(0, \"hlp32\")'>������� e-mail ��������</a></li>
                            <li><a href='help.php?section=33' class='noBorder' id='hlp33' onmouseover='helpText(1, \"hlp33\")' onmouseout='helpText(0, \"hlp33\")'>��������� �������� ���������� ����</a></li>
                            <li><a href='help.php?section=34' class='noBorder' id='hlp34' onmouseover='helpText(1, \"hlp34\")' onmouseout='helpText(0, \"hlp34\")'>���������� ������ � ���������� ����</a></li>
                            <li><a href='help.php?section=35' class='noBorder' id='hlp35' onmouseover='helpText(1, \"hlp35\")' onmouseout='helpText(0, \"hlp35\")'>�������������� ������ �� ���������� ����</a></li>
                            <li><a href='help.php?section=36' class='noBorder' id='hlp36' onmouseover='helpText(1, \"hlp36\")' onmouseout='helpText(0, \"hlp36\")'>�������� ������ �� ���������� ����</a></li>
                            <li><a href='help.php?section=37' class='noBorder' id='hlp37' onmouseover='helpText(1, \"hlp37\")' onmouseout='helpText(0, \"hlp37\")'>������� ����� ������ � ���������� ����</a></li>
                            <li><a href='help.php?section=38' class='noBorder' id='hlp38' onmouseover='helpText(1, \"hlp38\")' onmouseout='helpText(0, \"hlp38\")'>����� ������ �� ���������� ���� �� ������� �������</a></li>
                            <li><a href='help.php?section=39' class='noBorder' id='hlp39' onmouseover='helpText(1, \"hlp39\")' onmouseout='helpText(0, \"hlp39\")'>������ ��������, ����������� �� ��������</a></li>
                            <li><a href='help.php?section=40' class='noBorder' id='hlp40' onmouseover='helpText(1, \"hlp40\")' onmouseout='helpText(0, \"hlp40\")'>������ ������������������ �������������</a></li>
                            <li><a href='help.php?section=41' class='noBorder' id='hlp41' onmouseover='helpText(1, \"hlp41\")' onmouseout='helpText(0, \"hlp41\")'>��������� ������ ������ �������������</a></li>
                            <li><a href='help.php?section=42' class='noBorder' id='hlp42' onmouseover='helpText(1, \"hlp42\")' onmouseout='helpText(0, \"hlp42\")'>������ �������������� ��������</a></li>
                        </ol>
                    ";
                } else {
                    switch($_REQUEST['section']) {
                        case "1":
                            echo "
                                <h2 class='goodStyle'>��������� ������� ��������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=1' class='noBorder'><span class='catalogueItemTextItalic'>������� ��������</span></a></p>
                                <div class='helpBlock'>
                                    <p>������� �������� ����� <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�����-��</span></a> ������� �� ��� �������������� ������:</p>
                                    <ol>
                                        <li><a href='pictures/help/index.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>������������� ����</a></li>
                                        <li><a href='pictures/help/index_news_adm.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>���� ��������� ��������</a></li>
                                        <li><a href='pictures/help/index_partners.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp3\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'>������ �� ����� ��������</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>������������� ���� ������ ��� �������� �������� � ������������ ��� ������ <a href='catalogue.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������</span><span style='color: #3f3f3f;'> (<a href='catalogue.php?type=fa&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������� ���������</span></a>, <a href='catalogue.php?type=em&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������� ���������</span></a>, <a href='catalogue.php?type=ca&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>���������� ��� ����</span></a>) ���� ������� �� ��������������� ������ <b>\"� �������\"</b> ��� �� �����������.</span></p></div>
                               <div class='helpBlock'> <p>���� ��������� �������� �������� � ���� ��� ��������� �������, �������������� �� �����. ��������� ������ ����� ������� �����, ����� �� ���� � ��������� � ������� ��������� ������������ ��� �������. ������ ���� ����� �������� 3 ������: <b>\"������\"</b> (���� �� <a href='help.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�������� ������ �� �����</span></a>), <b>\"��� �������\"</b> (���� �� <a href='news.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�������� � ������ ������� ��������</span></a>) � <b>\"�������� �������\"</b> (���� �� <a href='admin/admin.php?section=users&action=addNews' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�������� ��������� �������</span></a>).</p><p>��� ������ �� �������� �������� ��������� �� <a href='help.php?section=3' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>������ ������</span></a>.</p></div>
                                <div class='helpBlock'><p>���������� ���� �������� ������ �� ����������� ����� ����� �������� � ���� �� ���������.</p></div>
                            ";
                            break;
                        case "2":
                            echo "
                                <h2 class='goodStyle'>��������� ��������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=2' class='noBorder'><span class='catalogueItemTextItalic'>�������</span></a></p>
                                <div class='helpBlock'>
                                    <p>�������� �������� <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�����-��</span></a> ������� �� ���� �������������� ������:</p>
                                    <ol>
                                        <li><a href='pictures/help/catalogue_nav.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>������������� ����</a></li>
                                        <li><a href='pictures/help/catalogue_content_adm.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>���� � ���������� ��������</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>������������� ���� ������� �� ��� �������� ��������: <a href='catalogue.php?type=fa&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������� ���������</span></a>, <a href='catalogue.php?type=em&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������� ���������</span></a>, <a href='catalogue.php?type=ca&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>���������� ��� ����</span></a>. ����������� �������� ������� �� �����������. ������ � ��������� ���� ������ <a href='pictures/help/catalogue_nav_selected.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���������� ���� ��� �����������</span></a>, ��� ��� ���������� ����� ������� ������������ ��� ���������. � ����������� ����� <a href='pictures/help/catalogue_nav_selected2.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>����� ���� ������������</span></a>.</p></div>
                                <div class='helpBlock'><p>� ����� � ���������� �������� ��������� ������ �� 10 ������������ �� ����� ��������. ���� � �������� ������� ����� 10 ����� �������, �� ������� �� ��������� �������� ����� ��� ������ <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>����� ������������ ���������</span></a>, ������������� �����.</p></div>
                                <div class='helpBlock'><p>������ ���������� ������ ������� ���������: ���� �� �� ������� �� ���� �� ��� �������� ���������, ������ ������������� � ��������� �������, ����� � ��������� ������� ����� ��������� ������ �� ��������� ���������. ���� ������ ������ �� ��������� � �� �������� ����������, ������ �� ������� ������� � ���� ��� ����������� ����� ��������� � ��������� �������. ���� ������ �� �������� �����������, �� ������ ����� ���������� � ������������� �������� ����������, ���������� ��������������� � ���� ������.</p></div>
                                <div class='helpBlock'><p>�����, ������������� � �������������� ������������ ����� ������� ��-�������. <a href='pictures/help/catalogue_content.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>��� �������� �������� ����� �������������� ������������</span></a>, � <a href='pictures/help/catalogue_guest.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>��� � ����� ����� � �������������</span></a>. ������������� ������������������ ������������� �������� ����������� ��������� ������ � ������� � ��������� ������-������.</p></div>
                            ";
                            break;
                        case "3":
                            echo "
                                <h2 class='goodStyle'>��������� �������� ��������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=3' class='noBorder'><span class='catalogueItemTextItalic'>�������</span></a></p>
                                <div class='helpBlock'><p>�� �������� �������� �������� ��� ������� � ������������ �����������, ������� �����-���� ���� ������������. �������� 10 �������� �� ����� ��������. ��� ������������� �� ���� ����������. ��� ��������� ����� ������ ����������, ���������� ��������������� <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������� ������������ ���������</span></a>, ������������� ����� ��������.</p></div>
                                <div class='helpBlock'><p>����� �������� ����� �������������� �� ���� ��� ������ <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���������</span></a> � ������� ������ ����� �������� � <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������ ������</span></a>.</p></div>
                                <div class='helpBlock'><p>����� �� ���� �������������� ���� ������ ���� � ���������. ���� � ����������� ���� ���� ����������, ���� � ��������� ����� �������� <a href='pictures/help/news_calendar_date.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������� ������ �� ����� ����</span></a>. ��� ������ ���� <a href='pictures/help/news_date.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>����� �������� �������</span></a>, ���������� � ���� ����. ��� ��������� �������� ������ <b>\"�������� ����\"</b>, ������� ���������� ��� �������� � ������ ���� ��������.</p></div>
                                <div class='helpBlock'><p>��� ������ ������� �� ���������, ����������� �������� ��� ������ �������, ������� �������� ����� <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>� ���� ��� ������</span></a>. � �������� ������ ��� ����� �������� ��������� ���������� <a href='pictures/help/news_search.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>� ���������� �����</span></a>. ���� ���������� �� ����� �������, �� ������� ������� <b>\"� ���������, ������ �������� �� �������\"</b>. ����� ������� ���������� ����, ������� �� ����� ����� �� ������ ��� ������ �����.</p></div>
                                <div class='helpBlock'><p>��� ��������� ������� ������� �� ���� � � ���������� � �������� ��������� � ����� ������ �������� ��� � ���������� ����� ������.</p></div>
                                <div class='helpBlock'><p>� ������ ������ ����� �������� ����������� <a href='admin/admin.php?section=users&action=addNews' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>������</span></a>, ������� �� <a href='help.php?section=17' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�������� ��������� �������</span></a>.</p></div>
                            ";
                            break;
                        case "4":
                            echo "
                                <h2 class='goodStyle'>��������� �������� � ���������� �����������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=4' class='noBorder'><span class='catalogueItemTextItalic'>���������� ����������</span></a></p>
                                <div class='helpBlock'>
                                    <p>�������� � ���������� ����������� <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�����-��</span></a> ������� �� ���� �������������� ������:</p>
                                    <ol>
                                        <li><a href='pictures/help/contacts_map.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>���� � ������������� ������</a></li>
                                        <li><a href='pictures/help/contacts.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>���� � ���������� �����������</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>� ������������� ������ ����� �����������������: �������� �������, ������������, �������� ����������� ������ � ���������� ���������.</p></div>
                            ";
                            break;
                        case "5":
                            echo "
                                <h2 class='goodStyle'>��������� �������� ������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=5' class='noBorder'><span class='catalogueItemTextItalic'>�������� ������</span></a></p>
                                <div class='helpBlock'><p>�� �������� ������ ��������� ������, �������� ������� ��� ������� ����������� ������ � ��������� ���� �������. �� �������� <a href='pictures/help/search.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���������� �� 10 �������</span></a>, ���� ���� ������� 10 ��� ����� ����������. ��� �������� �� ��������� �������� �������������� <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������ ������������ ���������</span></a>, ������������� ����� �������.</p></div>
                                <div class='helpBlock'><p><a href='pictures/help/search_item.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���� ���������� ������</span></a> �� �������� ������ ������� �� ������ ���������, ����������, ��������, ��������, ��������, ���� � �������.</p></div>
                                <div class='helpBlock'><p><a href='pictures/help/search_nav.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������ ��������� ������</span></a> ����������, � ����� �������� ������ ����� ����������.</p></div>
                            ";
                            break;
                        case "6":
                            echo "
                                <h2 class='goodStyle'>��������� ������� ��������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=6' class='noBorder'><span class='catalogueItemTextItalic'>������ �������</span></a></p>
                                <div class='helpBlock'>
                                    <p>������ ������� ������� �� ��� �������������� ������:</p>
                                    <ol>
                                        <li><a href='pictures/help/settings_1_adm.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>�������� ���������</a></li>
                                        <li><a href='pictures/help/settings_2.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>��������� ������</a></li>
                                        <li><a href='pictures/help/settings_3_adm.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp3\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'>���������� ������</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>������ �� ��������� ������ ���������� ������� � <a href='help.php?section=21' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������������� �����</span></a>.</p></div>
                                <div class='helpBlock'><p>������ �� ��������� ������ ������� � <a href='help.php?section=22' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������������� �����</span></a>.</p></div>
                                <div class='helpBlock'><p>������ �� ��������� ����� ������� ������� � <a href='help.php?section=20' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������������� �����</span></a>.</p></div>
                            ";
                            break;
                        case "7":
                            echo "
                                <h2 class='goodStyle'>��������� �������� ������</h2>
                                    <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=7' class='noBorder'><span class='catalogueItemTextItalic'>�������� ������</span></a></p>
                                    <div class='helpBlock'>
                                        <p>�������� ������ ������� �� ���� �������������� ������:</p>
                                        <ol>
                                            <li><a href='pictures/help/orders.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>�������� ������</a></li>
                                            <li><a href='pictures/help/orders_history.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>������� �������</a></li>
                                        </ol>
                                    </div>
                                    <div class='helpBlock'><p>�� <a href='pictures/help/orders.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'><span class='basicBlue'>�������� �������� ������</span></a> ������������ ��� ���������� ������. �� ��������� ������� ������� � <a href='help.php?section=14' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������������� �����</span></a>.</p></div>
                                    <div class='helpBlock'><p>�� <a href='pictures/help/orders_history.jpg' class='noBorder' rel='shadowbox' id='hlp4' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp4\")' style='color: #008fe1;'><span class='basicBlue'>�������� � �������� �������</span></a> ������������ ��� �������� ������. ������ �� �������� �������� ������� �� ����� � <a href='help.php?section=16' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������������� �����</span></a>.</p></div>
                            ";
                            break;
                        case "8":
                            break;
                        case "9":
                            echo "
                                <h2 class='goodStyle'>�����������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=9' class='noBorder'><span class='catalogueItemTextItalic'>�����������</span></a></p>
                                <div class='helpBlock'><p><a href='pictures/help/auth.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���� �����������</span></a> ������������ ����� ����, ������������� ������ �������� �����. ���� �������� ���� ����� ������ � ������, � ����� ������ �� ������� ����������� � �������������� ������. � �������� <a href='help.php?section=10' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�����������</span></a> � <a href='help.php?section=11' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�������������� ������</span></a> �� ������ ��������� � ��������������� ������.</p></div>
                                <div class='helpBlock'><p>������� � ������ ����������� ����� ���� ������� �� <a href='pictures/help/header.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������ � ���� �����</span></a>, ����������� ����� ������ � ���������� �������� � ������ ������.</p></div>
                                <div class='helpBlock'><p>����������� ���������� ��� ���������� ������-������.</p></div>
                                <div class='helpBlock'><p>��� ����������� ������� ���� ����� � ������ �  ������ <b>\"�����\"</b>.</p></div>
                            ";
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
                        case "19":
                            break;
                        case "20":
                            break;
                        case "21":
                            break;
                        case "22":
                            break;
                        case "23":
                            break;
                        case "24":
                            break;
                        case "25":
                            break;
                        case "26":
                            break;
                        case "27":
                            break;
                        case "28":
                            break;
                        case "29":
                            break;
                        case "30":
                            break;
                        case "31":
                            break;
                        case "32":
                            break;
                        case "33":
                            break;
                        case "34":
                            break;
                        case "35":
                            break;
                        case "36":
                            break;
                        case "37":
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
                                <div class='helpBlock'><p>����� �������� �� ����������. ��������� � <a href='help.php' class='noBorder'><span class='basicRed' style='font-size: 14px;'>������ ��������</span>.</p></div>
                            ";
                            break;
                    }
                }
            } else {
                if(empty($_REQUEST['section'])) {
                    echo "
                        <h2 class='goodStyle'>�������� �������� �����</h2>
                        <ol>
                            <li><a href='help.php?section=1' class='noBorder' id='hlp1' onmouseover='helpText(1, \"hlp1\")' onmouseout='helpText(0, \"hlp1\")'>������� ��������</a></li>
                            <li><a href='help.php?section=2' class='noBorder' id='hlp2' onmouseover='helpText(1, \"hlp2\")' onmouseout='helpText(0, \"hlp2\")'>�������</a></li>
                            <li><a href='help.php?section=3' class='noBorder' id='hlp3' onmouseover='helpText(1, \"hlp3\")' onmouseout='helpText(0, \"hlp3\")'>������� � �����������</a></li>
                            <li><a href='help.php?section=4' class='noBorder' id='hlp4' onmouseover='helpText(1, \"hlp4\")' onmouseout='helpText(0, \"hlp4\")'>���������� ���������</a></li>
                            <li><a href='help.php?section=5' class='noBorder' id='hlp5' onmouseover='helpText(1, \"hlp5\")' onmouseout='helpText(0, \"hlp5\")'>�������� ������</a></li>
                            <li><a href='help.php?section=6' class='noBorder' id='hlp6' onmouseover='helpText(1, \"hlp6\")' onmouseout='helpText(0, \"hlp6\")'>������ �������</a></li>
                            <li><a href='help.php?section=7' class='noBorder' id='hlp7' onmouseover='helpText(1, \"hlp7\")' onmouseout='helpText(0, \"hlp7\")'>������� � �������� �������</a></li>
                        </ol>
                        <h2 class='goodStyle'>���� ����������� � �����������</h2>
                        <ol>
                            <li><a href='help.php?section=9' class='noBorder' id='hlp9' onmouseover='helpText(1, \"hlp9\")' onmouseout='helpText(0, \"hlp9\")'>�����������</a></li>
                            <li><a href='help.php?section=10' class='noBorder' id='hlp10' onmouseover='helpText(1, \"hlp10\")' onmouseout='helpText(0, \"hlp10\")'>�����������</a></li>
                            <li><a href='help.php?section=11' class='noBorder' id='hlp11' onmouseover='helpText(1, \"hlp11\")' onmouseout='helpText(0, \"hlp11\")'>�������������� ������</a></li>
                        </ol>
                        <h2 class='goodStyle'>������� ��������</h2>
                        <ol>
                            <li><a href='help.php?section=12' class='noBorder' id='hlp12' onmouseover='helpText(1, \"hlp12\")' onmouseout='helpText(0, \"hlp12\")'>�������� ���������</a></li>
                            <li><a href='help.php?section=13' class='noBorder' id='hlp13' onmouseover='helpText(1, \"hlp13\")' onmouseout='helpText(0, \"hlp13\")'>������������ ��������� ��� � ������</a></li>
                        </ol>
                        <h2 class='goodStyle'>������� �����</h2>
                        <ol>
                            <li><a href='help.php?section=14' class='noBorder' id='hlp14' onmouseover='helpText(1, \"hlp14\")' onmouseout='helpText(0, \"hlp14\")'>���������� ������</a></li>
                            <li><a href='help.php?section=15' class='noBorder' id='hlp15' onmouseover='helpText(1, \"hlp15\")' onmouseout='helpText(0, \"hlp15\")'>��������� ������������ ������</a></li>
                            <li><a href='help.php?section=16' class='noBorder' id='hlp16' onmouseover='helpText(1, \"hlp16\")' onmouseout='helpText(0, \"hlp16\")'>�������������� ������ ������</a></li>
                            <li><a href='help.php?section=17' class='noBorder' id='hlp17' onmouseover='helpText(1, \"hlp17\")' onmouseout='helpText(0, \"hlp17\")'>��������� ������</a></li>
                            <li><a href='help.php?section=8' class='noBorder' id='hlp18' onmouseover='helpText(1, \"hlp18\")' onmouseout='helpText(0, \"hlp18\")'>�������� ��������</a></li>
                        </ol>
                    ";
                } else {
                    switch($_REQUEST['section']) {
                        case "1":
                            echo "
                                <h2 class='goodStyle'>��������� ������� ��������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=1' class='noBorder'><span class='catalogueItemTextItalic'>������� ��������</span></a></p>
                                <div class='helpBlock'>
                                    <p>������� �������� ����� <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�����-��</span></a> ������� �� ��� �������������� ������:</p>
                                    <ol>
                                        <li><a href='pictures/help/index.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>������������� ����</a></li>
                                        <li><a href='pictures/help/index_news.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>���� ��������� ��������</a></li>
                                        <li><a href='pictures/help/index_partners.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp3\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'>������ �� ����� ��������</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>������������� ���� ������ ��� �������� �������� � ������������ ��� ������ <a href='catalogue.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������</span><span style='color: #3f3f3f;'> (<a href='catalogue.php?type=fa&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������� ���������</span></a>, <a href='catalogue.php?type=em&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������� ���������</span></a>, <a href='catalogue.php?type=ca&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>���������� ��� ����</span></a>) ���� ������� �� ��������������� ������ <b>\"� �������\"</b> ��� �� �����������.</span></p></div>
                               <div class='helpBlock'> <p>���� ��������� �������� �������� � ���� ��� ��������� �������, �������������� �� �����. ��������� ������ ����� ������� �����, ����� �� ���� � ��������� � ������� ��������� ������������ ��� �������. ������ ���� ����� �������� 2 ������: <b>\"������\"</b> (���� �� <a href='help.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�������� ������ �� �����</span></a>) � <b>\"��� �������\"</b> (���� �� <a href='news.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�������� � ������ ������� ��������</span></a>).</p><p>��� ������ �� �������� �������� ��������� �� <a href='help.php?section=3' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>������ ������</span></a>.</p></div>
                                <div class='helpBlock'><p>���������� ���� �������� ������ �� ����������� ����� ����� �������� � ���� �� ���������.</p></div>
                            ";
                            break;
                        case "2":
                            echo "
                                <h2 class='goodStyle'>��������� ��������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=2' class='noBorder'><span class='catalogueItemTextItalic'>�������</span></a></p>
                                <div class='helpBlock'>
                                    <p>�������� �������� <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�����-��</span></a> ������� �� ���� �������������� ������:</p>
                                    <ol>
                                        <li><a href='pictures/help/catalogue_nav.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>������������� ����</a></li>
                                        <li><a href='pictures/help/catalogue_content.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>���� � ���������� ��������</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>������������� ���� ������� �� ��� �������� ��������: <a href='catalogue.php?type=fa&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������� ���������</span></a>, <a href='catalogue.php?type=em&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������� ���������</span></a>, <a href='catalogue.php?type=ca&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>���������� ��� ����</span></a>. ����������� �������� ������� �� �����������. ������ � ��������� ���� ������ <a href='pictures/help/catalogue_nav_selected.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���������� ���� ��� �����������</span></a>, ��� ��� ���������� ����� ������� ������������ ��� ���������. � ����������� ����� <a href='pictures/help/catalogue_nav_selected2.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>����� ���� ������������</span></a>.</p></div>
                                <div class='helpBlock'><p>� ����� � ���������� �������� ��������� ������ �� 10 ������������ �� ����� ��������. ���� � �������� ������� ����� 10 ����� �������, �� ������� �� ��������� �������� ����� ��� ������ <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>����� ������������ ���������</span></a>, ������������� �����.</p></div>
                                <div class='helpBlock'><p>������ ���������� ������ ������� ���������: ���� �� �� ������� �� ���� �� ��� �������� ���������, ������ ������������� � ��������� �������, ����� � ��������� ������� ����� ��������� ������ �� ��������� ���������. ���� ������ ������ �� ��������� � �� �������� ����������, ������ �� ������� ������� � ���� ��� ����������� ����� ��������� � ��������� �������. ���� ������ �� �������� �����������, �� ������ ����� ���������� � ������������� �������� ����������, ���������� ��������������� � ���� ������.</p></div>
                                <div class='helpBlock'><p>����� � �������������� ������������ ����� ������� ��-�������. <a href='pictures/help/catalogue_content.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>��� �������� �������� ����� �������������� ������������</span></a>, � <a href='pictures/help/catalogue_guest.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>��� � ����� �����</span></a>. ������������� ������������������ ������������� �������� ����������� ��������� ������ � ������� � ��������� ������-������.</p></div>
                                <div class='helpBlock'><p>������, ��� �������� ����� � ������� ����� <a href='help.php?section=14' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�� ���� ��������</span></a>.</p></div>
                            ";
                            break;
                        case "3":
                            echo "
                                <h2 class='goodStyle'>��������� �������� ��������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=3' class='noBorder'><span class='catalogueItemTextItalic'>�������</span></a></p>
                                <div class='helpBlock'><p>�� �������� �������� �������� ��� ������� � ������������ �����������, ������� �����-���� ���� ������������. �������� 10 �������� �� ����� ��������. ��� ������������� �� ���� ����������. ��� ��������� ����� ������ ����������, ���������� ��������������� <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������� ������������ ���������</span></a>, ������������� ����� ��������.</p></div>
                                <div class='helpBlock'><p>����� �������� ����� �������������� �� ���� ��� ������ <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���������</span></a> � ������� ������ ����� �������� � <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������ ������</span></a>.</p></div>
                                <div class='helpBlock'><p>����� �� ���� �������������� ���� ������ ���� � ���������. ���� � ����������� ���� ���� ����������, ���� � ��������� ����� �������� <a href='pictures/help/news_calendar_date.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������� ������ �� ����� ����</span></a>. ��� ������ ���� <a href='pictures/help/news_date.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>����� �������� �������</span></a>, ���������� � ���� ����. ��� ��������� �������� ������ <b>\"�������� ����\"</b>, ������� ���������� ��� �������� � ������ ���� ��������.</p></div>
                                <div class='helpBlock'><p>��� ������ ������� �� ���������, ����������� �������� ��� ������ �������, ������� �������� ����� <a href='pictures/help/news_calendar.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>� ���� ��� ������</span></a>. � �������� ������ ��� ����� �������� ��������� ���������� <a href='pictures/help/news_search.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>� ���������� �����</span></a>. ���� ���������� �� ����� �������, �� ������� ������� <b>\"� ���������, ������ �������� �� �������\"</b>. ����� ������� ���������� ����, ������� �� ����� ����� �� ������ ��� ������ �����.</p></div>
                                <div class='helpBlock'><p>��� ��������� ������� ������� �� ���� � � ���������� � �������� ��������� � ����� ������ �������� ��� � ���������� ����� ������.</p></div>
                            ";
                            break;
                        case "4":
                            echo "
                                <h2 class='goodStyle'>��������� �������� � ���������� �����������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=4' class='noBorder'><span class='catalogueItemTextItalic'>���������� ����������</span></a></p>
                                <div class='helpBlock'>
                                    <p>�������� � ���������� ����������� <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�����-��</span></a> ������� �� ���� �������������� ������:</p>
                                    <ol>
                                        <li><a href='pictures/help/contacts_map.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>���� � ������������� ������</a></li>
                                        <li><a href='pictures/help/contacts.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>���� � ���������� �����������</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>� ������������� ������ ����� �����������������: �������� �������, ������������, �������� ����������� ������ � ���������� ���������.</p></div>
                            ";
                            break;
                        case "5":
                            echo "
                                <h2 class='goodStyle'>��������� �������� ������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=5' class='noBorder'><span class='catalogueItemTextItalic'>�������� ������</span></a></p>
                                <div class='helpBlock'><p>�� �������� ������ ��������� ������, �������� ������� ��� ������� ����������� ������ � ��������� ���� �������. �� �������� <a href='pictures/help/search.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���������� �� 10 �������</span></a>, ���� ���� ������� 10 ��� ����� ����������. ��� �������� �� ��������� �������� �������������� <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������ ������������ ���������</span></a>, ������������� ����� �������.</p></div>
                                <div class='helpBlock'><p><a href='pictures/help/search_item.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���� ���������� ������</span></a> �� �������� ������ ������� �� ������ ���������, ����������, ��������, ��������, ��������, ���� � �������.</p></div>
                                <div class='helpBlock'><p><a href='pictures/help/search_nav.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������ ��������� ������</span></a> ����������, � ����� �������� ������ ����� ����������.</p></div>
                            ";
                            break;
                        case "6":
                            echo "
                                <h2 class='goodStyle'>��������� ������� ��������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=6' class='noBorder'><span class='catalogueItemTextItalic'>������ �������</span></a></p>
                                <div class='helpBlock'>
                                    <p>������ ������� ������� �� ��� �������������� ������:</p>
                                    <ol>
                                        <li><a href='pictures/help/settings_1.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>�������� ���������</a></li>
                                        <li><a href='pictures/help/settings_2.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>��������� ������</a></li>
                                        <li><a href='pictures/help/settings_3.jpg' class='noBorder' rel='shadowbox' id='hlp3' onmouseover='helpTextB(1, \"hlp3\")' onmouseout='helpTextB(0, \"hlp3\")' style='color: #008fe1;'>�������� ��������</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>������ �� ��������� ������ ���������� ������� � <a href='help.php?section=16' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������������� �����</span></a>.</p></div>
                                <div class='helpBlock'><p>������ �� ��������� ������ ������� � <a href='help.php?section=17' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������������� �����</span></a>.</p></div>
                                <div class='helpBlock'><p>������ �� �������� ������ �������� ������� � <a href='help.php?section=8' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������������� �����</span></a>.</p></div>
                            ";
                            break;
                        case "7":
                                echo "
                                    <h2 class='goodStyle'>��������� �������� �������</h2>
                                    <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=7' class='noBorder'><span class='catalogueItemTextItalic'>�������� �������</span></a></p>
                                    <div class='helpBlock'>
                                        <p>�������� ������� ������� �� ���� �������������� ������:</p>
                                        <ol>
                                            <li><a href='pictures/help/order_basket.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>������� ����� ��� �������</a></li>
                                            <li><a href='pictures/help/order_history.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>������� �������</a></li>
                                        </ol>
                                    </div>
                                    <div class='helpBlock'><p>�� �������� �������� ������ ���������� ��� ������, ������� �� �������� � ������� � <a href='catalogue.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������</span></a>. ��� ������� ������� ��������� ����� ����� ������ � ����������� ������. �������� � �������� ������� ������� <a href='help.php?section=14' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>� ��������������� �����</span></a>.</p></div>
                                    <div class='helpBlock'><p>�� ������� <b>\"������� �������\"</b> � ������� �������� ����� ����� ����������� ���� �������.</p></div>
                                    <div class='helpBlock'><p>
                                        �������� <b>\"������� �������\"</b> ������� �� ��� �����:
                                        <ol>
                                            <li style='color: #3f3f3f;'>�������������� ������</li>
                                            <li style='color: #3f3f3f;'>������������ ������</li>
                                        </ol>
                                    </p></div>
                                    <div class='helpBlock'><p>� ������� �������������� ������� ���������� ���� ������, ������� ��� ���� ���������� ���������� �� ������������, �� ��� �� ������ �� ������.</p></div>
                                    <div class='helpBlock'><p>� ������� ������������ ������� ���������� ��� ������� ����� ������. ������������ ������ ������������ �� 10 �� ��������. ��� �������� �� ������ ��������, �������������� <a href='pictures/help/catalogue_nav_numbers.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������ ������������ ���������</span></a>, ������������� �����.</p></div>
                                ";
                            break;
                        case "9":
                            echo "
                                <h2 class='goodStyle'>�����������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=9' class='noBorder'><span class='catalogueItemTextItalic'>�����������</span></a></p>
                                <div class='helpBlock'><p><a href='pictures/help/auth.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���� �����������</span></a> ������������ ����� ����, ������������� ������ �������� �����. ���� �������� ���� ����� ������ � ������, � ����� ������ �� ������� ����������� � �������������� ������. � �������� <a href='help.php?section=10' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�����������</span></a> � <a href='help.php?section=11' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�������������� ������</span></a> �� ������ ��������� � ��������������� ������.</p></div>
                                <div class='helpBlock'><p>������� � ������ ����������� ����� ���� ������� �� <a href='pictures/help/header.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������ � ���� �����</span></a>, ����������� ����� ������ � ���������� �������� � ������ ������.</p></div>
                                <div class='helpBlock'><p>����������� ���������� ��� ���������� ������-������.</p></div>
                                <div class='helpBlock'><p>��� ����������� ������� ���� ����� � ������ �  ������ <b>\"�����\"</b>.</p></div>
                            ";
                            break;
                        case "10":
                            echo "
                                <h2 class='goodStyle'>����������� ������ ������������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=10' class='noBorder'><span class='catalogueItemTextItalic'>�����������</span></a></p>
                                <div class='helpBlock'>
                                    <p>������ ����������� ������ ������������ ������������ ����� ����, ������������� ������ �������� ����, ���������� �� ��� �����:</p>
                                    <ol>
                                        <li><a href='pictures/help/reg_org.jpg' class='noBorder' rel='shadowbox' id='hlp1' onmouseover='helpTextB(1, \"hlp1\")' onmouseout='helpTextB(0, \"hlp1\")' style='color: #008fe1;'>����������� ��� �����������</a></li>
                                        <li><a href='pictures/help/reg_person.jpg' class='noBorder' rel='shadowbox' id='hlp2' onmouseover='helpTextB(1, \"hlp2\")' onmouseout='helpTextB(0, \"hlp2\")' style='color: #008fe1;'>����������� ��� ���������� ���</a></li>
                                    </ol>
                                </div>
                                <div class='helpBlock'><p>������� � ������ ����������� ����� ���� ������� �� <a href='pictures/help/header.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������ � ���� �����</span></a>, ����������� ����� ������ � ���������� �������� � ������ ������. ����� ���������� ������ �� ������� <b>\"��� �� ����������������?\"</b> ����� ������������ <a href='pictures/help/auth.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>����� �����������</span></a>.</p></div>
                                <div class='helpBlock'><p>��� ���������� ����������� �� ����� ������� ���������� ������� ���, ������� ��� ��� ��������: <b>�����������</b> ��� <b>���������� ����</b>.</p></div>
                                <div class='helpBlock'><p>� ���� ����� ������ ���������� ������ ���� �����, ������� ����� �������� �� ��������� ����, ����, �������� \"����\"<b>-</b>, ������� ������������� <b>_</b> � ����� <b>.</b> ����������� ����� ������: 3 �������. ���� ��������� ���� ����� ��� �����, ������� ������� ��� �� ����, ������� ���� ���� ����� ������ �� �������.</p></div>
                                <div class='helpBlock'><p>������ ������ �������� ������� �� 5 ��������.</p></div>
                                <div class='helpBlock'><p>���������� ������ ������� e-mail �����, ��� ��� �� ���� ����� ���������� ������ � �������������� �����������. ��� ������������� ���� ����������� �� ����� ��������� ��������������. ���� ��� ����������� ����� ��� ��� ������ ��� �����������, ������� ������� ��� �� ����, ������� ���� ���� ����� e-mail ������ �� �������.</p></div>
                                <div class='helpBlock'><p>���� �� ������� ��� ������������ <b>\"�����������\"</b> ��� ����� ���������� ������ �������� �����������, ������� �� �������������. ���� ���� ����������� ��� ����������������, ������� ������� ��� �� ����, ������� ���� ���� ����� ������ �� �������.</p></div>
                                <div class='helpBlock'><p>� ���� ��� ����� ����������� ����, ������� ��� ���� ��������, � ��� �� ����� ����������� � ������ ���������� ������-������.</p></div>
                                <div class='helpBlock'><p>����� �������� ���������� ��������� � ������������� �������: +375(XX)XXXXXXX �� ����� ������. ��� �������� ��� ������ � ����. ���� �������� ���� ����� �������� ��� ��� ������ ��� �����������, ������� ������� ��� �� ����, ������� ���� ���� ����� ������ �� �������.</p></div>
                                <div class='helpBlock'><p>����� ����� ���� ���������� ������� �� ������ <b>\"������������������\"</b>.</p></div>
                                <div class='helpBlock'><p>���� �� ���� ��������� �����, �� ������� <a href='pictures/help/reg_1.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>����</span></a>, ����������, ��� ������ ���� ����������� ������� ��������. ����� ����� ���������� ����� � ���� ����������� ����� � ������� �� ������ �� ������, ����� ������������ �������. ����� ��������� �� ������� ����� <a href='pictures/help/reg_2.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>����</span></a>. ��� ������, ��� ����������� ������� ��������� � �� ������ ������ ��������� ������-������.</p></div>
                            ";
                            break;
                        case "11":
                            echo "
                                <h2 class='goodStyle'>�������������� ������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=11' class='noBorder'><span class='catalogueItemTextItalic'>�������������� ������</span></a></p>
                                <div class='helpBlock'><p><a href='pictures/help/recovery.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���� �������������� ������</span></a> ������������ ����� ����, ������������� ������ �������� �����.</p></div>
                                <div class='helpBlock'><p>������� � ������ ����������� ����� ���� ������� �� <a href='pictures/help/header.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������ � ���� �����</span></a>, ����������� ����� ������ � ���������� �������� � ������ ������. ����� ���������� ������ �� ������� <b>\"������ ������?\"</b> ����� ������������ <a href='pictures/help/auth.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>����� �����������</span></a>.</p></div>
                                <div class='helpBlock'><p>��� �������������� �������� ������ ��� ���������� ������ ��� ����� ��� e-mail, ��������� ��� �����������, ����� ���� �� ��� ����������� ����� ����� ������ �� �������, ������� �� ������� ��� ��� ������������� ����� ��������� ������. �������� ��� ����� ����� � <a href='help.php?section=6' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>������ ��������</span></a> ����� <a href='help.php?section=9' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�����������</span></a>.</p></div>
                            ";
                            break;
                        case "12":
                            echo "
                                <h2 class='goodStyle'>�������� ��������� ������� ��������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=12' class='noBorder'><span class='catalogueItemTextItalic'>�������� ��������� ������� ��������</span></a></p>
                                <div class='helpBlock'><p>���� �� ����� <a href='index.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�����-��</span></a> ������������ � ����������� ������, ������ ��� ��������� � ����������� ������ ����� ������������� ����� ���������� ��������.</p></div>
                                <div class='helpBlock'><p>��� �������, ��������� � ����� ���������� ����� ����� ������������� ����������� ������ ����� �� ����� ������������� ����� ���������� ��������.</p></div>
                                <div class='helpBlock'><p>� ����������� ����������� ������ ����� �������� � ���� � ����������� ������ � ��������.</p></div>
                            ";
                            break;
                        case "13":
                            echo "
                                <h2 class='goodStyle'>�� ��������� ��� � ����������� ������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=13' class='noBorder'><span class='catalogueItemTextItalic'>������������ ��������� ��� � ������</span></a></p>
                                <div class='helpBlock'><p>��������� <a href='help.php?section=12' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>���� �� ������ ��������� � ����������� ������ ����� ������������� ����� ���������� ��������</span></a>, ����� � ����������� ������ � ����������� ������ ����� ����� ����������, ���� �� �����-���� ������� ��� ����� �� ��� ��������. ���� ����� ���� ��� �� �������, �� ������ ������ ���������� �� ������������ ������ ��� �����-���� ������� ���� ��������� � ���������� ����������� ������������ ������� �� ����, �������������� �� ������ ���������� ������.</p></div>
                                <div class='helpBlock'><p>������� ������ �� �������� ���� ������������� �������� � ���������� � ����������� �� ������ ������ ��� �� ����� ������ ������.</p></div>
                            ";
                            break;
                        case "14":
                            echo "
                                <h2 class='goodStyle'>��� �������� �����</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=14' class='noBorder'><span class='catalogueItemTextItalic'>���������� ������</span></a></p>
                                <div class='helpBlock'><p>��� ���������� ������ ���������� <a href='help.php?section=9' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�������������� �� �����</span></a> � ������� � <a href='catalogue.php' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�������</span></a>. ����� ����, ��� �� ������ ����������� ��� �����, <a href='pictures/help/good_basket.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������� ��� ���������� � ���� ��� ����� � ������� �� ������ ���������� ������ � �������</span></a>. ����� ���������� ������ � �������, � ������ ��������� � <a href='pictures/help/icons_basket_empty.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������</span></a> ������� �� <a href='pictures/help/icons_basket_full.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������</span></a>. ���� �� ������ ��� ��� �� ������ ���������� � ������� ��� ������������ ������, �� ���������� ������� ������ � ������� ���������� �� ����������, ��������� � ��������������� ��� ���� �����. ����� ������� � ������� <a href='pictures/help/good_basket_add.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���������</span></a> �� ��������� ���������� ����� ������ � �������.</p></div>
                                <div class='helpBlock'><p>�������� ����� ������� ��� ������������ ��� ������ � �������.</p></div>
                                <div class='helpBlock'><p>����� ����, ��� ��� ������ ����� ���������, ��������� � ���� <a href='order.php?s=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>�������</span></a>. ������� ��� ����� ��� ������ ������� �� <a href='pictures/help/icons_basket_full.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������ �������</span></a> � ������� ���� ����� ��� ������� �� <a href='pictures/help/good_basket_link.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>����������� ������</span></a> ����� ���������� ������ � �������.</p></div>
                                <div class='helpBlock'><p>�� ������� \"<a href='pictures/help/basket.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������� �����</span></a>\" �� ������ ��� ����������� ���� ������. ���� �� ��������� ������ � �����, �� �� ��������� �����, ����������� ������ ����� ����� ���������� � ����� ������� �� ��� ���, ���� �� �� �������� ��� ����� ��� �� ������� �� �� �������.</p></div>
                                <div class='helpBlock'><p>���� �� ������ �������� ��������� ������� � ����������� ������, ������ ������� ����� ���������� � <a href='pictures/help/basket_edit.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���� ��� �����</span></a>. ��� ������������� ���������, � ����� ������������� ������������� ��������� ������������� ������ ������� � ����� ��������� ������� � �������.</p></div>
                                <div class='helpBlock'><p>��� �������� ������ ������� �� �������, ���������� ������ �� <a href='pictures/help/basket_edit.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>������ �������� ������</span></a>. ����� ������� �� ������� <a href='pictures/help/basket_delete.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���������</span></a>, �������������� �������� ������ �������.</p></div>
                                <div class='helpBlock'><p>��� �������� ���� ������� �� �������, ���������� ������ �� ������ \"<a href='pictures/help/basket.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>�������� �������</span></a>\", ������������� � ������ ������ ���� �����. ����� ������� �� ������� <a href='pictures/help/basket_cleared.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���������</span></a>, ������� ������� � ���, ��� ���� ������� ���� ������� �������.</p></div>
                                <div class='helpBlock'><p>����� ����, ��� �� ���������, ��� ��� ������ ������� ����� � ����� ������� �� ����������, ������� �� ������ \"<a href='pictures/help/basket.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>��������� ����� ���������</span></a>\", ������������� � ������ ����� ���� �����. ����� ������� �� ������� <a href='pictures/help/basket_sent.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>���������</span></a>, ��������� � ���, ��� ����� ������� �������� � ������ ��� ������ ������� ��������� � ��������� � ���� ��� ������������� ������.</p></div>
                            ";
                            break;
                        case "15":
                            echo "
                                <h2 class='goodStyle'>��� ������������� ������������ ������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=15' class='noBorder'><span class='catalogueItemTextItalic'>��������� ������������ ������</span></a></p>
                                <div class='helpBlock'><p>���������� ������������ ������ �� ������ �� ������� \"<a href='order.php?s=2&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>������� �������</span></a>\" � ����� �������. ��� ������� � �������, ������� � <a href='help.php?section=14' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������������� �����</span></a>.</p></div>
                                <div class='helpBlock'>
                                    <p>������� \"<a href='order.php?s=2&p=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>������� �������</span></a>\" ��������� �� 2 �����:</p>
                                    <ol>
                                            <li><span style='font-family: Cambria, Arial, Helvetica, sans-serif; font-size: 14px; color: #3f3f3f;'>�������������� ������ (��� �� ������, ������� ��� �� ���� ������������ � ������ �� ������)</span></li>
                                            <li><span style='font-family: Cambria, Arial, Helvetica, sans-serif; font-size: 14px; color: #3f3f3f;'>������������ ������ (��� �� ������, ������� �� ��� �������, ���� ��, ������� ��� ������� ��� ���)</span></li>
                                        </ol>
                                </div>
                                <div class='helpBlock'><p>������������ ������ �� ������ ������ �������������, ����� �� ��� �����. ����� ������� ��������� <a href='pictures/help/order_completed.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>��������� ����������</span></a> � ������.</p></div>
                                <div class='helpBlock'><p>�� ��� ���, ���� �������� �� ������ ��� �����, � ��� ���� ����������� ��� ���������������: ������� ����� ������ �������. ��� ����� ��� ���������� ������ �� ����� ������, ����� ���� ��������� <a href='pictures/help/order_sent_detailed.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>��������� ����������</span></a> � ��������� ������. ������� �� ������� � ������� ������ ���� ��������� ������ �������, �� ������� ��������� ������ �������. ��� ���� ����� ��������� ������ ������ ������������� �������������.</p></div>
                            ";
                            break;
                        case "16":
                            echo "
                                <h2 class='goodStyle'>�������������� ������ ������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=16' class='noBorder'><span class='catalogueItemTextItalic'>�������������� ������ ������</span></a></p>
                                <div class='helpBlock'><p>��� �������������� ������ ������ ��� ����� <a href='help.php?section=9' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������������</span></a> �� ����� � ����� � <a href='settings.php?s=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>������ �������</span></a>.</p></div>
                                <div class='helpBlock'><p>������ �������������� ������ ������ ��������� �� ������� \"<a href='pictures/help/settings_personal.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>�������� ���������</span></a>\".</p></div>
                                <div class='helpBlock'><p>��� �������������� ����� ������ ������ ������� ����� ���������� � ���� � ������� �� ������ \"<b>������ ���������</b>\".</p></div>
                            ";
                            break;
                        case "17":
                            echo "
                                <h2 class='goodStyle'>��������� ������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=17 class='noBorder'><span class='catalogueItemTextItalic'>��������� ������</span></a></p>
                                <div class='helpBlock'><p>��� ��������� ������ ��� ����� <a href='help.php?section=9' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������������</span></a> �� ����� � ����� � <a href='settings.php?s=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>������ �������</span></a>.</p></div>
                                <div class='helpBlock'><p>������ ��������� ������ ��������� �� ������� \"<a href='pictures/help/settings_password.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>��������� ������</span></a>\".</p></div>
                                <div class='helpBlock'><p>��� ��������� ������ ������� ����� ������ � ��� ������������� � ��������������� ���� �����, ����� ������� �� ������ \"<b>�������� ������</b>\".</p></div>
                            ";
                            break;
                        case "8":
                            echo "
                                <h2 class='goodStyle'>�������� ��������</h2>
                                <p><a href='help.php' class='noBorder'><span class='catalogueItemTextItalic'>������</span></a><span class='catalogueItemTextItalicNoHover'> > </span><a href='help.php?section=17 class='noBorder'><span class='catalogueItemTextItalic'>�������� ��������</span></a></p>
                                <div class='helpBlock'><p>��� �������� �������� ��� ����� <a href='help.php?section=9' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>��������������</span></a> �� ����� � ����� � <a href='settings.php?s=1' target='_blank' class='noBorder'><span class='basicRed' style='font-size: 14px;'>������ �������</span></a>.</p></div>
                                <div class='helpBlock'><p>������ �������� ��������� �� ������� \"<a href='pictures/help/settings_delete.jpg' class='noBorder' rel='lightbox'><span class='basicBlue'>�������� ��������</span></a>\".</p></div>
                                <div class='helpBlock'><p>��� �������� �������� ������� �� ������ \"<b>������� �������</b>\". ��� �������� ������ �����, ��� ������� ����� ����� ������������ ��� ����������� ��������������. ��� ������ ������ ����� ����� �������.
</p></div>
                            ";
                            break;
                        default:
                            echo "
                                <div class='helpBlock'><p>����� �������� �� ����������. ��������� � <a href='help.php' class='noBorder'><span class='basicRed' style='font-size: 14px;'>������ ��������</span>.</p></div>
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
    $(window).load(function(e) {
        var fullHeight = $('#content_news').offset().top + $('#content_news').height() + 50;

        if($('footer').offset().top < fullHeight) {
            $('footer').offset({top: fullHeight});
        }
    });
</script>

</body>

</html>