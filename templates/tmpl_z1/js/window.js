function isMaximized (){
    var toleranceFactor = 0.85
    return (window.outerHeight && window.outerWidth && window.outerWidth >= screen.availWidth * toleranceFactor && window.outerHeight >= screen.availHeight * toleranceFactor);
}

function maximize(){
    window.moveTo(0,0);
    window.resizeTo(screen.width,screen.height);
}

if (!isMaximized()){
    maximize();
}

