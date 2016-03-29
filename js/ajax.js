function addToBasket(id) {
	var qID = 'quantity' + id;
	var nID = 'note' + id;

	if(document.getElementById(qID).value < 1 || Math.ceil(document.getElementById(qID).value) - document.getElementById(qID).value != 0) {
		document.getElementById(nID).innerHTML = "<span class='basicRed'>Неверно введено количетсво!</span>";
	}
	else {
		$.ajax({
			type: 'POST',
			url: 'scripts/addToBasket.php?id=' + id + '&q=' + document.getElementById(qID).value,
			cache: false,
			success: function(response) {
					document.getElementById(nID).innerHTML = response;
					if(document.getElementById('basketIcon').src = 'pictures/system/basket.png') {
						document.getElementById('basketIcon').src = 'pictures/system/basketFull.png';
						document.getElementById('basketIcon').setAttribute('onmouseover', 'changeBasketFullIcon(1)');
						document.getElementById('basketIcon').setAttribute('onmouseout', 'changeBasketFullIcon(0)');
					}

					var f = $('footer').offset().top + $('footer').height();
					var c = $('#catalogueGoods').offset().top + $('#catalogueGoods').height();

					if(f > parseInt(c + 50)) {
						$('footer').offset({top: parseInt(c + 50)});
					}
				}
		});
	}
}

function editBasketGood(id, price, rate, total, quantity) {
	var qID = 'quantity' + id;
	var nID = 'note' + id;
	var pID = 'price' + id;
	
	price = parseFloat(price);
	rate = parseInt(rate);
	total = parseInt(total);
	quantity = parseInt(quantity);

	if(document.getElementById(qID).value < 1 || Math.ceil(document.getElementById(qID).value) - document.getElementById(qID).value != 0) {
		document.getElementById(qID).style.border = '2px solid #df4e47';
	}
	else {
		document.getElementById(qID).style.border = '1px solid #3f3f3f';
	}
	
	if(document.getElementById(qID).value < 1 || Math.ceil(document.getElementById(qID).value) - document.getElementById(qID).value != 0) {
		document.getElementById(nID).innerHTML = "<span class='basicRed'>Неверно введено количетсво!</span>";
	} else {
		if(quantity > document.getElementById(qID).value) {
			var newTotalPrice = parseInt(total-(price*rate*Math.abs(quantity-document.getElementById(qID).value)));
		}
		
		if(quantity < document.getElementById(qID).value) {
			var newTotalPrice = parseInt(total+(price*rate*Math.abs(document.getElementById(qID).value-quantity)));
		}
		
		if(quantity == document.getElementById(qID).value) {
			var newTotalPrice = total;
		}
		
		$.ajax({
			type: 'POST',
			url: 'scripts/editBasketGood.php?id=' + id + '&q=' + document.getElementById(qID).value,
			cache: false,
			success: function(response) {
				document.getElementById(nID).innerHTML = response;
				document.getElementById(pID).innerHTML = rate * price * document.getElementById(qID).value;
				
				$.ajax({
					type: 'POST',
					url: 'scripts/ajaxCalculatePrice.php',
					success: function(newPrice) {
						document.getElementById('totalPrice').innerHTML = newPrice;
					}
				});
			}
		});
	}
}

function showDetails(order_id, text_id, field_id) {
	if(document.getElementById(text_id).hasAttribute('name')) {
		document.getElementById(text_id).removeAttribute('name');
		document.getElementById(field_id).innerHTML = '';
		
	} else {
		document.getElementById(text_id).setAttribute('name', 'active');

		$.ajax({
			type: 'POST',
			url: 'scripts/orderDetailedInfo.php?id=' + order_id,
			cache: false,
			success: function(response) {
				document.getElementById(field_id).innerHTML = response;
			}
		});
		
		var scrollHeight = document.documentElement.scrollHeight;
		var clientHeight = document.documentElement.clientHeight;
		var footer = jQuery('#footerOrder');
		
		scrollHeight = Math.max(scrollHeight, clientHeight);
		
		if(scrollHeight <= clientHeight) {
			footer.offset({top: scrollHeight - 70});
		}
		else {
			footer.offset({top: scrollHeight});
		}
	}
}

function deleteOrder(id) {
	$.ajax({
		type: 'POST',
		url: 'scripts/deleteOrder.php?id=' + id,
		cache: false,
		success: function(response) {
			document.getElementById('newOrders').innerHTML = response;
		}
	});
}

function cancelOrder(id) {
	$.ajax({
		type: 'POST',
		url: 'scripts/cancelOrder.php?id=' + id,
		cache: false,
		success: function(response) {
			document.getElementById('orderListBlock').innerHTML = response;
		}
	});
}

function acceptOrder(id) {
	$.ajax({
		type: 'POST',
		url: 'scripts/acceptOrder.php?id=' + id,
		cache: false,
		success: function(response) {
			document.getElementById('orderListBlock').innerHTML = response;
		}
	});
}

function showForm(block, price, good, rate) {
	var id = "goodPriceInput" + good;

	if(!document.getElementById(id)) {
		document.getElementById(block).innerHTML = "<form method='post'><input type='number' class='admInput' name='goodPrice' id='goodPriceInput" + good + "' step='0.001' min='0.001' style='width: 100%;' onblur='changePrice(\"" + block + "\", \"" + good + "\", \"goodPriceInput" + good + "\", \"" + rate + "\")' value='" + price + "' /></form>";
		document.getElementById(id).focus();
	}
}

function changePrice(block, good, input, rate) {
	var price = document.getElementById(input).value;

	$.ajax({
		type: 'POST',
		url: 'scripts/ajaxEditGoodPrice.php',
		cache: false,
		data: {"goodID": good, "goodPrice": price},
		success: function(response) {
			if(response == "a") {
				document.getElementById(block).innerHTML = "<span class='basic'><b>Цена: </b>" + parseInt(price * rate) + " бел. руб.</span>";
				document.getElementById(block).setAttribute("onclick", "showForm('gp" + good + "', '" + price +"', '" + good + "', '" + rate + "')");
			}

			if(response == "b") {
				document.getElementById(input).style.backgroundColor = '#ffb1ad';
				document.getElementById(input).style.border = '1px solid #df4e47';
			}
		}
	});
}

function showCustomer(id) {
	$.ajax({
		type: 'POST',
		cache: false,
		url: 'scripts/ajaxCustomer.php',
		data: {"id": id},
		success: function(response) {
			$('#mailTextBlock').html('<span class="admLabel">' + response + '</span><br /><br /><span class="basicRed" style="border-bottom: 1px dotted #df4e47; cursor: pointer; float: right;" onclick="hideCustomer()">Закрыть</span>');
			$('#mailTextBlock').css('z-index', '100');
			$('#mailTextBlock').css('display', 'block');
			$('#mailTextBlock').css('opacity', '1');
		},
	});
}

function hideCustomer() {
	$('#mailTextBlock').html('');
	$('#mailTextBlock').css('z-index', '0');
	$('#mailTextBlock').css('display', 'none');
	$('#mailTextBlock').css('opacity', '0');
}

function deleteGoodGroup(order_id, good_id) {
	$.ajax({
		type: 'POST',
		cache: false,
		data: {"orderID": order_id, "goodID": good_id},
		url: 'scripts/ajaxDeleteGoodGroup.php',
		success: function(response){
			if(response == "a") {
				var tableID = 'tableGood' + order_id;
				$.ajax({
					type: 'POST',
					cache: false,
					data: {"orderID": order_id},
					url: 'scripts/ajaxRebuildTable.php',
					success: function(table) {
						document.getElementById(tableID).innerHTML = table;
					}
				});
			}
		}
	});
}

function changeQuantity(block, text, quantity, good_id, order_id) {
	document.getElementById(block).innerHTML = "<form id='editOrderQuantityForm' method='POST'><input type='text' class='catalogueInput' value='" + quantity + "'' id='quantityInput' onkeyup='validateQuantity(\"quantityInput\")' onblur='adminEditOrder(\"" + order_id + "\", \"" + good_id + "\", \"" + block + "\", \"" + text + "\")' style='float: right; z-index: 3;' /></form><br /><br /><div style='height: 3px;'></div>";
	document.getElementById('quantityInput').focus();
	document.getElementById(block).removeAttribute('onclick');
}

function adminEditOrder(order_id, good_id, block, text) {
	var qID = "quantityInput";
	var val = document.getElementById(qID).value;

	if(parseInt(val) > 0) {
		$.ajax({
			type: 'POST',
			cache: false,
			data: {"goodID": good_id, "orderID": order_id, "quantity": val},
			url: 'scripts/ajaxEditOrderGoodQuantity.php',
			success: function(response) {
				if(response == "a") {
					document.getElementById(block).innerHTML = "<span class='basic'><b>Количество: </b><span id='gqt" + good_id + "'>" + val + "</span> шт.</span>";
					document.getElementById(block).setAttribute('onclick', 'changeQuantity(\"gq' + good_id + '\", \"' + text + '\", \"' + val + '\", \"' + good_id + '\", \"' + order_id + '\")');

					$.ajax({
						type: 'POST',
						cache: false,
						data: {"goodID": good_id, "orderID": order_id},
						url: 'scripts/ajaxChangeGroupPrice.php',
						success: function(result) {
							var pID = 'price' + good_id;
							document.getElementById(pID).innerHTML = result;

							$.ajax({
								type: 'POST',
								cache: false,
								data: {"orderID": order_id},
								url: 'scripts/ajaxChangeTotalPrice.php',
								success: function(newSum) {
									var tID = 'totalPrice' + order_id;
									document.getElementById(tID).innerHTML = newSum;
								}
							});
						}
					});
				}
			}
		});
	}
}

$(document).ready(function() {
	$('#searchField').keyup(function() {
		var query = $('#searchField').val();

		if(query.length > 0 && query != "Поиск...") {
			var pos = parseInt($('#searchField').offset().left + 150 - 430);
			pos = pos + 'px';

			$('#fastSearch').css('display', 'block');
			$('#fastSearch').css('top', '80px');
			$('#fastSearch').css('left', pos);

			$.ajax({
				type: 'POST',
				cache: false,
				data: {"searchQuery": query},
				url: 'scripts/ajaxSearch.php',
				success: function(response) {
					$('#fastSearch').html(response);

					if(document.getElementById('footerOrder')) {
						var fb = parseInt($('#fastSearch').offset().top + $('#fastSearch').height());
						var f = parseInt($('#footerOrder').offset().top + $('#footerOrder').height());

						if(fb > f) {
							$('#footerOrder').offset({top: parseInt(fb - f  + $('#footerOrder').offset().top + 30)});
						}
					}

					if(document.getElementsByTagName('footer')) {
						var fb = parseInt($('#fastSearch').offset().top + $('#fastSearch').height());
						var f = parseInt($('footer').offset().top + $('footer').height());

						if(fb > f) {
							$('footer').offset({top: parseInt(fb - f  + $('footer').offset().top + 30)});
						}
					}
					
				}
			});
		}
		else {
			$('#fastSearch').css('display', 'none');
		}
	});
});

$(document).ready(function() {
	$('#searchField').click(function() {
		var query = $('#searchField').val();

		if(query.length > 0 && query != "Поиск...") {
			var pos = parseInt($('#searchField').offset().left + 150 - 430);
			pos = pos + 'px';

			$('#fastSearch').css('display', 'block');
			$('#fastSearch').css('top', '80px');
			$('#fastSearch').css('left', pos);

			$.ajax({
				type: 'POST',
				cache: false,
				data: {"searchQuery": query},
				url: 'scripts/ajaxSearch.php',
				success: function(response) {
					$('#fastSearch').html(response);

					if(document.getElementsByTagName('footer')) {
						var fb = parseInt($('#fastSearch').offset().top + $('#fastSearch').height());
						var f = parseInt($('footer').offset().top + $('footer').height());

						if(fb > f) {
							$('footer').offset({top: parseInt(fb - f  + $('footer').offset().top + 30)});
						}
					}

					if(document.getElementById('footerOrder')) {
						var fb = parseInt($('#fastSearch').offset().top + $('#fastSearch').height());
						var f = parseInt($('#footerOrder').offset().top + $('#footerOrder').height());

						if(fb > f) {
							$('#footerOrder').offset({top: parseInt(fb - f  + $('#footerOrder').offset().top + 30)});
						}
					}

					if(document.getElementsByTagName('footer')) {
						var fb = parseInt($('#fastSearch').offset().top + $('#fastSearch').height());
						var f = parseInt($('footer').offset().top + $('footer').height());

						if(fb > f) {
							$('footer').offset({top: parseInt(fb - f  + $('footer').offset().top + 30)});
						}
					}
				}
			});
		}
		else {
			$('#fastSearch').css('display', 'none');
		}
	});
});

$(document).mouseup(function (e) {
    var container1 = $('#fastSearch');
    var container2 = $('#shadow');
    var container3 = $('#sb-overlay');
    var container4 = $('#sb-close');

    if (container1.has(e.target).length === 0 && container2.has(e.target).length === 0 && container3.has(e.target).length === 0 && container4.has(e.target).length === 0) {
        container1.hide();
    }
});

$(document).mouseup(function (e) {
    var container = $('#newsFastSearch');
    if (container.has(e.target).length === 0) {
        container.hide();
    }
});

$(document).ready(function() {
	$('#userLoginInput').keyup(function() {
		loginCheck();
	});
});

function loginCheck() {
	var login = $('#userLoginInput').val();

		if(login.length < 3 || login.length > 32) {
			$('#userLoginInput').css('background-color', '#ffb1ad');
			$('#userLoginInput').css('border', '1px solid #df4e47');
		} else {
			$.ajax({
				type: 'POST',
				data: {"login": login},
				url: 'scripts/ajaxCheckLogin.php',
				success: function(response) {
					if(response == "a") {
						$('#userLoginInput').css('background-color', '#dddddd');
						$('#userLoginInput').css('border', 'none');
					}

					if(response == "b") {
						$('#userLoginInput').css('background-color', '#ffb1ad');
						$('#userLoginInput').css('border', '1px solid #df4e47');
					}
				}
			});
		}
}

$(document).ready(function() {
	$('#userPasswordInput').keyup(function() {
		passwordCheck();
	});
});

function passwordCheck() {
	var password = $('#userPasswordInput').val();

		if(password.length < 5) {
			$('#userPasswordInput').css('background-color', '#ffb1ad');
			$('#userPasswordInput').css('border', '1px solid #df4e47');
		} else {
			$('#userPasswordInput').css('background-color', '#dddddd');
			$('#userPasswordInput').css('border', 'none');
		}
}

$(document).ready(function() {
	$('#userEmailInput').keyup(function() {
		emailCheck();
	});
});

function emailCheck() {
	var email = $('#userEmailInput').val();

		if(email.length < 1) {
			$('#userEmailInput').css('background-color', '#ffb1ad');
			$('#userEmailInput').css('border', '1px solid #df4e47');
		} else {
			$.ajax({
				type: 'POST',
				data: {"email": email},
				url: 'scripts/ajaxCheckEmail.php',
				success: function(response) {
					if(response == "a") {
						$('#userEmailInput').css('background-color', '#dddddd');
						$('#userEmailInput').css('border', 'none');
					}

					if(response == "b") {
						$('#userEmailInput').css('background-color', '#ffb1ad');
						$('#userEmailInput').css('border', '1px solid #df4e47');
					}
				}
			});
		}
}

$(document).ready(function() {
	$('#organisationInput').keyup(function() {
		organisationCheck();
	});
});

function organisationCheck() {
	var organisation = $('#organisationInput').val();

		if(organisation.length < 1) {
			$('#organisationInput').css('background-color', '#ffb1ad');
			$('#organisationInput').css('border', '1px solid #df4e47');
		} else {
			$.ajax({
				type: 'POST',
				data: {"organisation": organisation},
				url: 'scripts/ajaxCheckOrganisation.php',
				success: function(response) {
					if(response == "a") {
						$('#organisationInput').css('background-color', '#dddddd');
						$('#organisationInput').css('border', 'none');
					}

					if(response == "b") {
						$('#organisationInput').css('background-color', '#ffb1ad');
						$('#organisationInput').css('border', '1px solid #df4e47');
					}
				}
			});
		}
}

$(document).ready(function() {
	$('#recoveryInput').keyup(function() {
		var recovery = $('#recoveryInput').val();

		if(recovery.length < 3) {
			$('#recoveryInput').css('background-color', '#ffb1ad');
			$('#recoveryInput').css('border', '1px solid #df4e47');
		} else {
			$('#recoveryInput').css('background-color', '#dddddd');
			$('#recoveryInput').css('border', 'none');
		}
	});
});

$(document).ready(function() {
	$('#userNameInput').keyup(function() {
		nameCheck();
	});
});

function nameCheck() {
	var name = $('#userNameInput').val();

		if(name.length < 1) {
			$('#userNameInput').css('background-color', '#ffb1ad');
			$('#userNameInput').css('border', '1px solid #df4e47');
		} else {
			$.ajax({
				type: 'POST',
				data: {"name": name},
				url: 'scripts/ajaxCheckUserName.php',
				success: function(response) {
					if(response == "a") {
						$('#userNameInput').css('background-color', '#dddddd');
						$('#userNameInput').css('border', 'none');
					}

					if(response == "b") {
						$('#userNameInput').css('background-color', '#ffb1ad');
						$('#userNameInput').css('border', '1px solid #df4e47');
					}
				}
			});
		}
}

$(document).ready(function() {
	$('#userPhoneInput').keyup(function() {
		phoneCheck();
	});
});

function phoneCheck() {
	var phone = $('#userPhoneInput').val();

		if(phone.length < 1) {
			$('#userPhoneInput').css('background-color', '#ffb1ad');
			$('#userPhoneInput').css('border', '1px solid #df4e47');
		} else {
			$.ajax({
				type: 'POST',
				data: {"phone": phone},
				url: 'scripts/ajaxCheckPhone.php',
				success: function(response) {
					if(response == "a") {
						$('#userPhoneInput').css('background-color', '#dddddd');
						$('#userPhoneInput').css('border', 'none');
					}

					if(response == "b") {
						$('#userPhoneInput').css('background-color', '#ffb1ad');
						$('#userPhoneInput').css('border', '1px solid #df4e47');
					}
				}
			});
		}
}

$(document).ready(function() {
	$('#newsSearchInput').keyup(function() {
		var query = $('#newsSearchInput').val();

		if(query != "" && query != "Найти новость...") {
			$.ajax({
				type: 'POST',
				url: 'scripts/ajaxSearchNews.php',
				data: {"query": query},
				success: function(response) {
					$('#newsFastSearch').css('display', 'block');
					$('#newsFastSearch').html(response);
				}
			});
		}
	});
});

$(document).ready(function() {
	$('#newsSearchInput').click(function() {
		var query = $('#newsSearchInput').val();

		if(query != "" && query != "Найти новость...") {
			$.ajax({
				type: 'POST',
				url: 'scripts/ajaxSearchNews.php',
				data: {"query": query},
				success: function(response) {
					$('#newsFastSearch').css('display', 'block');
					$('#newsFastSearch').html(response);
				}
			});
		}
	});
});