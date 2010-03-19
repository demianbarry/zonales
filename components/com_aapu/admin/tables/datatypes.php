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
 * AAPU Data Type Table class
 *
 */
class TableDatatypes extends JTable {

    //Data Type ID
    var $id = 0;
    //Data Type Name
    var $label = null;
    //Data Type Description
    var $description = null;
    //Data Type Render
    var $render = null;

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function TableDatatypes( &$db ) {
        parent::__construct('#__aapu_data_types', 'id', $db);
    }

    /**
     * Binds an array to the object
     * @param 	array	Named array
     * @param 	string	Space separated list of fields not to bind
     * @return	boolean
     */
    function bind( $array, $ignore='' ) {
        $result = parent::bind( $array );
        // cast properties
        $this->id	= (int) $this->id;

        return $result;
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
     * Overloaded delete function. Deletes the attribute.
     * @return boolean true if check is positive, false otherwise
     */
    function delete() {

        $query = "DELETE FROM #__aapu_data_types
			WHERE id = '" . $this->id . "'" ;
        $this->_db->setQuery($query);
        if($this->_db->query() === false) return false ;

        return true;
    }

}
?>
