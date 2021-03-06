<?php
/**
 * Custom Properties for Joomla! 1.5.x
 * @package Custom Properties
 * @subpackage Component
 * @version 1.98
 * @revision $Revision: 1.4 $
 * @author Andrea Forghieri
 * @copyright (C) Andrea Forghieri, www.solidsystem.it
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Customproperties Fields Model - Collection of single fields
 *
 * @package Custom Properties
 * @version 1.90
 * @subpackage Component
 */
class CustompropertiesModelCpvalues extends JModel {
    /**
     * Value id
     *
     * @var integer
     */
    var $_id;
    /**
     * CP Values list
     *
     * @var array
     */
    var $_list;
    /**
     * Pagination
     *
     * @var array
     */
    var $_page;

    /**
     * Method to set the CP Value identifier
     *
     * @access    public
     * @param    int CP Value identifier
     * @return    void
     */
    function setId($id) {
        // Set id and wipe data
        $this->_id = $id;
        $this->_list = null;
    }

    /**
     * Retrieves the CP Value data
     * @param boolean returns the data with pagination
     * @return array Array of objects containing the data from the database
     */
    function getList($with_pagination = true, $cid = 0, $field_id = null) {

        if (!empty($this->_list)) {
            return $this->_list;
        }

        $database =& $this->getDBO();
        global $mainframe;

        if($with_pagination) {
            $limit      = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
            $limitstart = $mainframe->getUserStateFromRequest( 'limitstart', 'limitstart', 0, 'int' );

            /* count records for pagenav */
            if ($field_id == null) {
                $query = "SELECT count(*) FROM #__custom_properties_values WHERE parent_id = $cid";
            } else {
                $query = "SELECT count(*) FROM #__custom_properties_values WHERE parent_id = $cid AND field_id = $field_id";
            }
            $database->setQuery($query);
            $total = $database->loadResult();
            if ($total <= $limitstart) $limitstart = 0;

            // Create the pagination object
            jimport('joomla.html.pagination');
            $this->_page = $pageNav =  new JPagination($total, $limitstart, $limit);
        }

        /* retrieve records */
        if ($field_id == null) {
            $query = "SELECT * FROM #__custom_properties_values WHERE parent_id = $cid ORDER BY ordering ";
        } else {
            $query = "SELECT * FROM #__custom_properties_values WHERE parent_id = $cid AND field_id = $field_id ORDER BY ordering ";
        }
        if($with_pagination) {
            $database->setQuery($query, $pageNav->limitstart, $pageNav->limit);
        }
        else {
            $database->setQuery($query);
            $this->_page = null;
        }
        $this->_list = $database->loadObjectList();

        // If there is a db query error, throw a HTTP 500 and exit
        if ($database->getErrorNum()) {
            JError::raiseError( 500, $database->stderr() );
            return false;
        }

        return $this->_list;
    }



    function getAll($cid) {
        $database =& $this->getDBO();
        $query = "SELECT * FROM #__custom_properties_values WHERE id != $cid ORDER BY label ";
        $database->setQuery($query);
        $this->_list = $database->loadObjectList();
        return $this->_list;
    }

    function getAllFilterByField($cid, $field_id) {
        $database =& $this->getDBO();
        $query = "SELECT * FROM #__custom_properties_values WHERE id != $cid AND field_id = $field_id ORDER BY label ";
        $database->setQuery($query);
        $this->_list = $database->loadObjectList();
        return $this->_list;
    }
    

    function getAllHierarchical() {
        $database =& $this->getDBO();
        $query = "SELECT * FROM #__custom_properties_values WHERE parent_id IS NOT NULL ORDER BY ordering ";
        $database->setQuery($query);
        $this->_list = $database->loadObjectList();
        return $this->_list;
    }

    function getCachedRoots() {
        $cachedRoots = array();

        foreach ($this->_list as $root) {
            if($root->parent_id == 0)
                $cachedRoots[] = $root;
        }

        return $cachedRoots;
    }

    function getCachedChildren($cid) {
        $cachedChildren = array();

        foreach ($this->_list as $child) {
            if($child->parent_id == $cid)
                $cachedChildren[] = $child;
        }
        return $cachedChildren;
    }

    function getPagination() {
        if (is_null($this->_list) || is_null($this->_page)) {
            $this->getList();
        }
        return $this->_page;
    }

}

/**
 * Generates an HTML select/checkbox  for property assignment
 *
 * @param	string	$type 'checkbox' or 'select'
 * @param	object	$field field to rendered
 * @param	int	$content_id currently selected content_id
 * @param cpContentElement $content_element content element
 * @returns	string	HTML for the select list
 */

/*
function drawCpInput($type, $value, $content_id=0, $content_element){
	$database = JFactory::getDBO();
	$txt = "";
	$ref_table = $content_element->table;

	$value_id = $value->id;
	if($content_id != 0){ // show current content's properties
		$query = "SELECT v.* , cp.id as is_selected
			FROM #__custom_properties_values v
				LEFT JOIN #__custom_properties cp
				ON (v.field_id = cp.field_id
				AND v.id = cp.value_id
				AND '$content_id' = cp.content_id
				AND '$ref_table' = cp.ref_table )
			WHERE v.field_id = '$field_id'
			ORDER BY ordering ";
	}
	else{ // show unselected
		$query = "SELECT * , '' as is_selected
			FROM #__custom_properties_values
			WHERE field_id = '$field_id'
			ORDER BY ordering ";
	}

	$database->setQuery($query);
	$values = $database->loadObjectList();
	$count = count($values);

	$link = "index2.php?option=com_customproperties&amp;task=editField&amp;hidemainmenu=1&amp;id=" . $field_id ;
	if($count == 0){
		$txt .= "Error, values not found for field <a href=\"$link\">". $field->name ."</a>. Add at least one value.";
		return;
	}
	if($type == 'select'){
		$txt .= "<select name=\"cp_". $field-> name ."[]\" size=\"$count\" multiple=\"multiple\">\n";
		foreach($values as $value){
			$selected = $value->is_selected ? "selected=\"selected\"" : "";
			$txt .= "<option value=\"". $value->name . "\" $selected>". $value->label ."</option>\n";
		}
		$txt .= "</select>\n";
	}
	elseif($type == 'checkbox'){
		$name = 'cp_'.$field->name.'[]';
		foreach($values as $value){
			$label = $value->label;
			$checked = $value->is_selected ? "checked=\"checked\"" : "";
			$txt .= "<label for=\"$name\">$label</label><input name=\"$name\" value=\"". $value->name. "\" type=\"checkbox\" $checked/><br/>\n";
		}
	}
	return $txt;
}*/


