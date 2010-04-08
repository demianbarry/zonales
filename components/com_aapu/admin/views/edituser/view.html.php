<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AapuViewEditUser extends JView {
    /** @var QuotaOptions */
    var $_user;

    function display($tpl = null, $userId = null) {
        global $option, $mainframe;

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

        foreach ($types as $type) {
            $attributesModel->setWhere(' attribute_class_id =  '. $type->id.' AND published = 1');
            $type->attributes = $attributesModel->getAll(true);
            foreach ($type->attributes as $attr) {
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
            }
        }

        // Asigna variables en la vista y la muestra
        $this->assignRef('user', $this->_user);
        $this->assignRef('types', $types);

        parent::display($tpl);
    }
}