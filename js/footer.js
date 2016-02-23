function footerPos() {			
	var wHeight = $(window).height();
	var footer = jQuery('footer');
	var content = jQuery('#content');
	var fTop = footer.offset().top;
	var cTop = jQuery('#content').offset().top;
	var h = (fTop - cTop) / 2 + 70;
	h = h - content.height() / 2;
	var cgH = jQuery('#catalogueGoods').height();
	var absPos = cgH + 110;

	if(fTop < 595) {
		footer.offset({top: 595});
	}
	
	if(wHeight > 665 && fTop < (wHeight - 70)) {
		footer.offset({top: wHeight - 70});
	}
	
	if(fTop > 595) {
		content.offset({top: h - 20});
	}
	
	if(wHeight < 665) {
		footer.offset({top: 595});
		
		h = (footer.offset().top - cTop) / 2 + 70;
		h = h - content.height() / 2 + 20;
		
		content.offset({top: h});
	}
	
	if(absPos > footer.offset().top) {
		footer.offset({top: absPos + 240});
	}
}