<?php
/**
* List View for JWF Component
*
* @version		$Id: view.html.php 1407 2009-08-13 12:42:36Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * List View
 *
 * @package    Joomla
 * @subpackage JWF
 */
class JWFViewList extends JView
{
	/**
	 * List view display method
	 *
	 * Displays a list of all workflows available in the database
	 *
	 * @return void
	 **/
	function display()
	{
		$app = &JFactory::getApplication();

		
		JHTML::_('stylesheet', 'list.css', 'media/com_jwf/css/');

	
		//Toolbar
		JToolBarHelper::title(  JText::_( 'JWF' ), 'jwf-logo');
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList(JText::_('Are you sure?'),'delete','Delete');
		JToolBarHelper::editListX();
		JToolBarHelper::addNew();

		//Get the model
	    $model =& $this->getModel();
    
		$context	= 'com_jwf.pending_items';
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitStart	= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');	
	
		$response = $model->search( $limitStart, $limit, '', false );
		
		// Create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($response['requestTotal'], $limitStart, $limit);
		
		//Send data to the view
		$this->assignRef('workflows' , $response['workflows']);
		$this->assignRef('pagination', $pagination);
		

		//Display the template
		parent::display();
	}
}
