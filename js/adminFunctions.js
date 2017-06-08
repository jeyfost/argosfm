var files;

function checkboxClick(id) {
	if(document.getElementById(id).checked) {
		document.getElementById(id).checked = false;
	}
	else {
		document.getElementById(id).checked = true;
	}
}

function addressField() {
	if(document.getElementById('addressGroupSelect')) {
		hideField();
	}

	if(!document.getElementById('addressFieldInput')) {
		$('#addressField').html("<br /><br /><label class='admLabel'>Введите адрес получателя:</label><br /><input type='text' class='admInput' name='emailAddress' id='addressFieldInput' onkeyup='validateClientEmail()' />");
	}
}

function addressGroup() {
	if(!document.getElementById('addressFieldInput')) {
		hideField();
	}

	if(!document.getElementById('addressGroupSelect')) {
		$.ajax({
			type: "POST",
			url: "../scripts/admin/ajaxSelectRegion.php",
			data: {"region": 6},
			success: function(response) {
				$('#addressField').html(response);
				$('.admSubmit').hide();
			}
		});
	}
}

function hideField() {
	if($('#addressFieldInput') || $('#addressGroupSelect')) {
		$('#addressField').html("");
	}
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

function editEmail(id, block) {
	$.ajax({
		type: 'POST',
		data: {"clientID": id},
		url: '../scripts/admin/ajaxGetClientEmail.php',
		success: function(response) {
			document.getElementById(block).innerHTML = "<form name='editEmail' method='post'><input type='text' name='editEmail' id='editEmailInput' class='admInput' onblur='saveEmail(\"" + id + "\", \"emailBlock" + id + "\")' onkeyup='editClientEmail()' value='" + response + "' /></form>";
			document.getElementById('editEmailInput').focus();
		}
	});
}

function editName(id, block) {
	$.ajax({
		type: 'POST',
		data: {"clientID": id},
		url: '../scripts/admin/ajaxGetClientName.php',
		success: function(response) {
			document.getElementById(block).innerHTML = "<form name='editName' method='post'><input type='text' name='editName' id='editNameInput' class='admInput' onblur='saveName(\"" + id + "\", \"nameBlock" + id + "\")' onkeyup='editClientName()' value='" + response + "' /></form>";
			document.getElementById('editNameInput').focus();
		}
	});
}

function editPhone(id, block) {
	$.ajax({
		type: 'POST',
		data: {"clientID": id},
		url: '../scripts/admin/ajaxGetClientPhone.php',
		success: function(response) {
			document.getElementById(block).innerHTML = "<form name='editPhone' method='post'><input type='text' name='editPhone' id='editPhoneInput' class='admInput' onblur='savePhone(\"" + id + "\", \"phoneBlock" + id + "\")' value='" + response + "' /></form>";
			document.getElementById('editPhoneInput').focus();
		}
	});
}

function editNotes(id, block) {
	$.ajax({
		type: 'POST',
		data: {"clientID": id},
		url: '../scripts/admin/ajaxGetClientNotes.php',
		success: function(response) {
			response = response.replace(/<br>/g, "\n");
			document.getElementById(block).innerHTML = "<form name='editNotes' method='post'><textarea class='admTextarea' name='editNotes' id='editNotesInput' onblur='saveNotes(\"" + id + "\", \"notesBlock" + id + "\")'>" + response.replace("\n", "\n") + "</textarea></form>";
			document.getElementById('editNotesInput').focus();
		}
	});
}

function editLocation(id, location, block) {
	var content = "<form name='editLocationForm' method='post'><select class='admSelect' name='editLocation' id='editLocationSelect' onblur='saveLocation(\"" + id + "\", \"" + block + "\")' onchange='saveLocation(\"" + id + "\", \"" + block + "\")'>";

	if(location == 1) {
		content += "<option value='1' selected='selected'>Брестская</option>";
	} else {
		content += "<option value='1'>Брестская</option>";
	}

	if(location == 2) {
		content += "<option value='2' selected='selected'>Витебская</option>";
	} else {
		content += "<option value='2'>Витебская</option>";
	}

	if(location == 3) {
		content += "<option value='3' selected='selected'>Гомельская</option>";
	} else {
		content += "<option value='3'>Гомельская</option>";
	}

	if(location == 4) {
		content += "<option value='4' selected='selected'>Гродненская</option>";
	} else {
		content += "<option value='4'>Гродненская</option>";
	}

	if(location == 5) {
		content += "<option value='5' selected='selected'>Минская</option>";
	} else {
		content += "<option value='5'>Минская</option>";
	}

	if(location == 6) {
		content += "<option value='6' selected='selected'>Могилёвская</option>";
	} else {
		content += "<option value='6'>Могилёвская</option>";
	}

	if(location == 7) {
		content += "<option value='7' selected='selected'>Другая</option>";
	} else {
		content += "<option value='7'>Другая</option>";
	}

	if(location == 8) {
		content += "<option value='8' selected='selected'>Не определено</option>";
	} else {
		content += "<option value='8'>Не определено</option>";
	}

	content += "</select></form>";

	document.getElementById(block).innerHTML = content;
	document.getElementById("editLocationSelect").focus();
}

function saveLocation (id, block) {
	var location = $('#editLocationSelect').val();
	var name  = $('#editLocationSelect option:selected').text();
	if(location != 0) {
		$.ajax({
			type: 'POST',
			data: {"locationID": id, "location": location},
			url: '../scripts/admin/ajaxLocation.php',
			success: function(response) {
				if(response == "a") {
					document.getElementById(block).innerHTML = "<span class='admULFont' style='cursor: pointer;' title='Изменить местонахождение' onclick='editLocation(\"" + id + "\", \"" + location + "\", \"" + block + "\")'>" + name + "</span>";
				}

				if(response == "b") {

				}
			}
		});
	}
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

function savePhone(id, block) {
	var phone = $('#editPhoneInput').val();

	$.ajax({
		type: 'POST',
		data: {"phone": phone, "emailID": id},
		url: '../scripts/admin/ajaxPhone.php',
		success: function(response) {
			if(response == "a") {
				document.getElementById(block).innerHTML = "<span class='admULFont' style='cursor: pointer;' onclick='editPhone(\"" + id + "\", \"" + phone.replace(/\\?("|')/g, '\\$1') + "\", \'phoneBlock" + id + "\")' title='Изменить номер телефона'>" + phone + "</span>";
			}
		}
	});
}

function saveNotes(id, block) {
	var notes = $('#editNotesInput').val();

	$.ajax({
		type: 'POST',
		data: {"notes": notes, "emailID": id},
		url: '../scripts/admin/ajaxNotes.php',
		success: function(response) {
			if(response == "a") {
				document.getElementById(block).innerHTML = "<span class='admULFont' style='cursor: pointer;' onclick='editNotes(\"" + id +"\", \"" + notes.replace(/\\?("|')/g, '\\$1') + "\", \"notesBlock" + id + "\")' title='Редактировать заметки'>" + notes + "</span>";
			}
		}
	});
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
			$('#mailTextBlock').html(response + '<br /><br /><span class="basicRed" style="border-bottom: 1px dotted #df4e47; cursor: pointer; float: right;" onclick="hideMailText()">Закрыть</span>');
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

function showFailedEmails(id) {
	$.ajax({
		type: 'POST',
		url: '../scripts/admin/ajaxFailedEmails.php',
		data: {"id": id},
		success: function(response) {
			$('#mailTextBlock').html('<span class="admLabel">' + response + '</span><br /><br /><span class="basicRed" style="border-bottom: 1px dotted #df4e47; cursor: pointer; float: right;" onclick="hideMailText()">Закрыть</span>');
			$('#mailTextBlock').css('z-index', '100');
			$('#mailTextBlock').css('display', 'block');
			$('#mailTextBlock').css('opacity', '1');
			$('#admContent').css('overflow', 'visible');
		}
	});
}

function sendPartly(parameter, region, id) {
	var response_field = $('#responseField');

	if($('#emailThemeInput').val() != '') {
		if($('.nicEdit-main').html() != '' && $('.nicEdit-main').html() != '<br>') {
			var formData = new FormData($('#emailSendForm').get(0));
			formData.append("text", $('.nicEdit-main').html());
			formData.append("region", region);
			formData.append("parameter", parameter);

			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				processData: false,
				contentType: false,
				url: "../scripts/admin/ajaxSendEmailPartly.php",
				beforeSend: function() {
					if(response_field.css('opacity') == 1) {
						response_field.css('opacity', '0');
						setTimeout(function() {
							response_field.html('<img src="../pictures/system/preloader.gif" /><br /><br />');
							response_field.css('opacity', '1');
						}, 300);
					} else {
						response_field.html('<img src="../pictures/system/preloader.gif" /><br /><br />');
						response_field.css('opacity', '1');
					}
				},
				success: function(response) {
					switch(response) {
						case "a":
							if(response_field.css('opacity') == 1) {
								response_field.css('opacity', '0');
								setTimeout(function() {
									response_field.css('color', '#53acff');
									response_field.html('Письма были успешно отправлены.<br /><br />');
									response_field.css('opacity', '1');
								}, 300);
							} else {
								response_field.css('color', '#53acff');
								response_field.html('Письма были успешно отправлены.<br /><br />');
								response_field.css('opacity', '1');
							}

							document.getElementById(id).setAttribute('class', 'sendEmailButtonActive');
							document.getElementById(id).removeAttribute('onclick');
							break;
						case "b":
							if(response_field.css('opacity') == 1) {
								response_field.css('opacity', '0');
								setTimeout(function() {
									response_field.css('color', '#df4e47');
									response_field.html('Произошла ошибка. Попробуйте снова.<br /><br />');
									response_field.css('opacity', '1');
								}, 300);
							} else {
								response_field.css('color', '#df4e47');
								response_field.html('Произошла ошибка. Попробуйте снова.<br /><br />');
								response_field.css('opacity', '1');
							}
							break;
						case "c":
							if(response_field.css('opacity') == 1) {
								response_field.css('opacity', '0');
								setTimeout(function() {
									response_field.css('color', '#df4e47');
									response_field.html('Не все письма были отправлены.<br /><br />');
									response_field.css('opacity', '1');
								}, 300);
							} else {
								response_field.css('color', '#df4e47');
								response_field.html('Не все письма были отправлены.<br /><br />');
								response_field.css('opacity', '1');
							}

							document.getElementById(id).setAttribute('class', 'sendEmailButtonActive');
							document.getElementById(id).removeAttribute('onclick');
							break;
						case "files":
							if(response_field.css('opacity') == 1) {
								response_field.css('opacity', '0');
								setTimeout(function() {
									response_field.css('color', '#df4e47');
									response_field.html('В файлах найдена ошибка.<br /><br />');
									response_field.css('opacity', '1');
								}, 300);
							} else {
								response_field.css('color', '#df4e47');
								response_field.html('В файлах найдена ошибка.<br /><br />');
								response_field.css('opacity', '1');
							}

							document.getElementById(id).setAttribute('class', 'sendEmailButtonActive');
							document.getElementById(id).removeAttribute('onclick');
							break;
						default:
							if(response_field.css('opacity') == 1) {
								response_field.css('opacity', '0');
								setTimeout(function() {
									response_field.css('color', '#df4e47');
									response_field.html('Письма были отправлены.<br /><br />');
									response_field.css('opacity', '1');
								}, 300);
							} else {
								response_field.css('color', '#df4e47');
								response_field.html('Письма были отправлены.<br /><br />');
								response_field.css('opacity', '1');
							}

							document.getElementById(id).setAttribute('class', 'sendEmailButtonActive');
							document.getElementById(id).removeAttribute('onclick');
							break;
					}
				}
			});
		} else {
			if(response_field.css('opacity') == 1) {
				response_field.css('opacity', '0');
				setTimeout(function() {
					response_field.css('color', '#df4e47');
					response_field.html('Вы не ввели текст письма.<br /><br />');
					response_field.css('opacity', '1');
				}, 300);
			} else {
				response_field.css('color', '#df4e47');
				response_field.html('Вы не ввели текст письма.<br /><br />');
				response_field.css('opacity', '1');
			}
		}
	} else {
		if(response_field.css('opacity') == 1) {
			response_field.css('opacity', '0');
			setTimeout(function() {
				response_field.css('color', '#df4e47');
				response_field.html('Вы не ввели тему письма.<br /><br />');
				response_field.css('opacity', '1');
			}, 300);
		} else {
			response_field.css('color', '#df4e47');
			response_field.html('Вы не ввели тему письма.<br /><br />');
			response_field.css('opacity', '1');
		}
	}

	//alert($('.nicEdit-main').html());
}

function selectRegion() {
	$.ajax({
		type: "POST",
		data: {"region": $('#addressGroupSelect').val()},
		url: "../scripts/admin/ajaxSelectRegion.php",
		success: function(response) {
			$('#addressField').html(response);
			$('.admSubmit').hide();
		}
	});
}

$('input[type=file]').change(function() {
	files = this.files;
});