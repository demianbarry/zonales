window.addEvent('domready', function () {
    var loginModule = $("zlogin");
    var registerUser = $("registeruser");
    var registerModule = $("registrationform");

    loginModule.setStyle('display','block');
    registerModule.setStyle('display','none');

    registerUser.addEvent('click', function (){
        loginModule.setStyle('display','none');
        registerModule.setStyle('display','block');
    });

});



