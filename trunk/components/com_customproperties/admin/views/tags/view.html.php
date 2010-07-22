<?php
/**
 * Custom Properties for Joomla! 1.5.x
 * @package Custom Properties
 * @subpackage Component
 * @version 1.98
 * @revision $Revision: 1.3 $
 * @author Andrea Forghieri
 * @copyright (C) Andrea Forghieri, www.solidsystem.it
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );
JHTML::_('behavior.mootools');

/**
 * Value Management View
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesViewtags extends JView {
    /**
     * CP Values view display method
     * @return void
     **/
    function display($tpl = null) {
        global $option;

        $this->_context = $option.'tags';		// nombre del contexto
        $this->cid = JRequest::getVar('cid', 0, '', 'int');

        $document = JFactory::getDocument();
        $script = JUri::root().'/administrator/components/com_customproperties/includes/customproperties_ext.js';
        $document->addScript($script);

        $app =& JFactory::getApplication();

        $user = JFactory::getUser();
        $aid = $user->get('aid',0);
        $gid = $user->get('gid',0);

        $ce_file = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_customproperties' . DS . 'contentelement.class.php';
        require_once $ce_file;
        // get the content element
        $option = JRequest::getVar('contentname');
        if(!$option) {
            $ce = getContentElementByName("content");
        } else {
            $ce = getContentElementByOption($option);
        }
        $this->assignRef('ce',$ce);

        $query = "  SELECT DISTINCT f.id AS fid, f.label AS name, v.id AS vid, v.label, v.access_group AS ag
                    FROM #__custom_properties AS cp
			INNER JOIN #__custom_properties_fields AS f
			ON (cp.field_id = f.id)
			INNER JOIN #__custom_properties_values AS v
			ON (cp.value_id = v.id)
                    WHERE cp.ref_table = '".$ce->table."'
			AND cp.content_id = '$this->cid'
			AND f.published = '1'
			AND f.access <= '$aid'
                    ORDER BY f.ordering, v.ordering ";

        $database = JFactory::getDBO();
        $database->setQuery($query);
        $database->getErrorMsg();
        $this->assignRef('tags',$database->loadObjectList());
        $this->assignRef('app',$app);
        $this->assignRef('gid',$gid);
        parent::display($tpl);
    }

}
