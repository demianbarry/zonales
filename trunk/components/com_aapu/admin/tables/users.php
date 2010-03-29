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
 * AAPU Users Class
 *
 */
class TableUsers extends JTable {

    //User ID
    var $id = 0;
    //User Name
    var $name = null;
    //User Username
    var $username = null;
    //User email
    var $email = null;
    //User password
    var $password = null;
    //User Usertype
    var $usertype = null;
    //User is Blocked?
    var $block = null;
    //sendEmail to User?
    var $sendEmail = null;
    //User Gruop ID
    var $gid = null;
    //User RegisterDate
    var $registerDate = null;
    //User lastvisitDate
    var $lastvisitDate = null;
    //User activation
    var $activation = null;
    //User params
    var $params = null;

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function TableUsers( &$db ) {
        parent::__construct('#__users', 'id', $db);
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
