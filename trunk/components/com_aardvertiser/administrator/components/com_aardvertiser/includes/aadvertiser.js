window.addEvent('domready',function(){

    var count = $('box').getElements('img').length;

    var items = new Array();
    for(var i = 0; i < count; i++) {
        items.include(i);
    }

    var startItem = 3; //or   0   or any
    var thumbs_mask = $('thumbs_mask').setStyle('left',(startItem*60-568)+'px').setOpacity(0.8);
    var fxOptions = {duration:1000, transition:Fx.Transitions.Back.easeOut, wait:false}
    var thumbsFx = Fx.Style(thumbs_mask,'left',fxOptions);
    var hs = new noobSlide({
        box: $('box'),
        items: items,
        handles: $ES('span','thumbs_handles'),
        fxOptions: fxOptions,
        onWalk: function(currentItem){
            thumbsFx.start(currentItem*60-568);
        },
        startItem: startItem
    });
    hs.walk(0);
});