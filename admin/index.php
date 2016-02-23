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
	
?>

<!doctype html>

<html>

<head>

    <meta charset="windows-1251">
    
    <title>Вход в панель администрирования</title>
    
    <link rel='shortcut icon' href='../pictures/icons/favicon.ico' type='image/x-icon'>
	<link rel='icon' href='../pictures/icons/favicon.ico' type='image/x-icon'>
    <link rel='stylesheet' media='screen' type='text/css' href='../css/style.css'>
    <?php
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false)
		{
			echo "<link rel='stylesheet' media='screen' type='text/css' href='../css/styleOpera.css'>";
		}
	?>

</head>

<body>
    
    <?php
		if(!empty($_REQUEST['error']) and $_REQUEST['error'] == "true" and !empty($_SESSION['error']))
		{
			echo "<div id='adminAuthBig'>";
		}
		else
		{
			echo "<div id='adminAuth'>";
		}
    ?>	
		<center>
        	<span class='headerStyle'>Вход в панель администрирования</span>
        </center>
        <br /><br />
        <form method='post' action='../scripts/checking.php'>
        	<label>Логин:</label>
            <br />
            <input type='text' name='login' class='authInput' <?php if(!empty($_SESSION['login'])) {echo "value='".$_SESSION['login']."'";} ?> />
            <br /><br />
            <label>Пароль:</label>
            <br />
            <input type='password' name='password' class='authInput' />
            <br /><br />
            <center><input type='submit' value='Войти' id='authSubmit' /></center>
        </form>
        
        <?php
		
			if(!empty($_REQUEST['error']) and $_REQUEST['error'] == "true" and !empty($_SESSION['error']))
			{
				echo "
					<br />
				";
				
				switch($_SESSION['error'])
				{
					case "empty":
						echo "<span class='basicRed'>Необходимо заполнить все поля.</span>";
						break;
					case "password":
						echo "<span class='basicRed'>Неверно введён логин или пароль.</span>";
						break;
					default:
						break;
				}
			}
			
		?>
        
    </div>
    
    <?php
	
		if(isset($_SESSION['login']))
		{
			unset($_SESSION['login']);
		}
		
		unset($_SESSION['error']);
		
	?>

</body>

</html>