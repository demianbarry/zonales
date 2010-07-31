<?php
/**
 * @version		$Id: category.php 11687 2009-03-11 17:49:23Z ian $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Content Component My Archive Model
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class ContentModelMyarchive extends JModel {

    /**
     * Archive items data
     *
     * @var array
     */
    var $_data = array();

    /**
     * Archive number items
     *
     * @var integer
     */
    var $_total = null;

    /**
     * Archive data
     *
     * @var object
     */
    var $_user = null;

    var $_publishing_group;


    /**
     * Constructor
     *
     * @since 1.5
     */
    function __construct() {
        parent::__construct();

        global $mainframe, $cp_config;
        include_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_customproperties'.DS.'cp_config.php');

	$this->_publishing_group = $cp_config['publishing_group'];

        $published = JRequest::getVar('published', true, '', 'boolean');
        $stateFrom = JRequest::getVar('stateFrom') != null ? JRequest::getInt('stateFrom') : false;
        $this->_user =& JFactory::getUser();
	require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'contenthelper.php');
        $contenthelper = new comEqZonalesContentHelper();
	// Get the pagination request variables
        $limit		= JRequest::getVar('limit', 100, '', 'int');
        $limitstart	= JRequest::getVar('limitstart', 0, '', 'int');        
        $Arows = $contenthelper->getContent($limitstart, $limit, array($stateFrom != '5' ? '!tags_values:la_voz_del_vecino':"",$this->_user->get('gid') < $this->_publishing_group ? "created_by:".$this->_user->get('id'):""));


        // here we initialize defaults for category model
        $params = &$mainframe->getParams();
        $params->def('filter',					1);
        $params->def('filter_type',				'title');
    }

    /**
     * Method to get content item data for the current category
     *
     * @param	int	$state	The content state to pull from for the current
     * category
     * @since 1.5
     */
    function getData($state = 1) {
        // Load the Archive data
        if ($this->_loadData($state)) {
            // Initialize some variables
            $user	=& JFactory::getUser();
	    global $cp_config;

            // Make sure the category is published
            // check whether category access level allows access
            if ($cp_config['editing_level'] > $user->get('aid', 0)) {
                JError::raiseError(403, JText::_("ALERTNOTAUTH"));
                return false;
            }
        }
        return $this->_data[$state];
    }

    /**
     * Method to get the total number of content items for the frontpage
     *
     * @access public
     * @return integer
     */
    function getTotal($state = 1) {
        // Lets load the content if it doesn't already exist
        if (empty($this->_total)) {
            // Resultados desde Solr
            require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'contenthelper.php');
            $contenthelper = new comEqZonalesContentHelper();
            $this->_total[$state] = $contenthelper->getTotal(0,100,array('!tags_values:la_voz_del_vecino'));
        }

        return $this->_total[$state];
    }

    /**
     * Method to load content item data for items in the category if they don't
     * exist.
     *
     * @access	private
     * @return	boolean	True on success
     */
    function _loadData($state = 1) {
        if (empty($this->_user)) {
            return false; // TODO: set error -- can't get siblings when we don't know the category
        }

        // Lets load the siblings if they don't already exist
        if (empty($this->_content[$state])) {
	$stateFrom = JRequest::getVar('stateFrom') != null ? JRequest::getInt('stateFrom') : false;
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'contenthelper.php');
        $contenthelper = new comEqZonalesContentHelper();
        // Get the pagination request variables
        $limit          = JRequest::getVar('limit', 100, '', 'int');
        $limitstart     = JRequest::getVar('limitstart', 0, '', 'int');
        $Arows = $contenthelper->getContent($limitstart, $limit, array($stateFrom != '5' ? '!tags_values:la_voz_del_vecino':"",$this->_user->get('gid') < $this->_publishing_group ? "created_by:".$this->_user->get('id'):""));

            // special handling required as Uncategorized content does not have a section / category id linkage
            $i = $limitstart;
            $rows = array();
            foreach ($Arows as $row) {
                //$row->slug = strlen($row->alias) ? "$row->id:$row->alias" : $row->id;
                //$row->catslug = strlen($row->catalias) ? "$row->catid:$row->catalias" : $row->catid;

                // check to determine if section or category has proper access rights
                $rows[$i] = $row;
                $i ++;
            }
            $this->_data[$state] = $rows;
        }
        return true;
    }
}

