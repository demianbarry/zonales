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
jimport( 'joomla.application.module.helper');

/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */
 
 jimport( 'joomla.methods' );

class AliasViewAlias extends JView {
    
    function display($tpl = null) {
        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();

        $userid = $user->id;
        $query = 'select a.id, p.icon_url, a.block, p.name as providername from #__alias a, #__providers p where user_id=' . $userid .
                ' and a.provider_id=p.id';
        $db->setQuery($query);
        $dbaliaslist = $db->loadObjectList();
        
        $titleAliasBlockAdmin = JText::_('Alias block administration');
        $messageAliasBlockAdmin = JText::_('Here you can block or unblock your alias');

        $titleNewAlias = JText::_('New alias registration');
        $messageNewAlias = JText::_('Add new ways to authenticate you on Zonales');

        $titleAlias = JText::_('Alias');
        $titleUnblock = JText::_('Unblock');

        $moduleProviders = JModuleHelper::getModule('mod_zlogin');
        $htmlProviders = JModuleHelper::renderModule($moduleProviders);

        $this->assignRef( 'aliaslist', $dbaliaslist );
        $this->assign('titleAliasBlockAdmin',$titleAliasBlockAdmin);
        $this->assign('messageAliasBlockAdmin',$messageAliasBlockAdmin);
        $this->assign('titleNewAlias',$titleNewAlias);
        $this->assing('messageNewAlias',$messageNewAlias);
        $this->assign('titleAlias',$titleAlias);
        $this->assign('titleUnblock',$titleUnblock);
        $this->assign('moduleproviders',$htmlProviders);

        parent::display($tpl);
    }
}