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

/**
 * Value Management View
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesViewCpvalues extends JView {
    /**
     * CP Values view display method
     * @return void
     **/
    function display($tpl = null) {
        global $option, $mainframe;

        $this->_context = $option.'h_value';		// nombre del contexto

        $this->cid = JRequest::getVar('cid', 0, '', 'array');
        $this->pid = JRequest::getVar('pid', 0, '', 'int');
        switch($this->getLayout()) {
            case 'edit' :
            //Get the value and your childrens
                $model = $this->getModel('cpvalue');
                $value = $model->getData();
                if ($value->parent_id == 0) {
                    $value->parent_id=$this->pid;
                }
                $childs = $model->getChilds();
                //$values = $model->getValues();
                $this->assignRef('value', $value);
                $this->assignRef('childs', $childs);

                //create the parent list
                $emptyValue = $model->getDefaultValue();
                $emptyValue->parent_id = 0;
                $emptyValue->label = JTEXT::_('Root_Value');
                //$this->assignRef('values', $values);
                $types = array ();

                $pmodel =& $this->getModel('cpvalues');

                //$pmodel = $this->getModel('cpvalue');

                if ($this->pid != 0) {
                    $model->setId($this->pid);
                    $pvalue = $model->getData();
                    $items = $pmodel->getAllFilterByField($this->cid[0], $pvalue->field_id);
                } else {
                    $items = $pmodel->getAll($this->cid[0]);
                }

                $items[] = $emptyValue;
                $this->assignRef('items', $items);

                //create the field list
                if ($this->pid == 0) {
                    $fmodel =& $this->getModel('cpfields');
                    $fields = $fmodel->getAll();
                    $this->assignRef('fields', $fields);
                }

                break;

            default:

                $this->_filter_field = $mainframe->getUserStateFromRequest( $this->_context.'filter_field', 'filter_field', 0, 'int' );

                $fmodel = $this->getModel('cpfield');

                if ($this->pid == 0) {
                    //create the field list
                    $model =& $this->getModel('cpfields');
                    $fields = $model->getAll();
                    $emptyValue = $fmodel->getDefaultValue();
                    $emptyValue->label = JTEXT::_('- Field -');
                    array_unshift($fields, $emptyValue);

                    $this->_lists['field_list'] = JHTML::_('select.genericlist',  $fields, 'filter_field', 'class="inputbox" size="1" onchange="document.adminForm.submit();" ','id','label', $this->_filter_field);
                }

                // filtro
                
                $model =& $this->getModel('cpvalues');

                if ( !is_null($this->_filter_field) ) {
                    $items = $model->getList(true, $this->cid[0], $this->_filter_field);
                } else {
                    $items = $model->getList(true,$this->cid[0]);
                }

                //$model =& $this->getModel('cpvalues');
                //$items = $model->getList(true,$this->cid[0]);
                $page = $model->getPagination();

                for ($i=0, $n=count( $items ); $i < $n; $i++) {
                    $fmodel->setId($items[$i]->field_id);
                    $field = $fmodel->getData();
                    $items[$i]->field = $field->label;
                }

                if ($this->cid[0] != 0) {
                    $pmodel = $this->getModel('cpvalue');
                    $pvalue = $pmodel->getData();
                    $this->back = $pvalue->parent_id;
                    $this->actual = $pvalue->label;
                    $this->root = 1;
                } else {
                    $this->root = 0;
                    $this->back = 0;
                    $this->actual = '';
                }

                $itemsBC = $this->armaPathWay($this->cid[0]);

                $this->assignRef( 'itemsBC', $itemsBC );
                $this->assignRef( 'items', $items );
                $this->assignRef( 'page', $page );
        }
        parent::display($tpl);
    }

    function armaPathWay($cid = 0) {
        global $option, $mainframe;
        //$objc =& $this->get('Data');

        $items = array ();

        do {
            $model = $this->getModel('cpvalue');
            $model->setId($cid);
            $value = $model->getData();
            $item = new stdClass();
            $item->name = $value->label;
            $item->link = JRoute::_('index.php?option=com_customproperties&controller=values&cid[]='.$cid);
            array_unshift($items, $item);
            $cid = $value->parent_id;
        } while ($cid != 0);

        return $items;
    }

}
