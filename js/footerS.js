function footerPos() {			
	var wHeight = $(window).height();
	var footer = jQuery('footer');
	var content = jQuery('#content');
	var s = jQuery('#search');
	var sTop = s.offset().top;
	var fTop = footer.offset().top;
	var cTop = jQuery('#content').offset().top;
	var h = (fTop - cTop) / 2 + 70;
	h = h - content.height() / 2;
	var sH = s.height();
	var absPos = sH + 110;

	if(sTop < 90)
	{
		content.offset({top: 90});
	}

	if(wHeight < absPos + 80) {
		footer.offset({top: absPos + 30});
	}
	
	if(footer.offset().top < absPos) {
		footer.offset({top: absPos + 30});
	}
}