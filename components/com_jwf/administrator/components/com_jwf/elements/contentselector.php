<?php
/**
* JWF content type selection element
* This is not a standlone,general-purpose element, it will work only with the workflow editor view8
*
* @version		$Id: contentselector.php 1440 2009-08-16 14:06:09Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.elements
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();



/**
 * JWF content type selection element
 * This simply lists the installed "Component plugins" , since "Component plugins" are ones that handle different types of content, it is logical that JWF will be able to handle any type of content that has a plugin
 *
 * @package    Joomla
 * @subpackage JWF.elements
*/
class JElementContentselector extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Contentselector';
	
	/**
	 * Renders the element
	 * 
	 * @access	public
	 * @param string $name name of the element combined with $control_name like "$control_name[$name]"
	 * @param mixed $value pre-selected value of the element
	 * @param object $node reference to the XML node that defined this element
	 * @param string $control_name HTML element Array name , combined with $name like "$control_name[$name]"
	 * @return string HTML output that renders the element
	 */
	function fetchElement($name, $value, &$node, $control_name)
	{
		$htmlId = ( $node->attributes('id') ? $node->attributes('id') : '' );
		
		$pManager =& getPluginManager();
		$pManager->loadPlugins('component');
	
		$output  = JHTML::_('jwf.startJSBlock',2);
		$output .= JHTML::_('jwf.indentedLine',"defaultWorkflow.component = '$value';",3);
		$output .= JHTML::_('jwf.endJSBlock',2);
		
		$contentTypesList = array();
		
		foreach($pManager->settings['component'] as $p){
			$contentTypesList[] = JHTML::_('select.option', $p->id, $p->name);
		}
		
		$output .= JHTML::_('select.genericlist',  $contentTypesList, $control_name.'['.$name.']', 'onchange="updateCategoryList(this)" class="inputbox" size="1"', 'value', 'text', $value, $htmlId);


		return $output;
	}
}