function checkboxClick(id) {
	if(document.getElementById(id).checked) {
		document.getElementById(id).checked = false;
	}
	else {
		document.getElementById(id).checked = true;
	}
}

function addressField() {
	if(!document.getElementById('addressFieldInput')) {
		$('#addressField').html("<br /><br /><label class='admLabel'>Введите адрес получателя:</label><br /><input type='text' class='admInput' name='emailAddress' id='addressFieldInput' />");
	}
}

function hideField() {
	$('#addressField').html("");
}

function admPageBlock(action, block, text) {
	if(action == '1')
	{
		document.getElementById(block).style.backgroundColor = '#df4e47';
		document.getElementById(text).style.color = '#ffffff';
	}
	else
	{
		document.getElementById(block).style.backgroundColor = '#ffffff';
		document.getElementById(text).style.color = '#df4e47';
	}
}

function mailIcon(action, id) {
	if(action == '1') {
		document.getElementById(id).src = '../pictures/system/crossRed.png';
	} else {
		document.getElementById(id).src = '../pictures/system/cross.png';
	}
}

function editIcon(action, id) {
	if(action == '1') {
		document.getElementById(id).src = '../pictures/system/admEditRed.png';
	} else {
		document.getElementById(id).src = '../pictures/system/admEdit.png';
	}
}

function editEmail(id, email, block) {
	document.getElementById(block).innerHTML = "<form name='editEmail' method='post'><input type='text' name='editEmail' id='editEmailInput' class='admInput' onblur='saveEmail(\"" + id + "\", \"emailBlock" + id + "\")' value='" + email + "' /></form>";
	document.getElementById('editEmailInput').focus();
}

function editName(id, name, block) {
	document.getElementById(block).innerHTML = "<form name='editName' method='post'><input type='text' name='editName' id='editNameInput' class='admInput' onblur='saveName(\"" + id + "\", \"nameBlock" + id + "\")' value='" + name + "' /></form>";
	document.getElementById('editNameInput').focus();
}

function saveEmail(id, block) {
	var email = $('#editEmailInput').val();

	if(email.length == 0) {
		$('#editEmailInput').css('border', '1px solid #df4e47');
		$('#editEmailInput').css('background-color', '#ffb1ad');
	}
	else {
		$.ajax({
			type: 'POST',
			cache: false,
			data: {"email": email, "emailID": id},
			url: '../scripts/admin/ajaxEmail.php',
			success: function(response) {
				if(response == "a") {
					document.getElementById(block).innerHTML = "<span class='admULFont' style='cursor: pointer;' onclick='editEmail(\"" + id + "\", \"" + email + "\", \"emailBlock" + id + "\")' title='Редактировать e-mail'>" + email + "</span>";
				}

				if(response == "b") {
					$('#editEmailInput').css('border', '1px solid #df4e47');
					$('#editEmailInput').css('background-color', '#ffb1ad');
				}
			}
		});
		return false;
	}
}

function saveName(id, block) {
	var name = $('#editNameInput').val();

	if(name.length == 0) {
		$('#editNameInput').css('border', '1px solid #df4e47');
		$('#editNameInput').css('background-color', '#ffb1ad');
	}
	else {
		$.ajax({
			type: 'POST',
			cache: false,
			data: {"name": name, "emailID": id},
			url: '../scripts/admin/ajaxName.php',
			success: function(response) {
				if(response == "a") {
					document.getElementById(block).innerHTML = "<span class='admULFont' style='cursor: pointer;' onclick='editName(\"" + id + "\", \"" + name.replace(/\\?("|')/g, '\\$1') + "\", \"nameBlock" + id + "\")' title='Редактировать имя / название организации'>" + name + "</span>";
				}

				if(response == "b") {
					$('#editNameInput').css('border', '1px solid #df4e47');
					$('#editNameInput').css('background-color', '#ffb1ad');
				}
			}
		});
		return false;
	}
}

function randomPassword() {
	var symbols = new Array(1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 'q', 'w', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M');

	var count = symbols.length;
	var password = '';

	for(var i = 0; i < 16; i++) {
		password += symbols[Math.floor(Math.random() * count)];
	}

	document.getElementById('userPasswordInput').value = password;
}

function buttonColor(action, block, text) {
	if(action == '1') {
		document.getElementById(block).style.backgroundColor = '#dddddd';
		document.getElementById(text).style.color = '#df4e47';
	}

	if(action == '0') {
		document.getElementById(block).style.backgroundColor = '#df4e47';
		document.getElementById(text).style.color = '#ffffff';
	}
}

function showMailText(id) {
	$.ajax({
		type: 'POST',
		url: '../scripts/admin/ajaxMailText.php',
		data: {"id": id},
		cache: false,
		success: function(response) {
			$('#mailTextBlock').html('<span class="admLabel">' + response + '</span><br /><br /><span class="basicRed" style="border-bottom: 1px dotted #df4e47; cursor: pointer; float: right;" onclick="hideMailText()">Закрыть</span>');
			$('#mailTextBlock').css('z-index', '100');
			$('#mailTextBlock').css('display', 'block');
			$('#mailTextBlock').css('opacity', '1');
			$('#admContent').css('overflow', 'visible');
		}
	});
}

function hideMailText() {
	$('#mailTextBlock').html('');
	$('#mailTextBlock').css('z-index', '0');
	$('#mailTextBlock').css('display', 'none');
	$('#mailTextBlock').css('opacity', '0');
	$('#admContent').css('overflow', 'hidden');
}