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
        global $option, $mainframe, $Itemid, $cp_config;

        $this->_context = $option.'tags';		// nombre del contexto
        $this->cid = JRequest::getVar('cid', 0, '', 'int');

        $document = JFactory::getDocument();
        $script = JUri::root().'/administrator/components/com_customproperties/includes/customproperties_ext.js';
        $document->addScript($script);

        $app =& JFactory::getApplication();

        $database 		= JFactory::getDBO();
        $user 			= JFactory::getUser();
        $aid			= $user->get('aid',0);
        $result			= "";
        $tagstring		= "";
        $tagstrings		= array();

        $show_tag_name 	= $cp_config['show_tag_name'];
        $linked_tags 	= $cp_config['linked_tags'];
        $url_format 	= $cp_config['url_format'];
        $use_itemid	= 1;

        $query = "SELECT DISTINCT f.id as fid, f.label as name, v.id as vid, v.label
                    FROM #__custom_properties AS cp
                            INNER JOIN #__custom_properties_fields AS f
                            ON (cp.field_id = f.id )
                            INNER JOIN #__custom_properties_values AS v
                            ON (cp.value_id = v.id )
                    WHERE cp.ref_table = 'content'
                            AND cp.content_id = '$this->cid'
                            AND f.published = '1'
                            AND f.access <= '$aid'
                    ORDER BY f.ordering, v.ordering ";

        $database->setQuery($query);
        $database->getErrorMsg();
        $tags = $database->loadObjectList();

        $result .= "<div class=\"cp_tags\">\n";
        $result .= "<span class=\"cp_tag_label\">". JText::_('Tags').": </span>";

        $itemid_url = "";
        if($use_itemid == 1) {
            $itemid_url = "&Itemid=".$Itemid ;
        }


        $tags_count = 0;
        foreach($tags as $tag) {
            $tagstring = "";

            if($url_format == 0) {
                $link = JRoute::_("index.php?option=com_customproperties&task=tag&tagId=". $tag->vid .$itemid_url);
            }
            else {
                $link = JRoute::_("index.php?option=com_customproperties&task=tag&tagName=". urlencode($tag->name.":".$tag->label) . $itemid_url);
            }

            $result .= "<span id=".$tag->vid."_".$tag->fid."_content_".$this->cid." class=\"cp_tag cp_tag_".$tag->vid."\">";
            if($linked_tags) $result .= "<a href=\"$link\">";

            if($show_tag_name) $tagstring = htmlspecialchars($tag->name) . ": ";
            $tagstring .= htmlspecialchars($tag->label);
            $result .= $tagstring;

            if($linked_tags)
                $result .= "</a>\n";
            //if($can_edit)
            $result .= "<img id='tag_img_$tag->vid' onclick='deleteTag($tag->vid,$tag->fid, \"content\", $this->cid)' style='cursor: pointer; vertical-align: middle;' src='/templates/".$app->getTemplate()."/images/eliminar.gif'>";
            $result .= "</span> ";

            $tagstrings[] = $tagstring;

            $tags_count++;
        }

        $this->assignRef('result', $result);

        parent::display($tpl);
    }

}
