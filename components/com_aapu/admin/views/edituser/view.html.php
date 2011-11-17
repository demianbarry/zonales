<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
jimport('joomla.html.pane');

class AapuViewEditUser extends JView {
    /** @var QuotaOptions */
    var $_user;

    function display($tpl = null, $userId = null) {
        $option = JRequest::getCMD('option');
        $mainframe = JFactory::getApplication(); 

        // Setea la barra de menúes y el titulo del documento según la accion
        $document = & JFactory::getDocument();

        if ($userId == null) {
            $this->_user =& $this->get('Data');
        } else {
            $userModel = &$this->getModel('users');
            $userModel->setId($userId);
            $this->_user = $userModel->getData(true);
        }
        
        // no se ejecuta si se accede al backend
        $app = JFactory::getApplication();

        if ($app->isAdmin()) {

            if (!$this->_user->id) {
                JToolBarHelper::title( JText::_( 'EDIT_USER' ) .': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>', 'menu.png' );
                $document->setTitle(JText::_( 'EDIT_USER' ) .': ['. JText::_( 'New' ) .']');
            } else {
                JToolBarHelper::title( JText::_( 'EDIT_USER' ) .': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'menu.png' );
                $document->setTitle(JText::_( 'EDIT_USER' ) .': ['. JText::_( 'Edit' ) .']');
            }
        }

        $typesModel = &$this->getModel('tabs');
        $types = $typesModel->getAll();

        $attributesModel = &$this->getModel('attributes');
        $datatypesModel = &$this->getModel('datatypes');
        $attrEntityModel = &$this->getModel('attribute_entity');

        $validArray = array("'name'","'username'","'email'");

        foreach ($types as $typekey => $type) {

            $attributesModel->setWhere(' attribute_class_id =  '. $type->id.' AND published = 1');

            if ($type->id != 1) {
                $type->attributes = $attributesModel->getAll(true);
                if ($type->attributes != null) {
                    foreach ($type->attributes as $attr) {
                        $validArray[] = "'attr_$attr->id'";
                        $componentName = JRequest::getVar('option');
                        $attrEntityModel->setWhere(' attribute_id = '. $attr->id . ' AND object_id = '. $this->_user->id);
                        $attr->value = $attrEntityModel->getAll(true);
                        if ($attr->value == null) {
                            $attr->value[0] = new stdClass();
                            $attr->value[0]->id = 0;
                            $attr->value[0]->value = '';
                            $attr->value[0]->value_int = null;
                            $attr->value[0]->value_double = null;
                            $attr->value[0]->value_date = null;
                            $attr->value[0]->value_boolean = null;
                        }
                        $datatypesModel->setId($attr->data_type_id);
                        $attr->datatype = $datatypesModel->getData(true);
                        if (strpos($attr->values_list,'SELECT') !== false) {
                            if (strpos($attr->values_list,'SELECT') == 0) {
                                if (stripos($attr->values_list,'pass')) {
                                    $attr->values_list = '';
                                } else {
                                    $dbase =& JFactory::getDBO();
                                    $query = $attr->values_list;
                                    $dbase->setQuery($query);
                                    $attr->values_list = $dbase->loadRowList();
                                }
                            }
                        }
                    }
                } else {
                    unset($types[$typekey]);
                }
            } else {
                $this->_user->attributes = $attributesModel->getAll(true);
                foreach ($this->_user->attributes as $attr) {
                    $validArray[] = "'attr_$attr->id'";
                    $componentName = JRequest::getVar('option');
                    $attrEntityModel->setWhere(' attribute_id = '. $attr->id . ' AND object_id = '. $this->_user->id);
                    $attr->value = $attrEntityModel->getAll(true);
                    if ($attr->value == null) {
                        $attr->value[0] = new stdClass();
                        $attr->value[0]->id = 0;
                        $attr->value[0]->value = '';
                        $attr->value[0]->value_int = null;
                        $attr->value[0]->value_double = null;
                        $attr->value[0]->value_date = null;
                        $attr->value[0]->value_boolean = null;
                    }
                    $datatypesModel->setId($attr->data_type_id);
                    $attr->datatype = $datatypesModel->getData(true);
                    if (strpos($attr->values_list,'SELECT') !== false) {
                     if (strpos($attr->values_list,'SELECT') == 0) {
                        if (stripos($attr->values_list,'pass')) {
                            $attr->values_list = '';
                        } else {
                            $dbase =& JFactory::getDBO();
                            $query = substr($attr->values_list, 0);
                            $dbase->setQuery($query);
                            $attr->values_list = $dbase->loadRowList();
                        }
                     }
                    }
                }
                unset($types[$typekey]);
            }

        }

        $this->_user->attrCount = count($validArray);
        $this->_user->validString = implode(",", $validArray);

        // Asigna variables en la vista y la muestra
        $this->assignRef('user', $this->_user);
        $this->assignRef('types', $types);

        parent::display($tpl);
    }
}