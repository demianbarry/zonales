<?php
/**
 * Advanced User Profile Administrator for Joomla! 1.5.x
 * @version 0.1
 * @author Luis Ignacio Aita
 * @copyright (C) Luis Ignacio Aita
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * AAPU AttributeClass Table class
 *
 */
class TableTabs extends JTable {

    //Attribute Class ID
    var $id = 0;
    //Attribute Class Name
    var $name = null;
    //Attribute Class Label
    var $label = null;
    //Attribute Class Description
    var $description = null;

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function TableTabs( &$db ) {
        parent::__construct('#__aapu_attribute_class', 'id', $db);
    }

    /**
     * Overloaded bind function
     *
     * @access public
     * @return boolean
     * @see JTable::bind
     * @since 1.5
     */
    function bind( $from, $ignore=array() )
    {
        parent::bind($from, $ignore);

        return true;
    }

    /**
     * Overloaded check function
     * @return boolean true if check is positive, false otherwise
     */
    function check() {
        //Input here the validation code
        return true;
    }

    /**
     * Overloaded delete function.
     * @return boolean true if check is positive, false otherwise
     */
    /*function delete() {

        $query = "DELETE FROM #__aapu_attribute_class
			WHERE id = '" . $this->id . "'" ;
        $this->_db->setQuery($query);
        if($this->_db->query() === false) return false ;

        return true;
    }*/

}
?>
