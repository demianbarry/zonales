<?php
/**
 * JWF content category selection element
 * This is not a standlone,general-purpose element, it will work only with the workflow editor view8
 *
 * @version		$Id: categoryselector.php 1440 2009-08-16 14:06:09Z mostafa.muhammad $
 * @package		Joomla
 * @subpackage	JWF.elements
 * @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
 * @license		GNU/GPL
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();



/**
 * JWF content category selection element class
 *
 * @package    Joomla
 * @subpackage JWF.elements
 */
class JElementCategoryselector extends JElement {
    /**
     * Element name
     *
     * @access	protected
     * @var		string
     */
    var	$_name = 'Categoryselector';

    /**
     * Recursively dives through a category to fetch its children ( Used for J! 1.6)
     *
     * @access	public
     * @param object $category category object whose children are to be fetched
     * @param int $level level of indention to start from
     * @return string indented category tree
     */
    function fetchChildren( $category, $level ) {
        $output = '';
        foreach( $category->getChildren() as $child ) {
            $child->setTitle(str_repeat("...", $level) . $child->getTitle);
            $output .= JHTML::_('jwf.indentedLine',$child->getId()." : '{$child->getTitle()}',",5);
            if( count( $child->getChildren() ))$output .= $this->fetchChildren( $child, $level+1);
        }
        return $output;
    }

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
    function fetchElement($name, $value, &$node, $control_name) {
        $htmlId = ( $node->attributes('id') ? 'id="'.$node->attributes('id').'"' : '' );

        $pManager =& getPluginManager();
        $pManager->loadPlugins('component');

        $output  = JHTML::_('jwf.startJSBlock',2);
        $output .= JHTML::_('jwf.indentedLine',"defaultWorkflow.category = '$value';",3);

        $output .= JHTML::_('jwf.indentedLine','var categoriesData = {',3);

        $categoryLists = $pManager->invokeMethod( 'component', 'getCategories',  null, null );

        foreach( $categoryLists as $contentType => $data ) {

            $output .= JHTML::_('jwf.indentedLine',"$contentType:{",4);

            foreach( $data as $id => $obj ) {
                $output .= JHTML::_('jwf.indentedLine',$obj->getId().' : \''.$obj->getTitle().'\',',5);
                if( count($obj->getChildren()))$output .= $this->fetchChildren( $obj,1 );
            }
            if (count($data))$output  = JHTML::_('jwf.trimComma', $output );
            $output .= JHTML::_('jwf.indentedLine',"},",4);
        }
        $output  = JHTML::_('jwf.trimComma', $output );
        $output .= JHTML::_('jwf.indentedLine','};',3);
        $output .= JHTML::_('jwf.endJSBlock',2);
        $output .= JHTML::_('jwf.indentedLine',"<select multiple='multiple' name='{$control_name}[{$name}][]' $htmlId size='5'></select>",2);
        return $output;

    }
}

