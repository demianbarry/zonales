<?php
/**
 * @version	$Id$
 * @package	Zonales
 * @subpackage	Eq
 * @copyright	Copyright (C) 2005 - 2008. Mediabit. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Este helper provee metodos para generar el query solr a utilizar en el
 * componente de contenido modificado.
 */
class comEqZonalesContentHelper {

    function getContent($limit = 0, $limitstart = 0) {
        $zonalesParams = &JComponentHelper::getParams( 'com_eqzonales' );

        $solr_url = $pluginParams->get( 'solr_url', null );
        $solr_port = $pluginParams->get( 'solr_port', null );
        $solr_webapp = $pluginParams->get( 'solr_webapp', null );

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

        $params = array ();

        $params['fq'] = $this->getWhere();
        $params['sort'] = $this->getOrder();
        $params['fl'] = $this->getFieldList();

        try {
            $results = $solr->search("*:*", $limitstart, $limit, $params);
        }
        catch (Exception $e) {
            return array();
        }

        return $results;
    }

    function getFieldList() {
        $fieldList =
            "category,id,title,alias,title_alias,introtext,fulltext,sectionid,".
            "state,catid,created,created_by,created_by_alias,modified,modified_by,".
            "checked_out,checked_out_time,publish_up,publish_down,attribs,hits,".
            "images,urls,ordering,metakey,metadesc,access,slug,catslug,readmore,".
            "author,usertype,groups,author_email";
    }
    
    function getOrder() {
        // Get the page/component configuration
        $orderby = "score desc, created desc";
        return $orderby;
    }
    
    function getWhere() {
        global $mainframe;
        
        // usuario
        $user =& JFactory::getUser();
        $gid = $user->get('aid', 0);
        
        // fecha
        $jnow =& JFactory::getDate();
        $now = $jnow->toMySQL();
        
        // Get the page/component configuration
        $params = &$mainframe->getParams();
        $noauth = !$params->get('show_noauth');
        $nullDate = $this->_db->getNullDate();
        
        $where = "";
        
        // Does the user have access to view the items?
        if ($noauth) {
            $where .= "+access:[* TO $gid]";
        }
        
        if ($user->authorize('com_content', 'edit', 'content', 'all')) {
            $where .= "+state:[0 TO *]";
        }
        else {
            $where .= '+state:[1 TO 1]';
            $where .= '+(hasPublishUpDate:false OR publishUpDate:[* TO NOW])';
            $where .= '+(hasPublishDownDate:false OR publishDownDate:[NOW TO *])';
        }
        
        return $where;
    }
}