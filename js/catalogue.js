function categoryColor(action, icon, name, file) {
	if(action) {
		document.getElementById(icon).src = 'pictures/icons/' + file;
		document.getElementById(name).style.color = '#df4e47';
	}
	else {
		document.getElementById(icon).src = 'pictures/icons/' + file;
		document.getElementById(name).removeAttribute('style');
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