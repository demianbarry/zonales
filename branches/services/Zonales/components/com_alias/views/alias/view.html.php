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

    /**
     * Muestra la vista
     *
     * @param <type> $tpl
     */
    function display($tpl = null) {


        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();

        // bandera que indica si mostrar la seccion
        // de habilitacion/deshabilitacion de alias
        $showAliasBlock = true;

        // si el usuario no esta logueado
        // no mostrar la seccion de habilitacion/deshabilitacion de alias
        if ($user->guest) {
            $showAliasBlock = false;
        }
        else {
            // recupero los alias del usuario
            $userid = $user->id;
            $query = 'select a.id, p.icon_url, a.block, p.name as providername from #__alias a, #__providers p where user_id=' . $userid .
                ' and a.provider_id=p.id';
            $db->setQuery($query);
            $dbaliaslist = $db->loadObjectList();

            // si el usuario no tiene alias registrados
            // no muestra la seccion de habilitacion/deshabilitacion de alias
            if (count($dbaliaslist) == 0) {
                $showAliasBlock = false;
            }
            else {
                // arma los mensajes y los hace accesibles a la vista
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

        // si no se va a mostrar la seccion de habilitacion/deshabilitacion de alias
        // muestra un mensaje en su lugar, indicando la situacion
        if (!$showAliasBlock){
            $noAliasMessage = JText::_('ZONALES_ALIAS_NO_ALIAS');
        }

        // arma el resto de los mensajes y los hace accesibles
        // a la vista
        $titleNewAlias = JText::_('ZONALES_ALIAS_NEW_TITLE');
        $messageNewAlias = JText::_('ZONALES_ALIAS_NEW_MESSAGE');
        $moduleProviders = JModuleHelper::getModule('mod_zlogin');
        $htmlProviders = JModuleHelper::renderModule($moduleProviders);

        $this->assignRef('titleNewAlias',$titleNewAlias);
        $this->assignRef('messageNewAlias',$messageNewAlias);
        $this->assignRef('moduleproviders',$htmlProviders);
        $this->assign('showAliasBlock',$showAliasBlock);
        $this->assignRef('noAliasMessage',$noAliasMessage);

        parent::display($tpl);
    }
}