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
 * AAPU Attribute_Entity Table class
 *
 */
class TableAttributeEntity extends JTable {

    //AttributeEntity ID
    var $id = 0;
    //AttributeEntity generic value
    var $value = null;
    //AttributeEntity int value
    var $value_int = null;
    //AttributeEntity double value
    var $value_double = null;
    //AttributeEntity date value
    var $value_date = null;
    //AttributeEntity boolean value
    var $value_boolean = null;
    //AttributeEntity linked object id
    var $object_id = null;
    //AttributeEntity linked object type (TABLE for example)
    var $object_type = null;
    //AttributeEntity linked object name
    var $object_name = null;
    //AttributeEntity -> linked Attribute id
    var $attribute_id = null;


    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function TableAttributeEntity( &$db ) {
        parent::__construct('#__com_aapu_attribute_entity', 'id', $db);
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

        $query = "DELETE FROM #__com_aapu_attribute_entity
			WHERE id = '" . $this->id . "'" ;
        $this->_db->setQuery($query);
        if($this->_db->query() === false) return false ;

        return true;
    }

}
?>
