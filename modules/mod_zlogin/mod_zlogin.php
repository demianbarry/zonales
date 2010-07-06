<?php

$moduleMessage = JModuleHelper::getModule('mod_message');
$htmlMessage = JModuleHelper::renderModule($moduleMessage);

//require_once(JPATH_BASE .DS. "plugins".DS."authentication".DS."twitter_helper.php");
$db = &JFactory::getDBO();

$selectProvider = new stdClass();
$selectProvider->name = JText::_('ZLOGIN_CHOOSE_PROVIDER');
$selectProvider->required_input = ':::';
$selectProvider->groupname = 'Public';
$providerslist = array($selectProvider);

// realiza la consulta a la base de datos
$selectProviders = 'SELECT p.name, p.icon_url, g.name as groupname, p.required_input, p.default '.
        'FROM #__providers p, #__groups g '.
        'WHERE p.access=g.id AND p.enabled=1 '.
        'ORDER BY p.name';
$db->setQuery($selectProviders);
$providers = $db->loadObjectList();
$providerslist = array_merge($providerslist,$providers);


foreach ($providers as $cprov) {
    if ($cprov->default) {
        $defaultProvider = $cprov->name;
    }
}


$user =& JFactory::getUser();
$userislogged = (!$user->guest);

$elements = array();
$elementsHTML = array();

// recupera los valores de externalid y providerid si estan presentes
// esto es en caso de que se desee registrar un nuevo alias
$providerid = JRequest::getInt('providerid', '0', 'method');
$externalid = JRequest::getVar('externalid', '', 'method', 'string');

// parsea e interpreta la entrada requerida por cada proveedor
// se lograra crear los elementos html necesarios de entrada
// (algo asi como armar la pantalla al vuelo ;)
$inputData = array();
foreach ($providerslist as $provider) {
    $req_inputs = explode('/', $provider->required_input);
    $inputData[$provider->name] = array();
    foreach ($req_inputs as $input) {
        $data = explode(':', $input);
        $inputData[$provider->name][] =
                array (
                'type' => $data[0],
                'name' => $data[1],
                'message' => $data[2],
                'callback' => $data[3]
        );
    }
}

$connectMessage = JText::_('ZONALES_PROVIDER_CONNECT_WITH');
$buttonMessage = JText::_('ZONALES_PROVIDER_CONNECT');
$routeAction = JRoute::_('index.php');
$providerConnectMessage = JText::_('ZONALES_PROVIDER_CONNECT');
$routeReset = JRoute::_( 'index.php?option=com_user&view=reset&map=0' );
$forgotPasswordMessage = JText::_('FORGOT_YOUR_PASSWORD');
$routeRemind = JRoute::_( 'index.php?option=com_user&view=remind&map=0' );
$forgotUsernameMessage = JText::_('FORGOT_YOUR_USERNAME');
$usersConfig = &JComponentHelper::getParams( 'com_users' );
$allowRegistration = ($usersConfig->get('allowUserRegistration')) ? true : false;
$routeRegister = JRoute::_( 'index.php?option=com_user&view=register&map=0' );
$registerMessage = JText::_('REGISTER');

JHTML::script('webtoolkit.js',JRoute::_('media/system/js/'),false);
?>

<script type="text/javascript">
    var index;
    var providers = new Array();

    window.addEvent('domready', function() {

        /*var registerUser = $("registeruser");
        var registerModule = $("registrationform");

        if(registerModule != undefined) {
            registerModule.setStyle('display','none');

            registerUser.addEvent('click', function (){
                registerUser.href = '#';
                $('zlogin').setStyle('display','none');
                registerModule.setStyle('display','block');
            });
        }*/

        $('formzlogin_submit').setStyle('display', 'none');


<?php foreach ($providerslist as $provider) {
    echo "providers.push({
            name: '$provider->name',
            inputs: [";
    $first = true;
    foreach($inputData[$provider->name] as $input) {
        if (!(!$user->guest && $provider->groupname == 'Guest')) {
            if($first) {
                $first = false;
            } else {
                echo ',';
            }
            echo "  {
                    type: '".$input['type']."',
                    name: '".$input['name']."',
                    message: '".JText::_($input['message'])."',
                    callback: '".$input['callback']."'
                 }";
        }
    }
    echo "]";
    echo "});";
} ?>
        setElements();
        setDefaultProvider();
    });

    function setSelectedIndex() {
        index = $('formzlogin_selprovider').selectedIndex;
    }

    function setDefaultProvider(){
        var select = $("formzlogin_selprovider");
        var i = -1;

        for (coption in select){
            i++;
            if (coption.innerHTML == '<?php echo $defaultProvider ?>'){
                select.options[i].click();
                break;
            }
        }
    }   

    function onFocus(element) {
        if(element.hasClass('inactive')) {
            element.value = '';
            element.removeClass('inactive');
        }
    }

    function onBlur(element) {
        if(element.value == '') {
            var provider = providers[index];
            var inputs = provider.inputs;
            for(i=0 ; i < inputs.length ; i++){
                if (inputs[i].name == element.name ){
                    $('formzlogin_'+inputs[i].name).value=sprintf(inputs[i].message,provider.name);
                    element.addClass('inactive');
                }
            }
        }
    }

    function setElements() {
        setSelectedIndex();
        var provider = providers[index];
        $('formzlogin_provider').value=provider.name;
        $('formzlogin_submit').value=sprintf(<?php echo "'$buttonMessage'"?>,provider.name);
        var elements = provider.inputs;
<?php
foreach ($providerslist as $prov) {
    foreach ($inputData[$prov->name] as $input) {
        if (!(!$user->guest && $prov->groupname == 'Guest')) {
            if (!isset ($elements[$input['name']]) && strlen($input['name']) > 0) {
                $elements[$input['name']] = 1;
                echo '$(\'formzlogin_'. $input['name'] . 'set\').setStyle(\'display\',\'none\');';
            }
        }
    }
}

?>
        $('formzlogin_submit').setStyle('display', index != 0 ? 'block' : 'none');
        for(i=0 ; i < elements.length ; i++){
            if (elements[i].name != '' ){
                var fixpart = elements[i].message;
                $('formzlogin_submit').setStyle('display', elements[i].type != 'button' ? 'block' : 'none');
                $('formzlogin_'+elements[i].name + 'set').setStyle('display','block');
                if (elements[i].type != 'button'){
                    $('formzlogin_'+elements[i].name).value=sprintf(fixpart,provider.name);
                    $('formzlogin_'+elements[i].name).addClass('inactive');
                }
                else {
                    $('formzlogin_'+elements[i].name).innerHTML=sprintf(fixpart,provider.name);
                }
            }
        }

    }

</script>

<?php require(JModuleHelper::getLayoutPath('mod_zlogin')); ?>