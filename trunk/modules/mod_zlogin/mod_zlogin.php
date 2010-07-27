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
<?php require(JModuleHelper::getLayoutPath('mod_zlogin')); ?>