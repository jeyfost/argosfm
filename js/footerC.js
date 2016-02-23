function footerPos() {			
	var wHeight = $(window).height();
	var footer = jQuery('footer');
	var content = jQuery('#content');
	var cg = jQuery('#catalogueGoods');
	var fTop = footer.offset().top;
	var cTop = jQuery('#content').offset().top;
	var cgTop = cg.offset().top;
	var h = (fTop - cgTop) / 2 + 70;
	h = h - cg.height() / 2;
	var cgH = jQuery('#catalogueGoods').height();
	var absPos = cgH + 110;
	var cp = jQuery('#cataloguePoints');
	var cpH = cp.height() + 40;
	var cpTop = cp.offset().top;
	var cpAbsH = cpTop + cpH;

	if(cgTop < 90)
	{
		content.offset({top: 90});
	}

	if(wHeight < absPos + 80) {
		footer.offset({top: absPos + 30});
	}
	
	if(footer.offset().top < absPos) {
		footer.offset({top: absPos + 30});
	}
	
	if(footer.offset().top < cpAbsH + 70)
	{
		footer.offset({top: footer.offset().top + 155});
	}
}