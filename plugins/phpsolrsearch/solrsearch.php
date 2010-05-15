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

        // recupera parametros desde el plugin
        $sContent = $pluginParams->get( 'search_content', 1 );
        $sUncategorised = $pluginParams->get( 'search_uncategorised', 1 );
        $sArchived = $pluginParams->get( 'search_archived', 1 );
        $limit = $pluginParams->def( 'search_limit', 50 );
        $solr_url = $pluginParams->def( 'solr_url', null );
        $solr_port = $pluginParams->def( 'solr_port', null );
        $solr_webapp = $pluginParams->def( 'solr_webapp', null );

        // No se especifico la url de solr
        if ($solr_url == null) {
            return array();
        }
        // No se especifico el puerto de solr
        if ($solr_port == null) {
            return array();
        }
        // No se especifico el puerto de webapp
        if ($solr_webapp == null) {
            return array();
        }

        // The Apache Solr Client library should be on the include path
        // which is usually most easily accomplished by placing in the
        // same directory as this script ( . or current directory is a default
        // php include path entry in the php.ini)
        jimport('SolrPhpClient.Apache.Solr.Service');

        // create a new solr service instance - host, port, and webapp
        // path (all defaults in this example)
        $solr = new Apache_Solr_Service($solr_url, $solr_port, $solr_webapp);

        $additionalParameters = array(
                'published' => 'true',
                'section_published' => 'true',
                'category_published' => 'true',
                'hasPublishUpDate' => 'false',
                'publishUpDate' => '[* TO NOW])',
                'hasPublishDownDate' => 'false',
                'publishDownDate' => '[NOW TO *])',
                'a_access' => '[* TO ' . $user->aid . ']',
                'section_access' => '[* TO ' + $user->aid + ']',
                'category_access' => '[* TO ' + $user->aid + ']'
        );

        /**
         * TODO: Parametrizar en el backend que campo en el esquema de solr
         * que deberá utilizarse para cada tipo de ordenamiento, y así envíar
         * en el resquest unicamente el concepto (ej, 'oldest') y el orden.
         */
        switch ($ordering) {
            case 'oldest':
                $additionalParameters['sort'] = 'creationDate asc';
                break;

            case 'popular':
                $additionalParameters['sort'] = 'hits desc';
                break;

            case 'alpha':
                $additionalParameters['sort'] = 'title_s asc';
                break;

            case 'category':
                $additionalParameters['sort'] = 'category_s asc';
                break;

            case 'newest':
            default:
                $additionalParameters['sort'] = 'creationDate desc';
                break;
        }

        try {
            $results = $solr->search($text, 0, $limit, $additionalParameters);
        }
        catch (Exception $e) {
            return array();
        }

        // display results
        if ($results) {

            $total = (int) $results->response->numFound;
            $start = min(1, $total);
            $end = min($limit, $total);

            // iterate result documents
            foreach ($results->response->docs as $doc) {
                $item = new stdClass();
                $item->href = ContentHelperRoute::getArticleRoute($doc->id);
                $item->title = $doc->title;

                if (isset($doc->section) && isset($doc->category)) {
                    $item->section = $doc->section . '/' . $doc->category;
                } else {
                    $item->section = JText::_('Uncategorised Content');
                }

                $item->created = $doc->creationDate;
                $item->text = $doc->intro_content;
                $item->browsernav = '';
                $return[] = $item;
            }

        }

        return $return;
    }
}