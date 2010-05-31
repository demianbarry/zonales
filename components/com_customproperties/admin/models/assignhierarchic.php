<?php
/**
 * Custom Properties for Joomla! 1.5.x
 * @package Custom Properties
 * @subpackage Component
 * @version 1.98
 * @revision $Revision: 1.5 $
 * @author Andrea Forghieri
 * @copyright (C) Andrea Forghieri, www.solidsystem.it
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'contentelement.class.php');

/**
 * Customproperties Assignment Model - Link between content items and Custom properties
 *
 * @package Custom Properties
 * @version 1.90
 * @subpackage Component
 */
class CustompropertiesModelAssignhierarchic extends JModel {
    /**
     * Content id
     *
     * @var integer
     */
    var $_id;
    /**
     * Cp Content Element
     *
     * @var object
     */
    var $_content_element;
    /**
     * Reference table
     *
     * @var string
     */
    var $_title;
    /**
     * Content text
     *
     * @var string
     */
    var $_content;
    /**
     * CP array of associated custom properties
     *
     * @var array
     */
    var $_properties;

    /**
     * Constructor that retrieves the ID from the request
     *
     * @access    public
     * @return    void
     */
    function __construct() {
        parent::__construct();

        if($content_id = JRequest::getVar('cid', null, '', 'array')) {
            $this->setId((int) $content_id[0]);
        }
        elseif($content_id = JRequest::getVar('id', null)) {
            $this->setId($content_id);
        }
        else {
            return false;
        }

        $ce_name = JRequest::getVar('ce_name', null);
        if (!$content_element = getContentElementByName("$ce_name")) {
            $content_element = getContentElementByName("content");
        }
        $this->setContentElement($content_element);
    }


    /**
     * Method to set the CP Field identifier
     *
     * @access    public
     * @param    int CP Field identifier
     * @return    void
     */
    function setId($id) {
// Set id and wipe data
        $this->_id = $id;
    }
    /**
     * Method to set the item reference table
     *
     * @access    public
     * @param    string $ref_table Item reference table
     * @return    void
     */
    function setContentElement($content_element) {
// Set ref_table
        $this->_content_element = $content_element;
    }

    function getContentElement() {
        return $this->_content_element;
    }

    /**
     * Retrieves the Title of select content item
     * @return String
     */
    function & getTitle() {

        if(empty($this->_id)) {
            $this->_title = "";
            return $this->_title;
        }

// Load the data
        if (empty ($this->_title)) {
            $database = $this->_db;
            $ce = $this->_content_element;

            /* retrieve values */
            $query = "SELECT " .$ce->title." FROM #__". $ce->table ." WHERE " . $ce->id. " = '" . $this->_id ."'";

            $database->setQuery($query);
            $this->_title = $database->loadResult();
        }
        return $this->_title;
    }

    /**
     * Retrieves the Title of select content item
     * @return String
     */
    function & getContent() {

        if(empty($this->_id)) {
            $this->_content = "";
            return $this->_content;
        }

// Load the data
        if (empty ($this->_content)) {
            $database = $this->_db;
            $ce = $this->_content_element;

            /* retrieve values */
            $query = "SELECT " .$ce->fulltext." FROM #__". $ce->table ." WHERE " . $ce->id. " = '" . $this->_id ."'";

            $database->setQuery($query);
            $this->_content = $database->loadResult();
        }
        return $this->_content;
    }

    /**
     * Retrieves some descriptive properties about the content items
     * @return array associative array with section, category, authour, content element label
     */
    function &getProperties() {
// Load the data
        if (empty ($this->_properties)) {

            $database 	=& $this->_db;
            $ce 		=& $this->_content_element;
            $ref_table 	= $ce->table;
            $content_id = $this->_id;

            $database = $this->_db;
            $ce = $this->_content_element;
// retrieve values
// $selstr[] 	= "c.id ";
            $selstr[] = "c.".$ce->id;
            $fromstr[] 	= "#__$ref_table AS c";
            $wherestr	= "c.".$ce->id ." = '" . $this->_id . "'";

            if($ce->sec_table) {
                $selstr[] 		= "sec.".$ce->sec_table_id." AS secid ";
                $selstr[] 		= "sec.".$ce->sec_table_title." AS section";
                $fromstr[] 		= "LEFT JOIN #__".$ce->sec_table." AS sec ON(c.".$ce->sectionid." = sec.".$ce->sec_table_id.") ";
            }

            if($ce->cat_table) {
                $selstr[] 		= "cat.".$ce->cat_table_id." AS catid ";
                $selstr[] 		= "cat.".$ce->cat_table_title." AS category";
                $fromstr[] 		= "LEFT JOIN #__".$ce->cat_table." AS cat ON(c.".$ce->catid." = cat.".$ce->cat_table_id.") ";
            }



            $query = "SELECT " .implode( ',', $selstr). " FROM " . implode(' ', $fromstr). " WHERE $wherestr ";
            $database->setQuery($query);
            $row = $database->loadObject();

            $properties = 	array (	'section' 	=> ($ce->sec_table) ? $row->section : '',
                    'category' 	=> ($ce->cat_table) ? $row->category : '',
                    'content_element' => $ce->label
            );
            $this->_properties = $properties;
        }
        return $this->_properties;
    }

    /**
     * Assign custom property to a content element
     * @param string $action delete|replace|replace
     */
    function assignCustomProperties($action) {

        $database = $this->_db;
        $cid = JRequest::getVar('cid', 0, '', 'array');
        $ce = $this->_content_element;
        $ref_table = $ce->table;

        $i = 0;
        // retrieve cp_fields id
        foreach ($cid as $content_id) {

            if ($action == 'delete' || $action == 'replace') {
                // clean previous properties
                $query = "delete FROM #__custom_properties
                                        WHERE content_id = '$content_id' AND ref_table = '$ref_table' ";

                $database->setQuery($query);
                $database->query();

                $i++;
            }

            if ($action == "add" || $action == "replace") {
                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'cp_') === 0 && $value == 2) {
                        $field = $database->getEscaped(substr($key, 3, strpos($key, '_', 3)-3));
                        $value = $database->getEscaped(substr($key, strpos($key, '_', 3)+1));

                        $query = "  REPLACE INTO #__custom_properties (ref_table, content_id,field_id,value_id)
                                        VALUES ('$ref_table',$content_id,$field,$value)";
                        
                        $database->setQuery($query);
                        $database->query();
                    }
                }
                $i++;
            }
        }
        if($i != 0) {
            $article  =& JTable::getInstance('content');
            $dispatcher =& JDispatcher::getInstance();
            $dispatcher->trigger('onAfterContentSave', array(&$article,($article->id < 1)));
        }
    }

    function getAssignedCustomProperties($ref_table, $content_id) {
        $database = JFactory::getDBO();

        if($content_id != 0) { // show current content's properties
            $query = "SELECT cp.value_id as value_id
			FROM #__custom_properties cp
				JOIN #__custom_properties_values v
				 ON (cp.value_id = v.id AND v.parent_id IS NOT NULL)
			WHERE cp.ref_table = '$ref_table'
                        AND cp.content_id = '$content_id'";
        }

        $database->setQuery($query);
        return $database->loadObjectList();
    }
}