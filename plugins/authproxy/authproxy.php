<?php

class plgSystemAuthproxy extends JPlugin{

    function plgSystemAuthproxy(&$subject) {
        parent::__construct( $subject );

            // load plugin parameters
            $this->_plugin = JPluginHelper::getPlugin( 'System', 'Authproxy' );
            $this->_params = new JParameter( $this->_plugin->params );
    }

    function onAfterInitialise() {
        $provider = JRequest::getVar('provider','','method','string');

        $db = JFactory::getDBO();
        $selectParams = 'select p.name as provider, p.callback_parameters as params '.
                        'from #__providers where p.callback_parameters!=null';
        $db->setQuery($selectParams);
        $dbProviders = $db->loadObjectList();

        $url = 'index.php?option=com_user&task=login';
        foreach ($dbProviders as $provider) {
            $params = explode(',', $provider->params);
            foreach ($params as $parameter) {
                $valor = // hago el get
                // chequeo que este seteado , di lo esta hago la siguiente linea
                $url = $url . '&' . $parameter . '=' . $valor;
            }
        }

        $mainframe =& JFactory::getApplication();

        $mainframe->redirect(JRoute('index.php?option=com_user&task=login&'));
    }

}

?>
