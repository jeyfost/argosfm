function menuVisual (action, png, stripe) {
	if(action) {
		switch (png) {
			case 'mpIMG':
				document.getElementById(png).src = 'pictures/system/mainTextRed.png';
				document.getElementById(stripe).style.backgroundColor = '#df4e47';
				break;
			case 'cpIMG':
				document.getElementById(png).src = 'pictures/system/catalogueTextRed.png';
				document.getElementById(stripe).style.backgroundColor = '#df4e47';
				break;
			case 'csIMG':
				document.getElementById(png).src = 'pictures/system/contactsTextRed.png';
				document.getElementById(stripe).style.backgroundColor = '#df4e47';
				break;
			case 'opIMG':
				document.getElementById(png).src = 'pictures/system/newsTextRed.png';
				document.getElementById(stripe).style.backgroundColor = '#df4e47';
				break;
			default:
				break;
		}
	}
}

function menuDefault1() {
	document.getElementById('mpTop').style.backgroundColor = '#ffffff';
	document.getElementById('mpIMG').src = 'pictures/system/mainText.png';
}

function menuDefault2() {
	document.getElementById('cpTop').style.backgroundColor = '#ffffff';
	document.getElementById('cpIMG').src = 'pictures/system/catalogueText.png';
}

function menuDefault3() {
	document.getElementById('opTop').style.backgroundColor = '#ffffff';
	document.getElementById('opIMG').src = 'pictures/system/newsText.png';
}

function menuDefault4() {
	document.getElementById('csTop').style.backgroundColor = '#ffffff';
	document.getElementById('csIMG').src = 'pictures/system/contactsText.png';
}

function changeLoginIcon(action) {
	if(action) {
		if(!document.getElementById('loginIcon').hasAttribute('name')) {
			document.getElementById('loginIcon').src = 'pictures/system/loginRed.png';
		}
	}
	else {
		if(!document.getElementById('loginIcon').hasAttribute('name')) {
			document.getElementById('loginIcon').src = 'pictures/system/login.png';
		}
	}
}

function changeUserIcon(action) {
	if(action) {
		if(!document.getElementById('userIcon').hasAttribute('name')) {
			document.getElementById('userIcon').src = 'pictures/system/userRed.png';
		}
	}
	else {
		if(!document.getElementById('userIcon').hasAttribute('name')) {
			document.getElementById('userIcon').src = 'pictures/system/user.png';
		}
	}
}

function changeExitIcon(action) {
	if(action) {
		document.getElementById('exitIcon').src = 'pictures/system/exitRed.png';
	}
	else {
		document.getElementById('exitIcon').src = 'pictures/system/exit.png';
	}
}

function changeBasketIcon(action) {
	if(action) {
		document.getElementById('basketIcon').src = 'pictures/system/basketRed.png';
	}
	else {
		document.getElementById('basketIcon').src = 'pictures/system/basket.png';
	}
}

function changeBasketFullIcon(action) {
	if(action) {
		document.getElementById('basketIcon').src = 'pictures/system/basketFullRed.png';
	}
	else {
		document.getElementById('basketIcon').src = 'pictures/system/basketFull.png';
	}
}

function toBasket(action, id) {
	if(action) {
		document.getElementById(id).src='pictures/system/toBasketHover.png'
	}
	else {
		document.getElementById(id).src='pictures/system/toBasket.png'
	}
}

function closeNotification() {
	document.getElementById('layout').setAttribute('style', 'display: none;');
	if(document.getElementById('notificationWindowOuter')) {
		document.getElementById('notificationWindowOuter').setAttribute('style', 'display: none;');
	}
	if(document.getElementById('notificationRegistrationWindowOuter')) {
		document.getElementById('notificationRegistrationWindowOuter').setAttribute('style', 'display: none;');
	}
}

function resetBlocks() {
	document.getElementById('loginIcon').removeAttribute('name');
	document.getElementById('loginIcon').src = 'pictures/system/login.png';
	
	document.getElementById('userLogin').value = '';
	document.getElementById('userPassword').value = '';
	document.getElementById('userNameInput').value = '';
	document.getElementById('userPhoneInput').value = '';
	document.getElementById('userLoginInput').value = '';
	document.getElementById('userPasswordInput').value = '';
	document.getElementById('userEmailInput').value = '';
	document.getElementById('recoveryInput').value = '';
	
	if(document.getElementById('organisationInput')) {
		document.getElementById('organisationInput').value = '';
	}

	if(document.getElementById('layout')) {
		document.getElementById('layout').setAttribute('style', 'display: none;');
	}
	
	if(document.getElementById('loginBlock')) {
		document.getElementById('loginBlock').setAttribute('style', 'display: none;');
	}
	
	if(document.getElementById('registrationWindow')) {
		document.getElementById('registrationWindow').setAttribute('style', 'display: none;');
	}
	
	if(document.getElementById('passwordRecoveryBlock')) {
		document.getElementById('passwordRecoveryBlock').setAttribute('style', 'display: none;');
	}
	
	if(document.getElementById('notificationWindowOuter')) {
		document.getElementById('notificationWindowOuter').setAttribute('style', 'display: none;');
	}
	
	if(document.getElementById('notificationRegistrationWindowOuter')) {
		document.getElementById('notificationRegistrationWindowOuter').setAttribute('style', 'display: none;');
	}
}

function showLoginForm() {
	if(!document.getElementById('loginIcon').hasAttribute('name')) {
		document.getElementById('loginIcon').setAttribute('name', 'activeIcon');
		document.getElementById('layout').setAttribute('style', 'display: block;');

		var scrollHeight = Math.max(
		  document.body.scrollHeight, document.documentElement.scrollHeight,
		  document.body.offsetHeight, document.documentElement.offsetHeight,
		  document.body.clientHeight, document.documentElement.clientHeight
		);

		$('#layout').height(scrollHeight); 
		document.getElementById('loginBlock').setAttribute('style', 'display: block;');
	}
	else {
		document.getElementById('loginIcon').removeAttribute('name');
	}
}

function registrationWindow() {
	document.getElementById('loginBlock').setAttribute('style', 'display: none;');
	document.getElementById('registrationWindow').setAttribute('style', 'display: block');
}

function recoveryWindow() {
	document.getElementById('loginBlock').setAttribute('style', 'display: none;');
	document.getElementById('passwordRecoveryBlock').setAttribute('style', 'display: block');
}

function registrationType(n) {
	if(n == 1) {
		document.getElementById('registrationWindow').innerHTML = "<form name='registrationForm' id='registrationForm' method='post' action='scripts/registration.php'><center><span class='headerStyleRed'>Регистрация нового пользователя</span></center><br /><br /><label>Тип пользователя:</label><br /><input type='radio' name='userType' value='1' class='radio' onclick='registrationType(1)' checked><span class='mainIMGText'>Организация</span><br /><input type='radio' name='userType' value='2' class='radio' onclick='registrationType(2)'><span class='mainIMGText'>Физическое лицо</span><br /><br /><label>Логин: </label><span class='hintText' id='hintLogin' title='Логин должен состоять минимум из 3 латинских букв, цифр или допустимых символов.'>(подсказка)</span><br /><input type='text' class='admInput' name='userLogin' id='userLoginInput' onkeyup='loginCheck();' /><br /><br /><label>Пароль: </label><span class='hintText' id='hintPassword' title='Пароль должен содержать минимум 5 символов.'>(подсказка)</span><br /><input type='password' class='admInput' name='userPassword' id='userPasswordInput' onkeyup='passwordCheck();' /><br /><br /><label>E-mail:</label><br /><input type='text' class='admInput' name='userEmail' id='userEmailInput' onkeyup='emailCheck();' /><br /><br /><label>Название организации:</label><br /><input type='text' class='admInput' name='organisation' id='organisationInput' onkeyup='organisationCheck();' /><br /><br /><label>Контактное лицо:</label><br /><input type='text' class='admInput' name='userName' id='userNameInput' onkeyup='nameCheck();' /><br /><br /><label>Контактный телефон: </label><span class='hintText' id='hintPhone' title='Номер телефона желательно указывать в международном формате: +375 (XX) XXXXXXX'>(подсказка)</span><br /><input type='text' class='admInput' name='userPhone' id='userPhoneInput' onkeyup='phoneCheck();' /><br /><br /><br /><input type='submit' class='windowSubmit' value='Зарегистрироваться' id='registrationSubmit' /><input type='button' class='windowSubmit' value='Отмена' id='loginCancel' onclick='resetBlocks();' /></form>";
	}
	
	if(n == 2) {
		document.getElementById('registrationWindow').innerHTML = "<form name='registrationForm' id='registrationForm' method='post' action='scripts/registration.php'><center><span class='headerStyleRed'>Регистрация нового пользователя</span></center><br /><br /><label>Тип пользователя:</label><br /><input type='radio' name='userType' value='1' class='radio' onclick='registrationType(1)'><span class='mainIMGText'>Организация</span><br /><input type='radio' name='userType' value='2' class='radio' onclick='registrationType(2)' checked><span class='mainIMGText'>Физическое лицо</span><br /><br /><label>Логин: </label><span class='hintText' id='hintLogin' title='Логин должен состоять минимум из 3 латинских букв, цифр или допустимых символов.'>(подсказка)</span><br /><input type='text' class='admInput' name='userLogin' id='userLoginInput' onkeup='loginCheck();' /><br /><br /><label>Пароль: </label><span class='hintText' id='hintPassword' title='Пароль должен содержать минимум 5 символов.'>(подсказка)</span><br /><input type='password' class='admInput' name='userPassword' id='userPasswordInput' onkeyup='passwordCheck();' /><br /><br /><label>E-mail:</label><br /><input type='text' class='admInput' name='userEmail' id='userEmailInput' onkeyup='emailCheck();' /><br /><br /><label>Имя:</label><br /><input type='text' class='admInput' name='userName' id='userNameInput' onkeyup='nameCheck();' /><br /><br /><label>Контактный телефон: </label><span class='hintText' id='hintPhone2' title='Номер телефона желательно указывать в международном формате: +375 (XX) XXXXXXX'>(подсказка)</span><br /><input type='text' class='admInput' name='userPhone' id='userPhoneInput' onkeyup='phoneCheck();' /><br /><br /><br /><br /><br /><br /><br /><br /><input type='submit' class='windowSubmit' value='Зарегистрироваться' id='registrationSubmit' /><input type='button' class='windowSubmit' value='Отмена' id='loginCancel' onclick='resetBlocks();' /></form>";
	}
}

function settingsMenuButton(action, id, text) {
	if(action == 1) {
		document.getElementById(id).style.backgroundColor = '#df4e47';
		document.getElementById(text).style.color = '#ffffff';
	}
	if(action == 0) {
		document.getElementById(id).style.backgroundColor = '#ffffff';
		document.getElementById(text).style.color = '#3f3f3f';
	}
}

function validateQuantity(id) {
	if(document.getElementById(id).value < 1 || Math.ceil(document.getElementById(id).value) - document.getElementById(id).value != 0) {
		document.getElementById(id).style.border = '2px solid #df4e47';
	}
	else {
		document.getElementById(id).style.border = '1px solid #3f3f3f';
	}
}

function changeX(action, id) {
	if(action == 1) {
		document.getElementById(id).src = 'pictures/system/xHover.png';
	}
	if(action == 0) {
		document.getElementById(id).src = 'pictures/system/x.png';
	}
}

function correction() {
	var content = jQuery('#content_main');
	var block = jQuery('#fa_main_block');
	
	if(content.height() < block.height()) {
		content.height(block.height());
	}
}

function changePictures(id, fileRed, file, action) {
	if(action) {
		document.getElementById(id).src = 'pictures/system/' + fileRed;
	}
	else {
		document.getElementById(id).src = 'pictures/system/' + file;
	}
}

function resizeLayout() {
	var scrollHeight = Math.max(
		document.body.scrollHeight, document.documentElement.scrollHeight,
		document.body.offsetHeight, document.documentElement.offsetHeight,
		document.body.clientHeight, document.documentElement.clientHeight
	);

	$('#layout').height(scrollHeight); 
}

function logoChange(action) {
	if(action == 1) {
		document.getElementById('logoIMG').src = 'pictures/system/logoDark.png';
	}

	if(action == 0) {
		document.getElementById('logoIMG').src = 'pictures/system/logo.png';
	}
}