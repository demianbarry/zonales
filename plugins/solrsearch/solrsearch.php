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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Agrega el helper de zonales
require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');

// import library dependencies
jimport('joomla.event.plugin');

class plgSearchSolrsearch extends JPlugin {
    var $_zhelper = null;

    function plgSearchSolrsearch( &$subject) {
        parent::__construct($subject);

        // carga los parametros del plugin
        $this->_plugin =& JPluginHelper::getPlugin('search', 'solrsearch');
        $this->_params = new JParameter($this->_plugin->params);

        $this->_zhelper = new comZonalesHelper();
    }

    function onSearchAreas() {
        static $areas = array(
        'content' => 'Articles'
        );
        return $areas;
    }
     
    function onSearch($text, $phrase='', $ordering='', $areas=null) {
        require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
        require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_search'.DS.'helpers'.DS.'search.php');

        $return = array();

        $searchText = $text;
        if (is_array($areas)) {
            if (!array_intersect($areas, array_keys($this->onSearchAreas()))) {
                return $return;
            }
        }

        $text = trim( $text );
        if ($text == '') {
            return $return;
        }

        // objeto usuario joomla
        $user =& JFactory::getUser();

        // conexión a la base de datos
        $db =& JFactory::getDBO();
        $nullDate = $db->getNullDate();
        $date =& JFactory::getDate();
        $now = $date->toMySQL();

        // load plugin params info
        $plugin	=& JPluginHelper::getPlugin('search', 'solrsearch');
        $pluginParams = new JParameter( $plugin->params );

        $sContent = $pluginParams->get( 'search_content', 1 );
        $sUncategorised = $pluginParams->get( 'search_uncategorised', 1 );
        $sArchived = $pluginParams->get( 'search_archived', 1 );
        $limit = $pluginParams->def( 'search_limit', 50 );
        $ws_endpoint = $pluginParams->def( 'ws_endpoint', null );

        // No se especifico ningún endpoint en la configuración
        if ($ws_endpoint == null) {
            return array();
        }

$xmlstr = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<ns0:requerimiento  xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'
   xmlns:ns0='http://xml.netbeans.org/schema/ZonalesSolrQueryRequerimiento'
   xsi:schemaLocation='http://xml.netbeans.org/schema/ZonalesSolrQueryRequerimiento ZonalesSolrQueryRequerimiento.xsd'>
    <ns0:contenido>
        <ns0:query></ns0:query>
    </ns0:contenido>
</ns0:requerimiento>
XML;

        $ns = "http://www.zonales.com.ar/schema/ZonalesSolrQueryRespuesta";
        $ns1 = "http://xml.netbeans.org/schema/ZonalesSolrQueryRequerimiento";

        $option = array('trace' => 1, 'exceptions' => TRUE);

        /**
         * http://bugs.php.net/bug.php?id=34657
         * http://bugs.xdebug.org/view.php?id=249
         *
         * "Supuestamente" solucionado, pero igualmente sigue apareciendo un
         * error fatal que no puede ser atrapado por el catch... para resolver
         * el problema, temporalmente, se desactiva xdebug, si esta habilitado.
         */
        if (array_search('xdebug', get_loaded_extensions()) !== FALSE) {
            xdebug_disable();
        }

        try {
            $client = new SoapClient($ws_endpoint, $option);
        } catch (Exception $e) {
            return $return;
        }

        // ---------- requerimiento ----------------
        $xml = simplexml_load_string($xmlstr, null, null, $ns1);
        $xml->contenido->query = $text;

        // Ordenamiento
        $sortFields = $xml->contenido->addChild('ns0:sortFields', null, $ns1);

        // Ordenamiento por defecto, en base al 'score' de solr
        $sort = $sortFields->addChild('ns0:sort', null, $ns1);
        $sort->addAttribute("field", "score");
        $sort->addAttribute("order", "desc");

        // Ordenamiento seleccionado por el usuario
        $sort = $sortFields->addChild('ns0:sort', null, $ns1);

        /**
         * TODO: Parametrizar en el backend que campo en el esquema de solr
         * que deberá utilizarse para cada tipo de ordenamiento, y así envíar
         * en el resquest unicamente el concepto (ej, 'oldest') y el orden.
         */
        switch ($ordering) {
            case 'oldest':
                $sort->addAttribute("field", "creationDate");
                $sort->addAttribute("order", "asc");
                break;

            case 'popular':
                $sort->addAttribute("field", "hits");
                $sort->addAttribute("order", "desc");
                break;

            case 'alpha':
                $sort->addAttribute("field", "title_s");
                $sort->addAttribute("order", "asc");
                break;

            case 'category':
                $sort->addAttribute("field", "category_s");
                $sort->addAttribute("order", "asc");
                break;

            case 'newest':
            default:
                $sort->addAttribute("field", "creationDate");
                $sort->addAttribute("order", "desc");
                break;
        }
        
        $parametros = $xml->contenido->addChild('ns0:parametros', null, $ns1);

        // Informacion del Usuario
        $parametro = $parametros->addChild('ns0:parametro', null, $ns1);
        $parametro->addChild('ns0:clave', null, $ns1);
        $parametro->addChild('ns0:valor', null, $ns1);
        $parametro->clave = 'usuario';
        $parametro->valor = $user->id;

        // User aid
        $parametro = $parametros->addChild('ns0:parametro', null, $ns1);
        $parametro->addChild('ns0:clave', null, $ns1);
        $parametro->addChild('ns0:valor', null, $ns1);
        $parametro->clave = 'usuario_aid';
        $parametro->valor = $user->aid;

        // Límite de la búsqueda
        $parametro = $parametros->addChild('ns0:parametro', null, $ns1);
        $parametro->addChild('ns0:clave', null, $ns1);
        $parametro->addChild('ns0:valor', null, $ns1);
        $parametro->clave = 'limit';
        $parametro->valor = $limit;

        // Búsqueda en Solr
        $response = $client->solrquery(array("mensaje" => $xml->asXML()));

        if ($response == null || !isset($response->return)) {
            return $return;
        }

        $xmlResp = simplexml_load_string($response->return, null, null, $ns);
        
        foreach ($xmlResp->contenido->resultados->resultado as $r) {
            $item = new stdClass();
            $item->href = ContentHelperRoute::getArticleRoute($r->id);
            $item->title = $r->titulo;

            if (isset($r->section) && isset($r->category)) {
                $item->section = $doc->section . '/' . $doc->category;
            } else {
                $item->section = JText::_('Uncategorised Content');
            }

            $item->created = $now;
            $item->text = $r->texto;
            $item->browsernav = '';
            $return[] = $item;
        }

        return $return;
    }
}