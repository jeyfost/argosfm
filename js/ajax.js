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
				document.getElementById('totalPrice').innerHTML = newTotalPrice;
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
	document.getElementById(block).innerHTML = "";
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
				}
			});
		}
		else {
			$('#fastSearch').css('display', 'none');
		}
	});
});

$(document).mouseup(function (e) {
    var container = $('#fastSearch');
    if (container.has(e.target).length === 0){
        container.hide();
    }
});