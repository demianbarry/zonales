<?php

$moduleMessage = JModuleHelper::getModule('mod_message');
$htmlMessage = JModuleHelper::renderModule($moduleMessage);

//require_once(JPATH_BASE .DS. "plugins".DS."authentication".DS."twitter_helper.php");
$db = &JFactory::getDBO();

// realiza la consulta a la base de datos
$selectProviders = 'select p.name, p.icon_url, g.name as groupname, p.required_input, t.name as type ' .
    'from #__providers p, #__groups g, #__protocol_types t ' .
    'where p.access=g.id and t.id=p.protocol_type_id and p.enabled=1 ' .
    'order by (select count(*) from #__alias a where p.id=a.provider_id) desc, t.name  asc';
$db->setQuery($selectProviders);
$providerslist = $db->loadObjectList();

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

<script type="text/javascript" defer="defer">
    // window.onload = setIni();

    function setIni(){
        var index = document.formlogin.selprovider.options.selectedIndex;
        document.formlogin.selprovider.options[index].onclick();
    }

    function setElement(elements,provider,type) {
        var fixbutton = document.getElementById('fixbutton').value;
        document.getElementById('provider').value=provider;
        document.getElementById('submit').value=sprintf(fixbutton,provider);
<?php
foreach ($providerslist as $prov) {
    foreach ($inputData[$prov->name] as $input) {
        if (!(!$user->guest && $prov->groupname == 'Guest')) {
            if (!isset ($elements[$input['name']])) {
                $elements[$input['name']] = 1;
                echo 'document.getElementById(\'' . $input['name'] . 'set\').style.display = \'none\';';
            }
        }
    }
}

?>
        document.getElementById('submit').style.display = 'block';
        for(i=0 ; i < elements.length ; i++){
            if (elements[i] != ''){
                var fixpart = document.getElementById(elements[i] + '-' + provider + 'fixmessage').value;
                var showsubmit = document.getElementById(elements[i] + 'submitshow').value;
                document.getElementById('submit').style.display = showsubmit;
                document.getElementById(elements[i] + 'set').style.display = 'block';
                if (showsubmit == 'block'){
                    document.getElementById(elements[i] + 'message').innerHTML=sprintf(fixpart,provider);
                }
                else {
                    document.getElementById(elements[i]).innerHTML=sprintf(fixpart,provider);
                }
            }
        }
    }
</script>

<?php require(JModuleHelper::getLayoutPath('mod_zlogin')); ?>