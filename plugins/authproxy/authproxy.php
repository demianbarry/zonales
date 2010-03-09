<?php

class plgSystemAuthproxy extends JPlugin{

    function plgSystemAuthproxy(&$subject) {
        parent::__construct( $subject );

            // load plugin parameters
            $this->_plugin = JPluginHelper::getPlugin( 'System', 'Authproxy' );
            $this->_params = new JParameter( $this->_plugin->params );
    }

    function onAfterInitialise() {
        $get = JRequest::get('get');

        if (count($get) == 0) return;

        $db = JFactory::getDBO();
        $selectParams = 'select p.name as provider, p.callback_parameters as params '.
                        'from #__providers where p.callback_parameters!=null';
        $db->setQuery($selectParams);
        $dbProviders = $db->loadObjectList();

        $url = '';
        foreach ($dbProviders as $provider) {
            $url = $this->_params->get('loginurl','index.php?option=com_user&amp;task=login');
            // bandera que indica si seguir iterando en el foreach externo
            $continue = false;
            $params = explode(',', $provider->params);
            foreach ($params as $parameter) {
                if (isset ($get[$parameter]) && !$continue){
                    $url = $url . '&' . $parameter . '=' . $get[$parameter];
                }
                else $continue = true;
            }
            if (!$continue) break;
        }

        // si se le agregaron los parametros especificos
        if ($url != '' && !$continue){
            $url = $url . '&provider=' . $dbProviders->name;
            $url = $url . '&' . JUtility::getToken() . '=1';

            $mainframe =& JFactory::getApplication();
            $mainframe->redirect(JRoute($url));
        }
        
    }

}

?>
