<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

/**
 * Controlador
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

        // Según valor de retorno el estilo de msg será de exito o error
        $return = false;

        $link = 'index.php?option=' . $option;

        $paramsEq = &JComponentHelper::getParams( 'com_eqzonales' );
        $eq_anon_id = $paramsEq->get( 'eq_anon_id', null );
        $eq_anon_name = $paramsEq->get( 'eq_anon_name', null );

        if (is_null($eq_anon_id)) {
            $msg = 'No se especifico un id para el Ecualizador Anónimo';
            $this->setRedirect($link, $msg);
            return $return;
        }

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
         * ----
         * NOTA: Al especificar id en $params, JTable realiza un update
         * sobre la base de datos. Como no se encontro forma de evitar este
         * comportamiento se decidió interactuar directamente con la base de
         * datos.
         */
        //$eqId = $ctrlEq->createEq($params);
        $eqRow =& JTable::getInstance('eq', 'Table');
        if ($eqRow->load($eq_anon_id)) {
            $msg = 'Error al crear el Ecualizador Anónimo - El ID especificado ya existe';
        } else {
            // Insertamos el EQ anonimo
            $db =& JFactory::getDBO();
            $query = "INSERT INTO #__eqzonales_eq (id,user_id,nombre,descripcion,observaciones)".
                    " VALUES ($params->id, $params->user_id, '$params->nombre', '$params->descripcion', '$params->observaciones')";
            $db->setQuery($query);
            $result = $db->query();

            //if ($eqId === FALSE) {
            if ($result === FALSE) {
                $msg = 'Error al crear el Ecualizador Anónimo - No se pudo almacenar en la BD';
            } else {
                /**
                 * Instancia las bandas por defecto para el ecualizador.
                 */
                $ctrlBand->createDefaultBands($params);
                $msg = 'Ecualizador Anónimo creado exitosamente';
                $return = true;
            }
        }

        $this->setRedirect($link, $msg);
        return $return;
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