<?php
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

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
        $db =& JFactory::getDBO();
        $this->logme($db, 'alias'.$alias->id);
        JRequest::checkToken() or jexit( 'Invalid Token' );

        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();

        $userid = $user->id;
        $query = 'select a.id from #__alias a where user_id=' . $userid;
        $db->setQuery($query);
        $dbaliaslist = $db->loadObjectList();
        
        foreach ($dbaliaslist as $alias) {
            $unblock = JRequest::getBool('alias'.$alias->id, false,'method');

            $block = ($unblock) ? '0' : '1';

            $this->logme($db, 'alias'.$alias->id);
            $this->logme($db, 'el bloqueo es: ' . $block);



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
