<?php
/**
 * @version	$Id$
 * @package	Zonales
 * @copyright	Copyright (C) 2009 Mediabit. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controller');

/**
 * Controlador
 *
 * @package Zonales
 * @since 1.5
 */
class ZonalesController extends JController {
    /** @var class */
    var $_zonalesHelper = null;

    function __construct($default = array()) {
        parent::__construct($default);
        $this->_zonalesHelper = new comZonalesHelper();
    }

    function zonal() {
        global $option;
        $document = &JFactory::getDocument();
        $vType = $document->getType();
        $vName = JRequest::getCmd('view','zonal');
        $vLayout = JRequest::getCmd('layout','default');

        $view =& $this->getView($vName, $vType);
        $view->setLayout($vLayout);
        $view->display();
    }

    function mapa() {
        global $option;

        $document =& JFactory::getDocument();

        $vType = $document->getType();
        $vName = JRequest::getCmd('view','mapa');
        $vLayout = JRequest::getCmd('layout','default');

        $view =& $this->getView($vName, $vType);
        $view->setLayout($vLayout);
        $view->display();
    }

    /**
     * Setea o actualiza la variable de sesión con el zonal actualmente
     * seleccionado por el usuario, y redirecciona a la URL de retorno
     * especificada.
     */
    function setZonal($zname = null) {
        global $option;

        // parametros
        if (is_null($zname)) {
            $zname = JRequest::getVar('zname', NULL, 'post', 'string');
        }
        $return	= JRequest::getVar('return', 'index.php', 'post', 'string');

        // al utilizar flashbar & para separar variables el url puede estar divido
        $view = JRequest::getVar('view', NULL, 'post', 'string');
        $item = JRequest::getVar('Itemid', NULL, 'post', 'int');
        if ($view && $item)
            $return .= '&view=' . $view . '&Itemid=' . $item;

        $session = JFactory::getSession();
        if ($zname) {
            $zonal = $this->_zonalesHelper->getZonal($zname);
            if ($zonal) {
                $session->set('zonales_zonal_name', $zonal->name);
            }
        }
        else {
            $session->set('zonales_zonal_name', NULL);
        }

        $this->setRedirect($return);
    }

    /**
     * Setea o actualiza la variable de sesión con el zonal actualmente
     * seleccionado por el usuario, y redirecciona a la URL de retorno
     * especificada.
     *
     * @param int $id identificador del zonal
     */
    function setZonalById($id) {
        if (is_null($id)) {
            $id = JRequest::getVar('zonalid', NULL, 'post', 'int');
        }

        $dbo	= & JFactory::getDBO();
        $query = 'SELECT ' . $dbo->nameQuote('v.id') .', '. $dbo->nameQuote('v.name') .', '.
                $dbo->nameQuote('v.label')
                .' FROM ' . $dbo->nameQuote('#__custom_properties_values') . ' v'
                .' WHERE '. $dbo->nameQuote('v.id') .' = '. $id;
        $dbo->setQuery($query);
        $r = $dbo->loadObject();
        $this->setZonal($r->name);
    }

    /**
     * Setea o actualiza la variable de sesión con el zonal actualmente
     * seleccionado por el usuario, y retorna mensaje de confirmación.
     * Este mètodo debe ser utilizado en operaciones tipo ajax.
     */
    function setZonalAjax() {
        $zname	= JRequest::getVar('zname', NULL, 'post', 'string');
        $sesid  = JRequest::getVar('PHPSESSID', NULL, 'post', 'string');
        $usemap = JRequest::getVar('usemap', true, 'post', 'string');

        if ($sesid) session_id($sesid);

        $result = "failure";
        $message = "Zonal desconocido";

        if ($zname) {
            $zonal = $this->_zonalesHelper->getZonal($zname);

            if ($zonal) {
                $session = JFactory::getSession();
                $session->set('zonales_zonal_name', ($zonal ? $zonal->name : NULL));

                $result = "success";
                $message = $zonal->label;
            }
        }

        if ($usemap) {
            echo "result=$result&message=$message";
        }

        return;
    }

    /**
     *
     * @return <type>
     */
    function getFieldValuesAjax() {
        $partidoId = JRequest::getVar('fieldId', NULL, 'get', 'int');

        if ($partidoId) {
            $helper = new comZonalesHelper();
            $values = $helper->getLocalidadesByPartido($partidoId);

            if (sizeof($values)) {
                echo JHTML::_('select.genericlist', $values, 'localidad', 'size="1" class="required"', 'id', 'label');
            }
            else {
                echo JText::_("No se han recuperado localidades");
            }
        } else {
            echo 'prueba';
        }

        return;
    }

    /**
     * Almacena la noticia enviada por el usuario a traves de una instancia
     * del módulo mod_soycorresponsal.
     */
    function saveCorresponsalContent() {
        global $mainframe;
        jimport('json.json');
        $helper = new comZonalesHelper();

        $response = array();

        // chequea irregularidades en el request
        JRequest::checkToken() or jexit( 'Invalid Token' );

        // titulo del modulo que envio el request
        $moduleTitle = JRequest::getVar('module', NULL, 'post', 'string' );

        // chequea que el modulo especificado en el request sea valido
        if (!$moduleTitle) {
            jexit($helper->getJsonResponse('failure', 'Error interno', 'No module name'));
        } else {
            jimport('joomla.application.module.helper');
            $module = JModuleHelper::getModule('soycorresponsal', $moduleTitle);
            if ($module->id == 0) {
                jexit($helper->getJsonResponse('failure', 'Error interno', 'Invalid module'));
            }
        }

        // recupera parametros del módulo
        $modparams = new JParameter($module->params);

        // librería recaptcha
        jimport('recaptcha.recaptchalib');
        // parametros del componente
        $zonalesParams = &JComponentHelper::getParams( 'com_zonales' );
        $privatekey = $zonalesParams->get('recaptcha_privatekey', null);

        if (!$privatekey) {
            jexit($helper->getJsonResponse('failure', $modparams->get('error'), 'No recaptcha private key'));
        } else {
            // validamos la respuesta del usuario
            $challenge = JRequest::getVar('recaptcha_challenge_field', NULL, 'post', 'string' );
            $response = JRequest::getVar('recaptcha_response_field', NULL, 'post', 'string' );
            $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $challenge, $response);

            if ($catid == 0) {
                jexit($helper->getJsonResponse('failure', $modparams->get('error'), 'Invalid response'));
            } else {
                $category =& JTable::getInstance('category');
                $category->load($catid);
                $sectionid = $category->section;
            }

            $nullDate = $db->getNullDate();

            // tabla de contenidos joomla
            $row = & JTable::getInstance('content');

            if ($catid == 0) {
                jexit( getJsonResponse('failure', $modparams->get('error'), 'Invalid response'));
            } else {
                $category =& JTable::getInstance('category');
                $category->load($catid);
                $sectionid = $category->section;
            }

            $createdate =& JFactory::getDate();

            $row->title = JRequest::getVar('title', NULL, 'post', 'string');
            $row->sectionid = $sectionid;
            $row->catid = $catid;
            $row->version = 0;
            $row->state = 0;
            $row->ordering = 0;
            $row->images = array ();
            $row->publish_down = $nullDate;
            $row->created_by = $user->get('id');
            $row->modified = $nullDate;

            // corrección de la fecha
            $config =& JFactory::getConfig();
            $tzoffset = $config->getValue('config.offset');
            $date =& JFactory::getDate('now', $tzoffset);
            $row->created = $date->toMySQL();
            $row->publish_up = $date->toMysQL();

            // se redondea timestamp de creación
            if ($row->created && strlen(trim( $row->created )) <= 10) {
                $row->created 	.= ' 00:00:00';
            }

            // Prepare the content for saving to the database
            require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_content'.DS.'helper.php');
            ContentHelper::saveContentPrep( $row );

            // Se agregan nombre de usuario, correo y telefono
            $enviaNombre = JRequest::getVar('nombre', NULL, 'post', 'string');
            $enviaEmail = JRequest::getVar('email', NULL, 'post', 'string');
            $enviaTel = JRequest::getVar('telefono', NULL, 'post', 'string');
            $row->introtext = $row->introtext . "<p>Envio esta noticia:</p><p>Nombre: $enviaNombre<br/>Email: $enviaEmail<br/>Telefono: $enviaTel</p>";

            // Make sure the data is valid
            if (!$row->check()) {
                JError::raiseError( 500, $db->stderr() );
            }

            // Store the content to the database
            if (!$row->store()) {
                JError::raiseError( 500, $db->stderr() );
            }

            // Check the article and update item order
            $row->checkin();
            $row->reorder('catid = '.(int) $row->catid.' AND state >= 0');

            // Asignamos los tags de Custom Properties según los valores de zonal y localidad
            $partidoId = JRequest::getVar('partidos', NULL, 'post', 'int');
            $zonaId = JRequest::getVar('localidad', NULL, 'post', 'int');

            $query = "REPLACE INTO #__custom_properties (ref_table, content_id,field_id,value_id)
					SELECT 'content','$row->id',v.field_id AS field, v.id AS value
					FROM #__custom_properties_values v 
                                        WHERE v.parent_id = $partidoId
					AND v.id = $zonaId 
                                        OR v.name = 'la_voz_del_vecino'";
            $database = JFactory::getDBO();
            $database->setQuery($query);
            $database->query();

            // Todo ok, enviamos confirmación
            echo $helper->getJsonResponse('success', $modparams->get('confirmacion'));
            return;
        }
    }
    

    /**
     * Genera un select html listando todos los values que sean hijos directos
     * del tag con el identificador $id.
     *
     * @param int $id identificador tag padre
     * @param string $name id y nombre del select html
     * @return String select html
     */
    function getItemsAjax($id = null, $name = null) {
        if(is_null($id)) {
            $id = JRequest::getVar('id', NULL, 'get', 'int');
        }
        if(is_null($name)) {
            $name = JRequest::getVar('name', NULL, 'get', 'string');
        }

        $helper = new comZonalesHelper();
        $parents = $helper->getItems($id);

        echo JHTML::_('select.genericlist', $parents, $name,
        'size="1" class="required"', 'id', 'label');
        return;
    }
}
