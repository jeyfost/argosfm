$(document).ready(function(){
	$('#cLeftArrowIMG').mouseover(function(){
		$('#cLeftArrowIMG').attr('src', 'pictures/system/cArrowRedLeft.png');
	})
})

$(document).ready(function(){
	$('#cLeftArrowIMG').mouseout(function(){
		$('#cLeftArrowIMG').attr('src', 'pictures/system/cArrowLeft.png');
	})
})

$(document).ready(function(){
	$('#cRightArrowIMG').mouseover(function(){
		$('#cRightArrowIMG').attr('src', 'pictures/system/cArrowRedRight.png');
	})
})

$(document).ready(function(){
	$('#cRightArrowIMG').mouseout(function(){
		$('#cRightArrowIMG').attr('src', 'pictures/system/cArrowRight.png');
	})
})

function arrowColor(id, img) {
	document.getElementById(id).src = 'pictures/system/' + img;
}

function prevMonth(month, year, curMonth, curYear, selectedDate) {
	$.ajax({
		type: 'POST',
		url: 'scripts/ajaxPrevMonth.php',
		data: {"month": month, "year": year},
		cache: false,
		success: function(response) {
			$('#cMonthText').html(getMonthName(response));

			if(response == '12') {
				year = parseInt(year - 1);
				$('#cYearText').html(year);
			}

			if(!document.getElementById('cRightArrowIMG')) {
				$('#cRightArrow').html("<img src='pictures/system/cArrowRight.png' class='noBorder' id='cRightArrowIMG' onmouseover='arrowColor(\"cRightArrowIMG\", \"cArrowRedRight.png\")' onmouseout='arrowColor(\"cRightArrowIMG\", \"cArrowRight.png\")' onclick='nextMonth(\"" + response + "\", \"" + year + "\", \"" + curMonth + "\", \"" + curYear + "\", \"" + selectedDate + "\")'>");
			} else {
				document.getElementById('cRightArrowIMG').setAttribute('onclick', 'nextMonth("' + response + '", "'+ year + '", "' + curMonth + '", "' + curYear + '", "' + selectedDate + '")')
			}

			document.getElementById('cLeftArrowIMG').setAttribute('onclick', 'prevMonth("' + response + '", "'+ year + '", "' + curMonth + '", "' + curYear + '", "' + selectedDate + '")');

			$.ajax({
				type: 'POST',
				url: 'scripts/ajaxDayOfWeek.php',
				data: {"month": response, "year": year},
				cache: false,
				success: function(days) {
					var day = days.split(';');
					document.getElementById('cDaysNums').innerHTML = '';

					$.ajax({
						type: 'POST',
						url: 'scripts/ajaxNewsByDate.php',
						data: {"month": response, "year": year, "start": day[0], "days": day[1], "selectedDate": selectedDate},
						cache: false,
						success: function(result) {
							$('#cDaysNums').html(result);
						}
					});
				}
			});
		}
	});
}

function nextMonth(month, year, curMonth, curYear, selectedDate) {

	if(year == curYear) {
		if(month < curMonth)
		{
			$.ajax({
				type: 'POST',
				url: 'scripts/ajaxNextMonth.php',
				data: {"month": month, "year": year},
				cache: false,
				success: function(response) {
					$('#cMonthText').html(getMonthName(response));

					if(response == '01' || response == '1') {
						year = parseInt(parseInt(year) + 1);
						$('#cYearText').html(year);
					}


					if(!document.getElementById('cRightArrowIMG')) {
						$('#cRightArrow').html("<img src='pictures/system/cArrowRight.png' class='noBorder' id='cRightArrowIMG' onmouseover='arrowColor(\"cRightArrowIMG\", \"cArrowRedRight.png\")' onmouseout='arrowColor(\"cRightArrowIMG\", \"cArrowRight.png\")' onclick='nextMonth(\"" + response + "\", \"" + year + "\", \"" + curMonth + "\", \"" + curYear + "\", \"" + selectedDate + "\")' />");
					}
					else {
						document.getElementById('cRightArrowIMG').setAttribute('onclick', 'nextMonth("' + response + '", "' + year + '", "' + curMonth + '", "' + curYear + '", "' + selectedDate + '")');
					}

					document.getElementById('cLeftArrowIMG').setAttribute('onclick', 'prevMonth("' + response + '", "'+ year + '", "' + curMonth + '", "' + curYear + '", "' + selectedDate + '")');

					$.ajax({
						type: 'POST',
						url: 'scripts/ajaxDayOfWeek.php',
						data: {"month": response, "year": year},
						cache: false,
						success: function(days) {
							var day = days.split(';');
							document.getElementById('cDaysNums').innerHTML = '';

							$.ajax({
								type: 'POST',
								url: 'scripts/ajaxNewsByDate.php',
								data: {"month": response, "year": year, "start": day[0], "days": day[1], "selectedDate": selectedDate},
								cache: false,
								success: function(result) {
									$('#cDaysNums').html(result);
									if(response == curMonth) {
										$('#cRightArrow').html('');
									}
								}
							});
						}
					});
				}
			});
		}
	}

	if(year < curYear) {

		$.ajax({
			type: 'POST',
			url: 'scripts/ajaxNextMonth.php',
			data: {"month": month, "year": year},
			cache: false,
			success: function(response) {
				$('#cMonthText').html(getMonthName(response));

				if(response == '01' || response == '1') {
					year = parseInt(parseInt(year) + 1);
					$('#cYearText').html(year);
				}

				if(!document.getElementById('cRightArrowIMG')) {
					$('#cRightArrow').html("<img src='pictures/system/cArrowRight.png' class='noBorder' id='cRightArrowIMG' onmouseover='arrowColor(\"cRightArrowIMG\", \"cArrowRedRight.png\")' onmouseout='arrowColor(\"cRightArrowIMG\", \"cArrowRight.png\")' onclick='nextMonth(\"" + response + "\", \"" + year + "\", \"" + curMonth + "\", \"" + curYear + "\", \"" + selectedDate + "\")' />");
				} else {
					document.getElementById('cRightArrowIMG').setAttribute('onclick', 'nextMonth("' + response + '", "' + year + '", "' + curMonth + '", "' + curYear + '", "' + selectedDate + '")');
				}

				document.getElementById('cLeftArrowIMG').setAttribute('onclick', 'prevMonth("' + response + '", "'+ year + '", "' + curMonth + '", "' + curYear + '", "' + selectedDate + '")');

				$.ajax({
					type: 'POST',
					url: 'scripts/ajaxDayOfWeek.php',
					data: {"month": response, "year": year},
					cache: false,
					success: function(days) {
						var day = days.split(';');
						document.getElementById('cDaysNums').innerHTML = '';

						$.ajax({
							type: 'POST',
							url: 'scripts/ajaxNewsByDate.php',
							data: {"month": response, "year": year, "start": day[0], "days": day[1], "selectedDate": selectedDate},
							cache: false,
							success: function(result) {
								$('#cDaysNums').html(result);
							}
						});
					}
				});
			}
		});
	}
}

function getMonthName(month)
{
	switch(month)
	{
		case "01":
			return "Январь";
			break;
		case "02":
			return "Февраль";
			break;
		case "03":
			return "Март";
			break;
		case "04":
			return "Апрель";
			break;
		case "05":
			return "Май";
			break;
		case "06":
			return "Июнь";
			break;
		case "07":
			return "Июль";
			break;
		case "08":
			return "Август";
			break;
		case "09":
			return "Сентябрь";
			break;
		case "10":
			return "Октябрь";
			break;
		case "11":
			return "Ноябрь";
			break;
		case "12":
			return "Декабрь";
			break;
		default:
			break;
	}
}

function buttonColor(action, block, text) {
	if(action == '0') {
		document.getElementById(block).style.backgroundColor = '#dddddd';
		document.getElementById(text).style.color = '#df4e47';
	}

	if(action == '1') {
		document.getElementById(block).style.backgroundColor = '#df4e47';
		document.getElementById(text).style.color = '#ffffff';
	}
}

function deleteIcon(action, id) {
	if(action == 1) {
		document.getElementById(id).src = 'pictures/system/deleteDarkRed.png';
	}

	if(action == 0) {
		document.getElementById(id).src = 'pictures/system/deleteRed.png';
	}
}

function helpText(action, id) {
	if(action == 1) {
		document.getElementById(id).style.color = '#b3413c';
	}

	if(action == 0) {
		document.getElementById(id).style.color = '#df4e47';
	}
}