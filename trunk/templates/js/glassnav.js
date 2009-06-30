window.addEvent('load', function(){

	// hide all submenu links
	$('sublinks').getElements('ul').setStyle('display', 'none');
	// show by default the first submenu
	$('s0_m').setStyle('display', 'block');
		
	$$('#mymenu li').each(function(el){
		el.getElement('a').addEvent('mouseover', function(subLinkId){
			var layer = subLinkId+"_m";
			$('sublinks').getElements('ul').setStyle('display', 'none');
			$(layer).setStyle('display', 'block');
		}.pass(el.id));
	});

});