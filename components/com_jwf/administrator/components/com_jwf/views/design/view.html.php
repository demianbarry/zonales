<?php
/**
 * Design View for JWF Component
 *
 * @version		$Id: view.html.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
 * @package		Joomla
 * @subpackage	JWF
 * @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.registry.parameter');
jimport('joomla.application.component.view');
jimport('joomla.html.pane');


/**
 * Design View
 *
 * @package    Joomla
 * @subpackage JWF
 */
class JWFViewDesign extends JView {
    /**
     * Design view display method
     *
     * Displays a the workflow design GUI
     *
     * @return void
     **/
    function display($workflow, $tpl = null) {
        //Holds ACL information, will be retireved from ACL plugins
        $this->aclData = null;

        JRequest::setVar('hidemainmenu', 1);

        JHTML::_('JWF.reloadMootools');

        JHTML::_('stylesheet', 'design.css'  , 'media/com_jwf/css/');
        JHTML::_('script', 'utilities.js'     , 'media/com_jwf/scripts/');
        JHTML::_('script', 'design.events.js' , 'media/com_jwf/scripts/');
        JHTML::_('script', 'design.station.js', 'media/com_jwf/scripts/');
        JHTML::_('script', 'design.general.js', 'media/com_jwf/scripts/');

        //Toolbar
        JToolBarHelper::title(   JText::_( 'JWF' ), 'jwf-logo' );
        JToolBarHelper::save();
        JToolBarHelper::cancel();

        //Send data to the view
        $this->assignRef('workflow'        , $workflow);
        $this->assignRef('groupsForm'      , $this->getGroupsForm());
        $this->assignRef('propertiesForm'  , $this->getPropertiesForm());
        $this->assignRef('generalForm'     , $this->getGeneralForm( $workflow ));

        //Display the template
        parent::display($tpl);
    }


    function getGroupsForm() {

        $output  = JHTML::_('jwf.indentedLine','<div id="groups-container">',2);
        $output .= JHTML::_('select.genericlist',  array(), "groups_input_acl", 'class="inputbox" size="5"', 'value', 'text');
        $output .= JHTML::_('jwf.indentedLine','<input type="button" class="button" onclick="addEmptyElement()" value="'.JText::_('Add Station').'" />',2);
        $output .= JHTML::_('jwf.indentedLine','</div>',2);
        $output .= JHTML::_('jwf.indentedLine',"<div class='clear'></div>",2);
        return $output;

    }

    function getGeneralForm($workflow) {

        $form = new JParameter('');
        $form->addElementPath(JWF_BACKEND_PATH.DS.'elements');
        $form->loadSetupFile(JPATH_COMPONENT.DS.'models'.DS.'workflow.xml');
        $jsOutput = '';
        if($workflow != null) {
            $form->bind( $workflow );
            $jsOutput  = JHTML::_('jwf.startJSBlock',2);
            $jsOutput .= JHTML::_('jwf.indentedLine','adminGroupId ='. $workflow->admin_gid . ';',2);
            $jsOutput .= JHTML::_('jwf.indentedLine','window.addEvent("load",function(){',3);
            $jsOutput .= JHTML::_('jwf.indentedLine','var s;',4);
            $jsOutput .= JHTML::_('jwf.indentedLine','var tempHook;',4);
            foreach($workflow->stations as $s) {
                $s->activeHooks = unserialize(base64_decode($s->activeHooks));
                $jsOutput .= JHTML::_('jwf.indentedLine',"s = new Object();",4);
                $jsOutput .= JHTML::_('jwf.indentedLine',"s.id    = $s->id;",4);
                $jsOutput .= JHTML::_('jwf.indentedLine',"s.title = '$s->title';",4);
                $jsOutput .= JHTML::_('jwf.indentedLine',"s.task  = '$s->task';",4);
                $jsOutput .= JHTML::_('jwf.indentedLine',"s.allocatedTime = $s->allocatedTime;",4);
                $jsOutput .= JHTML::_('jwf.indentedLine',"s.acl   =  {id:$s->group,name:aclLists['".$workflow->acl."'][$s->group]};",4);
                $jsOutput .= JHTML::_('jwf.indentedLine',"s.fields= '$s->fields';",4);
                $jsOutput .= JHTML::_('jwf.indentedLine',"s.activeValidations= '$s->activeValidations';",4);
                
                $jsOutput .= JHTML::_('jwf.indentedLine',"s.activeHooks = new Array();",4);
                $jsOutput .= JHTML::_('jwf.indentedLine',"tempHook = new Object();",4);

                foreach( $s->activeHooks as $hookName => $hookData) {
                    $parameters = JArrayHelper::fromObject($hookData);
                    foreach( $parameters as $key => $value ) {

                        $value = addslashes($value);
                        $value = str_replace("\r\n","\n" ,$value);
                        $value = str_replace("\n"  ,'\n' ,$value);
                        $jsOutput .= JHTML::_('jwf.indentedLine',"tempHook.$key = '$value';",4);
                    }
                    $jsOutput .= JHTML::_('jwf.indentedLine',"s.activeHooks['$hookName'] = tempHook;",4);

                }
                $jsOutput .= JHTML::_('jwf.indentedLine',"addElement(s);",4);

            }
            $jsOutput .= JHTML::_('jwf.indentedLine','});',3);
            $jsOutput .= JHTML::_('jwf.endJSBlock',2);
        }
        return $jsOutput . $form->render('params');
    }

    function getPropertiesForm() {

        $pManager =& getPluginManager();
        $pManager->loadPlugins('acl');
        $pManager->loadPlugins('field');
        $pManager->loadPlugins('hook');
        $pManager->loadPlugins('validation');
        $pane	=& JPane::getInstance('sliders');


        $output  = JHTML::_('jwf.indentedLine','<div id="properties-container">',2);

        $output .= JHTML::_('jwf.indentedLine',"<fieldset><legend>".JText::_('Identification')."</legend>",2);
        $output .= JHTML::_('jwf.indentedLine',"<label for='properties_input_title'>".JText::_('Title')."</label>",2);
        $output .= JHTML::_('jwf.indentedLine',"<input onchange='saveElement()' type='text' id='properties_input_title' />",2);
        $output .= JHTML::_('jwf.indentedLine',"<div class='clear'></div>",2);

        $output .= JHTML::_('jwf.indentedLine',"<label for='properties_input_task'>".JText::_('Task')."</label>",2);
        $output .= JHTML::_('jwf.indentedLine',"<textarea onchange='saveElement()' id='properties_input_task'></textarea>",2);
        $output .= JHTML::_('jwf.indentedLine',"<div class='clear'></div>",2);

        $output .= JHTML::_('jwf.indentedLine',"<label id='properties_lbl_acl' for='properties_input_acl'>".JText::_('Group')."</label>",2);
        $output .= JHTML::_('select.genericlist',  array(), "properties_input_acl", 'onchange="saveElement()" class="inputbox" size="1"', 'value', 'text','','properties_input_acl');
        $output .= JHTML::_('jwf.indentedLine',"<div class='clear'></div>",2);

        $output .= JHTML::_('jwf.indentedLine',"<input onclick='deleteSelectedElement()' id='properties_input_delete' type='button' value='".JText::_('Delete Station')."' />",2);
        $output .= JHTML::_('jwf.indentedLine',"<div class='clear'></div>",2);

        $output .= JHTML::_('jwf.indentedLine',"</fieldset>",2);

        $output .= JHTML::_('jwf.indentedLine',"<fieldset><legend>".JText::_('Allocated Time')."</legend>",2);
        $output .= JHTML::_('jwf.indentedLine',"<label style='width:40px;' id='properties_lbl_hours' for='properties_input_hours'>".JText::_('Hours')."</label>",2);
        $output .= JHTML::_('select.integerlist',  0,23,1 , "properties_input_hours", 'id="properties_input_hours" style="width:40px;" onchange="saveElement()" class="inputbox" size="1"');
        $output .= JHTML::_('jwf.indentedLine',"<div class='clear'></div>",2);
        $output .= JHTML::_('jwf.indentedLine',"<label style='width:40px;' id='properties_lbl_days' for='properties_input_days'>".JText::_('Days')."</label>",2);
        $output .= JHTML::_('select.integerlist',  0,30,1 , "properties_input_days", 'id="properties_input_days" style="width:40px;" onchange="saveElement()" class="inputbox" size="1"');
        $output .= JHTML::_('jwf.indentedLine',"<div class='clear'></div>",2);
        $output .= JHTML::_('jwf.indentedLine',"<label style='width:40px;' id='properties_lbl_months' for='properties_input_months'>".JText::_('Months')."</label>",2);
        $output .= JHTML::_('select.integerlist',  0,11,1 , "properties_input_months", 'id="properties_input_months" style="width:40px;" onchange="saveElement()" class="inputbox" size="1"');
        $output .= JHTML::_('jwf.indentedLine',"<div class='clear'></div>",2);
        $output .= JHTML::_('jwf.indentedLine',"</fieldset>",2);





        $fieldList = array();
        $output .= JHTML::_('jwf.indentedLine',"<label id='properties_lbl_fields' for='properties_input_fields'>".JText::_('Active fields')."</label>",2);
        foreach($pManager->settings['field'] as $p) {
            $fieldList[] = JHTML::_('select.option', $p->id, JText::_($p->name));
        }
        $output .= JHTML::_('select.genericlist',  $fieldList, "properties_input_fields", 'multiple="multiple" class="inputbox field" size="5"', 'value', 'text');
        $output .= JHTML::_('jwf.indentedLine',"<div class='clear'></div>",2);


        $validationList = array();
        $output .= JHTML::_('jwf.indentedLine',"<label id='properties_lbl_validations' for='properties_input_validations'>".JText::_('Active validations')."</label>",2);
        foreach($pManager->settings['validation'] as $p) {
            $validationList[] = JHTML::_('select.option', $p->id, JText::_($p->name));
        }
        $output .= JHTML::_('select.genericlist',  $validationList, "properties_input_validations", 'multiple="multiple" class="inputbox validation" size="5"', 'value', 'text');
        $output .= JHTML::_('jwf.indentedLine',"<div class='clear'></div>",2);


        $output .= JHTML::_('jwf.indentedLine',"<h2>".JText::_('Active hooks')."</h2>",2);
        $output .= JHTML::_('jwf.indentedLine',"<form id='hook-settings-form'>",2);

        $hookList    = array();
        $paneOutput  = JHTML::_('jwf.hiddenPane');
        $paneOutput .= $pane->startPane('hooks-pane');
        foreach($pManager->settings['hook'] as $p) {

            $form        = new JParameter('', $p->xml);
            $params      = $form->renderToArray();
            $paneOutput .= JHTML::_('jwf.startJSBlock',2);
            $paneOutput .= JHTML::_('jwf.indentedLine','hookParameters["'.$p->id.'"] = new Array();',3);
            $i=0;
            foreach( $params as $key => $value ) {
                $paneOutput .= JHTML::_('jwf.indentedLine','hookParameters["'.$p->id.'"]['.$i.'] = "'.$key.'"',3);
                $i++;
            }
            $paneOutput .= JHTML::_('jwf.endJSBlock'  ,2);



            $title   = JText::_( $p->name .' '. JText::_('Settings') );
            $paneOutput .= '<div class="search-pane-list-handle"></div>';
            $paneOutput .= '<div class="search-pane-list-check"><input value="'.$p->id.'" type="checkbox" class="active-hooks" name="properties_input_hooks['.$p->id.']" checked="checked" id="properties_input_hook_'.$p->id.'" /></div>';
            $paneOutput .= $pane->startPanel( $title, "form-page" );


            $paneOutput .= $form->render('hooks-'.$p->id);
            $paneOutput .= $pane->endPanel();

        }
        $paneOutput .= $pane->endPane();
        $paneOutput .= JHTML::_('jwf.indentedLine',"</form>",2);


        $output .= $paneOutput;
        $output .= JHTML::_('jwf.indentedLine','</div>',2);
        return $output;


    }


}
