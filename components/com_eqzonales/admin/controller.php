<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

/**
 * Controlador
 *
 */
class EqZonalesController extends JController
{
	/**
	 * Constructor
	 *
	 * @param array $default
	 */
	function __construct($default = array())
	{
		parent::__construct($default);
	}

        function listEq()
	{
		$this->baseDisplayTask('ListaEq', '');
	}

        function baseDisplayTask($view, $modelName, $layout = 'default', $hidemainmenu = 0, $vars = array())
	{
		global $option;

		$document = &JFactory::getDocument();
		$vType = $document->getType();
		$vName = JRequest::getCmd('view', $view);
		$vLayout = JRequest::getCmd( 'layout', $layout );

		// Get/Create the view
		$view = &$this->getView( $vName, $vType);

		// Get/Create the models
		$model = &$this->getModel($modelName);
		if ($model)
		{
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