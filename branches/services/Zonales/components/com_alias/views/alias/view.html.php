<?php
/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_1
 * @license    GNU/GPL
*/

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */

class AliasViewAlias extends JView {
    
    function display($tpl = null) {
        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();

        $userid = $user->id;
        $query = 'select a.id, p.icon_url, a.block, p.name as providername from #__alias a, #__providers p where user_id=' . $userid .
                ' and a.provider_id=p.id';
        $db->setQuery($query);
        $dbaliaslist = $db->loadObjectList();

        $this->assignRef( 'aliaslist', $dbaliaslist );

        parent::display($tpl);
    }
}