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
 * AAPU Attributes Table class
 *
 */
class TableAttributes extends JTable {

    //Attribute ID
    var $id = 0;
    //Attribute Name
    var $name = null;
    //Attribute Label
    var $label = null;
    //Attribute Description
    var $description = null;
    //Attribute Comments
    var $comments = null;
    //Attribute From
    var $from = null;
    //Attribute To
    var $to = null;
    //Attribute Canceled - used if the attribute was removed
    var $canceled = null;
    //Attribute Required - used when an attribute is required
    var $required = null;
    //Attribute Published - used when indicate if the attribute is visible in front-end
    var $published = null;
    //Attribute Validator File
    var $validator = null;
    //Attribute Data Type - id Attribute datatype
    var $data_type_id = null;
    //Attribute Class - id Attribute class
    var $attribute_class_id = null;

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function TableAttributes( &$db ) {
        parent::__construct('#__aapu_attributes', 'id', $db);
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

}
?>
