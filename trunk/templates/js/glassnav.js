window.addEvent('load', function(){

		$('sublinks').getElements('ul').setStyle('display', 'none');
		$('s1_m').setStyle('display', 'block');
		
		$$('#mymenu li').each(function(el){
         el.getElement('a').addEvent('mouseover', function(subLinkId){
			var layer = subLinkId+"_m";
				$('sublinks').getElements('ul').setStyle('display', 'none');
				$(layer).setStyle('display', 'block');
          }.pass( el.id)
        );      
      });
    });
