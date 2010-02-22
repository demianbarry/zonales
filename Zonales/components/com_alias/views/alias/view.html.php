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
        $idUser = JRequest::getInt('userid','0','method');

//        if ($idUser != 0){
//            // Check for request forgeries
//		JRequest::checkToken() or jexit( 'Invalid Token' );
//        }


        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();

        $showAliasBlock = true;
        
        if ($user->guest) {
            $showAliasBlock = false;
        }
        else {
            $userid = $user->id;
            $query = 'select a.id, p.icon_url, a.block, p.name as providername from #__alias a, #__providers p where user_id=' . $userid .
                ' and a.provider_id=p.id';
            $db->setQuery($query);
            $dbaliaslist = $db->loadObjectList();
            
            if (count($dbaliaslist) == 0) {
                $showAliasBlock = false;
            }
            else {
                $titleAliasBlockAdmin = JText::_('ZONALES_ALIAS_TITLE_BLOCK_ADMIN');
                $messageAliasBlockAdmin = JText::_('ZONALES_ALIAS_MESSAGE_BLOCK_ADMIN');
                
                $titleUnblock = JText::_('ZONALES_ALIAS_UNBLOCK');

                $titleAlias = JText::_('ZONALES_ALIAS_TITLE');
                $this->assignRef('titleAlias',$titleAlias);
                $this->assignRef( 'aliaslist', $dbaliaslist );
                $this->assignRef('titleAliasBlockAdmin',$titleAliasBlockAdmin);
                $this->assignRef('messageAliasBlockAdmin',$messageAliasBlockAdmin);
                $this->assignRef('titleUnblock',$titleUnblock);
            }
        }

        if (!$showAliasBlock){
            $noAliasMessage = JText::_('ZONALES_ALIAS_NO_ALIAS');
        }

        $titleNewAlias = JText::_('ZONALES_ALIAS_NEW_TITLE');
        $messageNewAlias = JText::_('ZONALES_ALIAS_NEW_MESSAGE');
        $moduleProviders = JModuleHelper::getModule('mod_zlogin');
        $htmlProviders = JModuleHelper::renderModule($moduleProviders);

        $this->assignRef('titleNewAlias',$titleNewAlias);
        $this->assignRef('messageNewAlias',$messageNewAlias);
        $this->assignRef('moduleproviders',$htmlProviders);
        $this->assign('showAliasBlock',$showAliasBlock);
        $this->assignRef('noAliasMessage',$noAliasMessage);
        $this->assign('userId',$idUser);

        parent::display($tpl);
    }
}