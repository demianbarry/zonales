<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

/**
 * Controlador
 *
 */
class EqZonalesController extends JController {
    /**
     * Constructor
     *
     * @param array $default
     */
    function __construct($default = array()) {
        parent::__construct($default);
    }

    function listaEq() {
        $this->baseDisplayTask('ListaEq', '');
    }

    function addAnonDefaultEq() {
        global $option;

        $params = &JComponentHelper::getParams( 'com_eqzonales' );
        $eq_anon_id = $params->get( 'eq_anon_id', null );
        $eq_anon_name = $params->get( 'eq_anon_name', null );

        if (is_null($eq_anon_id)) {
            echo "<script> alert('No se especifico un id para el Ecualizador Anónimo'); window.history.go(-1); </script>\n";
            exit();
        }

        /**
         * Realiza el include de los controladores del componente EqZonales
         */
        require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'eq.php');
        require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'band.php');
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'helper.php');
        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'tables');

        /**
         * Crea instancias de los controladores del componente EqZonales,
         * y les setea el path correcto hacia sus respectivos modelos.
         */
        $ctrlEq = new EqZonalesControllerEq();
        $ctrlEq->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );
        $ctrlBand = new EqZonalesControllerBand();
        $ctrlBand->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );

        $params = new stdClass();
        $params->id = $eq_anon_id;
        $params->user_id = 0;
        $params->nombre = $eq_anon_name;
        $params->descripcion = $eq_anon_name;
        $params->observaciones = $eq_anon_name;

        /**
         * Crea el ecualizador con los datos del nuevo usuario.
         */
        $eqId = $ctrlEq->createEq($params);
        if ($eqId === FALSE) {
            echo "<script> alert('Error al crear el Ecualizador Anónimo'); window.history.go(-1); </script>\n";
            exit();
        } else {
            $params->id = $eqId;
            /**
             * Instancia las bandas por defecto para el ecualizador.
             */
            $ctrlBand->createDefaultBands($params);
        }

        $link = 'index.php?option=' . $option;
        $msg = 'Ecualizador Anónimo creado exitosamente';

        $this->setRedirect($link, $msg);
    }

    function baseDisplayTask($view, $modelName, $layout = 'default', $hidemainmenu = 0, $vars = array()) {
        global $option;

        $document = &JFactory::getDocument();
        $vType = $document->getType();
        $vName = JRequest::getCmd('view', $view);
        $vLayout = JRequest::getCmd( 'layout', $layout );

        // Get/Create the view
        $view = &$this->getView( $vName, $vType);

        // Get/Create the models
        $model = &$this->getModel($modelName);
        if ($model) {
            $view->setModel($model, true);
        }

        // Desactiva el menú principal
        JRequest::setVar('hidemainmenu', $hidemainmenu);

        // Set the layout
        $view->setLayout($vLayout);

        // Display the view
        $view->display();
    }
}