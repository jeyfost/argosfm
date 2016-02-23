function footerPos() {
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