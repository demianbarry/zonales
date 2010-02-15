<?php
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');
//JTable::addIncludePath(JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table');
//jimport('joomla.database.table.alias');

class AliasController extends JController {

    /**
     * Method to display the view
     *
     * @access    public
     */
    function display()
    {
        parent::display();
    }

    function unblock() {
        JRequest::checkToken() or jexit( 'Invalid Token' );

        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();

        //$row =& JTable::getInstance('alias', 'Table');
        //$row = new JTableAlias($db);

        $userid = $user->id;
        $query = 'select a.id from #__alias a where user_id=' . $userid;
        $db->setQuery($query);
        $dbaliaslist = $db->loadObjectList();
        
        foreach ($dbaliaslist as $alias) {
            $unblock = JRequest::getBool('alias'.$alias->id, false,'method');

            $block = ($unblock) ? '0' : '1';

            $this->logme($db, 'alias'.$alias->id);
            $this->logme($db, 'el bloqueo es: ' . $block);


//            $row->load($alias->id);
//            $row->block = (int) $block;
//            if (!$row->store()) {
//                JError::raiseError(500, $row->getError() );
//            }

            $update = 'update #__alias set block=' . $block .
                ' where id=' . $alias->id;

            $db->setQuery($update);
            $db->query();
        }
    }

    private function logme($db,$message) {
        $query='insert into #__logs(info,timestamp) values ("' .
            $message . '","' . date('Y-m-d h:i:s') . '")';
        $db->setQuery($query);
        $db->query();
    }

}

?>
