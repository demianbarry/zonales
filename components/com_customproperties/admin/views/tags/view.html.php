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
        global $option, $mainframe;

        $document = JFactory::getDocument();
        $script = JUri::root().'/administrator/components/com_customproperties/includes/customproperties_ext.js';
        $document->addScript($script);

        $this->_context = $option.'tags';		// nombre del contexto
        $this->cid = JRequest::getVar('cid', 0, '', 'int');

        parent::display($tpl);
    }

}
