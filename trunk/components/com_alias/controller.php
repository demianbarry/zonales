<?php
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');
//JTable::addIncludePath(JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table');
//jimport('joomla.database.table.alias');

require_once JPATH_ROOT . DS . 'components' . DS . 'com_user' . DS . 'helper.php';
require_once JPATH_ROOT . DS . 'components' . DS . 'com_user' . DS . 'views' . DS . 'message' . DS . 'constants.php';

class AliasController extends JController {

/**
 * Method to display the view
 *
 * @access    public
 */
    function display() {
        parent::display();
    }

    /**
     * Permite habilitar o deshabilitar los alias seleccionados
     *
     */
    function unblock() {
        JRequest::checkToken() or jexit( 'Invalid Token' );

        try {
            $db =& JFactory::getDBO();
            $user =& JFactory::getUser();

            //$row =& JTable::getInstance('alias', 'Table');
            //$row = new JTableAlias($db);

            // recupero los alias del usuario
            $userid = $user->id;
            $query = 'select a.id from #__alias a where user_id=' . $userid;
            $db->setQuery($query);
            $dbaliaslist = $db->loadObjectList();

            // a cada alias del usuario le actualizo su estado (habilitado o dehabilitado)
            // segun lo especificado por el usuario
            foreach ($dbaliaslist as $alias) {
                $unblock = JRequest::getBool('alias'.$alias->id, false,'method');

                $block = ($unblock) ? '0' : '1';

                $update = 'update #__alias set block=' . $block .
                    ' where id=' . $alias->id;

                $db->setQuery($update);
                $db->query();
            }

            $message = JText::_('ZONALES_ALIAS_BLOCKED_SUCCESSFULLY');
            UserHelper::showMessage(SUCCESS, $message);
        }
        catch (Exception $ex) {
            $message = JText::_('ZONALES_ALIAS_BLOCKED_ERROR');
            UserHelper::showMessage(ERROR, $message);
        }
    }

}

?>
