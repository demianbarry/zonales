<?php
/**
* JWF ACL Group selection element
* This is not a standlone,general-purpose element, it will work only with the workflow editor view8
*
* @version		$Id: aclgroup.php 1440 2009-08-16 14:06:09Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.elements
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();



/**
 * JWF ACL Group selection element
 *
 * @package    Joomla
 * @subpackage JWF.elements
*/
class JElementAclgroup extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Aclgroup';

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
		$htmlId   = ( $node->attributes('id') ? $node->attributes('id') : '' );
		$disabled = ( $value ? 'disabled="disabled"' : '' );
		
		$pManager =& getPluginManager();
		$pManager->loadPlugins('acl');
		$aclData  = $pManager->invokeMethod( 'acl', 'getACL',  null, null );
		
		$aclHandlersList = array();
		$aclHandlersList[] = JHTML::_('select.option', 0, JText::_('Please Select'));
		

		$jsOutput = JHTML::_('jwf.indentedLine','var aclLists = {',3);
		
		foreach($pManager->settings['acl'] as $p){
			if( $aclData[$p->id] == null )continue;
			$jsOutput .= JHTML::_('jwf.indentedLine',"'$p->id' : {",4);
			foreach( $aclData[$p->id] as $groupID => $groupTitle ){
				$jsOutput .= JHTML::_('jwf.indentedLine',"$groupID : '$groupTitle',",5);
			}
			$jsOutput  = JHTML::_('jwf.trimComma',$jsOutput);
			$jsOutput .= JHTML::_('jwf.indentedLine','},',4);
		
			$aclHandlersList[] = JHTML::_('select.option', $p->id, $p->name);
		}
		$jsOutput  = JHTML::_('jwf.trimComma',$jsOutput);
		$jsOutput .= JHTML::_('jwf.indentedLine','};',3);
		
		$output  = JHTML::_('jwf.startJSBlock',2);
		$output .= JHTML::_('jwf.indentedLine',"defaultWorkflow.acl = '$value';",3);
		$output .= $jsOutput;
		$output .= JHTML::_('jwf.endJSBlock',2);
	
		
		$output .= JHTML::_('select.genericlist',  $aclHandlersList, "{$control_name}[{$name}]", "$disabled class='inputbox' size='1' onchange='selectACLSystemFromList(this)'", 'value', 'text', $value, $htmlId);
			
		return $output;
	}
}