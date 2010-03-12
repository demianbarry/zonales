<?php

class plgSystemAuthproxy extends JPlugin{

    function plgSystemAuthproxy(&$subject,$config) {
        parent::__construct( $subject,$config);
    }

    function onAfterInitialise() {
        $get = JRequest::get('method');

        if (count($get) == 0) return;

        $db = JFactory::getDBO();
        $selectParams = 'select p.name, p.callback_parameters as params '.
                        'from #__providers p where p.callback_parameters is not null';
        $db->setQuery($selectParams);
        $dbProviders = $db->loadObjectList();

        $url = '';
        $actualProvider = null;
        // busca el proveedor que posea TODOS los parametros que arriban
        foreach ($dbProviders as $provider) {
            $url = $this->params->get('loginurl','index.php?option=com_user&amp;task=login');
            // bandera que indica si seguir iterando en el foreach externo
            $continue = false;
            $params = explode(',', $provider->params);
            foreach ($params as $parameter) {
                if (isset ($get[$parameter]) && !$continue){
                    $url = $url . '&' . $parameter . '=' . $get[$parameter];
                }
                else $continue = true;
            }
            if (!$continue) {
                $actualProvider = $provider;
                break;
            }
        }

        // si se le agregaron los parametros especificos
        if ($url != '' && $actualProvider != null){
            $url = $url . '&provider=' . $actualProvider->name;
            $url = $url . '&map=0';
            $url = $url . '&' . JUtility::getToken() . '=1';

            $mainframe =& JFactory::getApplication();
            $mainframe->redirect(JRoute::_($url));
        }
        
    }

}

?>
