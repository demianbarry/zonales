<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

/**
 * Controlador
 *
 */
class ZonalesController extends JController
{
	/**
	 * Constructor
	 *
	 * @param array $default
	 */
	function __construct($default = array())
	{
		parent::__construct($default);

		$this->registerTask('applyTipoTag', 'saveTipoTag');
		$this->registerTask('addTipoTag', 'editTipoTag');

		$this->registerTask('applyCp2TipoTag', 'saveCp2TipoTag');
		$this->registerTask('addCp2TipoTag', 'editCp2TipoTag');

		$this->registerTask('applyMenu', 'saveMenu');
		$this->registerTask('addMenu', 'editMenu');
	}


	function listTipoTag()
	{
		$this->baseDisplayTask('ListaTipoTag', 'TipoTag');
	}

	function editTipoTag()
	{
		$this->baseDisplayTask('EditaTipoTag', 'TipoTag', 'default', 1);
	}

	function cancelTipoTag()
	{
		$this->baseCancelTask(JText::_('INFO_CANCEL'), 'listTipoTag');
	}

	function removeTipoTag()
	{
		$this->baseRemoveTask('TipoTag', 'listTipoTag');
	}

	function saveTipoTag()
	{
		global $option;

		$model	= &$this->getModel('TipoTag');

		if (!$model->store())
		{
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$tipotag = $model->getData();
		$msg = JText::sprintf('INFO_SAVE', 'Tipo Tag');

		switch ($this->_task) {
			case 'applyTipoTag':
				$link = 'index.php?option=' . $option . '&task=editTipoTag&cid[]=' . $tipotag->id;
				break;

			case 'saveTipoTag':
			default:
				$link = 'index.php?option=' . $option . '&task=listTipoTag';
				break;
		}

		$this->setRedirect($link, $msg);
	}

	function listCp2TipoTag()
	{
		$this->baseDisplayTask('ListaCp2TipoTag', 'Cp2TipoTag');
	}

	function editCp2TipoTag()
	{
		$this->baseDisplayTask('EditaCp2TipoTag', 'Cp2TipoTag', 'default', 1);
	}

	function cancelCp2TipoTag()
	{
		$this->baseCancelTask(JText::_('INFO_CANCEL'), 'listCp2TipoTag');
	}

	function removeCp2TipoTag()
	{
		$this->baseRemoveTask('Cp2TipoTag', 'listCp2TipoTag');
	}

	function saveCp2TipoTag()
	{
		global $option;

		$model	= &$this->getModel('Cp2TipoTag');

		if (!$model->store())
		{
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$cp2tipotag = $model->getData();
		$msg = JText::sprintf('INFO_SAVE', 'Tipo Tag');

		switch ($this->_task) {
			case 'applyCp2TipoTag':
				$link = 'index.php?option=' . $option . '&task=editCp2TipoTag&cid[]=' . $cp2tipotag->id;
				break;

			case 'saveCp2TipoTag':
			default:
				$link = 'index.php?option=' . $option . '&task=listCp2TipoTag';
				break;
		}

		$this->setRedirect($link, $msg);
	}

	function listMenu()
	{
		$this->baseDisplayTask('ListaMenu', 'Menu');
	}

	function editMenu()
	{
		$this->baseDisplayTask('EditaMenu', 'Menu', 'default', 1);
	}

	function cancelMenu()
	{
		$this->baseCancelTask(JText::_('INFO_CANCEL'), 'listMenu');
	}

	function removeMenu()
	{
		$this->baseRemoveTask('Menu', 'listMenu');
	}

	function saveMenu()
	{
		global $option;

		$model = &$this->getModel('Menu');

		if (!$model->store())
		{
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$menu= $model->getData();
		$msg = JText::sprintf('INFO_SAVE', 'Tipo Tag');

		switch ($this->_task) {
			case 'applyMenu':
				$link = 'index.php?option=' . $option . '&task=editMenu&cid[]=' . $menu->id;
				break;

			case 'saveMenu':
			default:
				$link = 'index.php?option=' . $option . '&task=listMenu';
				break;
		}

		$this->setRedirect($link, $msg);
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

	function baseRemoveTask($model, $task)
	{
		global $option;

		$model = &$this->getModel($model);

		if (!$model->delete())
		{
			$msg = JText::_( 'ERROR_REMOVE' );
		}
		else
		{
			$msg = JText::_( 'INFO_REMOVE' );
		}

		$redirect = 'index.php?option=' . $option . '&task=' . $task;
		$this->setRedirect($redirect, $msg);
	}

	function baseCancelTask($msg, $task)
	{
		global $option;
		$this->setRedirect('index.php?option='.$option.'&task='.$task, $msg);
	}

	/* --- AJAX --- */

	function listValues()
	{
		$field_id = JRequest::getVar('fieldId', NULL, 'get', 'int');

		// opción nula
		$blank_option[] = JHTML::_('select.option', '0', JText::_('Seleccione una opcion'), 'id', 'name');

		$helper = new comZonalesAdminHelper();
		$values = array_merge(array(), $blank_option, $helper->getCpMenuValues($field_id));

		if (sizeof($values)) {
			echo JHTML::_('select.genericlist', $values, 'value_id', 'size="1"', 'id', 'name');
		}
		else {
			echo JText::_("Seleccione un field");
		}

		return;
	}
}