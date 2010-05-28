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

    /**
     * Recupera el total de resultados.
     * 
     * @param <type> $limit
     * @param <type> $limitstart
     * @param <type> $queryParams 
     */
    function getTotal($limitstart = 0, $limit = 0, $additionalParams = '') {
        $results = $this->getSolrResults($limitstart, $limit, $additionalParams);

        if (!is_null($results)) {
            return count($results->response->docs);
        } else {
            return 0;
        }
    }

    /**
     *
     * @param <type> $limit
     * @param <type> $limitstart
     * @param <type> $queryParams
     * @return <type>
     */
    function getContent($limitstart = 0, $limit = 0, $additionalParams = '') {
        $results = $this->getSolrResults($limitstart, $limit, $additionalParams);

        if (!is_null($results)) {
            return $results->response->docs;
        } else {
            return array();
        }
    }

    /**
     *
     * @param <type> $limit
     * @param <type> $limitstart
     * @param <type> $queryParams
     * @return <type>
     */
    function getSolrResults($limitstart = 0, $limit = 0, $additionalParams = '') {

        $zonalesParams = &JComponentHelper::getParams( 'com_eqzonales' );

        // recupera parametros
        $solr_url = $zonalesParams->get( 'solr_url', null );
        $solr_port = $zonalesParams->get( 'solr_port', null );
        $solr_webapp = $zonalesParams->get( 'solr_webapp', null );

        // No se especifico la url de solr
        if ($solr_url == null) {
            return null;
        }
        // No se especifico el puerto de solr
        if ($solr_port == null) {
            return null;
        }
        // No se especifico el puerto de webapp
        if ($solr_webapp == null) {
            return null;
        }

        // The Apache Solr Client library should be on the include path
        // which is usuaPlly most easily accomplished by placing in the
        // same directory as this script ( . or current directory is a default
        // php include path entry in the php.ini)
        jimport('SolrPhpClient.Apache.Solr.Service');

        // create a new solr service instance - host, port, and webapp
        // path (all defaults in this example)
        $solr = new Apache_Solr_Service($solr_url, $solr_port, $solr_webapp);

        $queryParams = array ();

        $queryParams['fq'] = Array(
            $this->getWhere(),
            $additionalParams
            );
        $queryParams['sort'] = $this->getOrder();
        $queryParams['fl'] = $this->getFieldList();
        $queryParams['bq'] = $this->getEqPreferences();
        $queryParams['qt'] = "zonalesContent";

        try {
            $results = $solr->search("", $limitstart, $limit, $queryParams);
        }
        catch (Exception $e) {
            return null;
        }

        return $results;
    }

    /**
     *
     *
     * @return <type>
     */
    function getFieldList() {
        $fieldList =
                "category,id,title,alias,title_alias,introtext,fulltext,sectionid,".
                "state,catid,created,created_by,created_by_alias,modified,modified_by,".
                "checked_out,checked_out_time,publish_up,publish_down,attribs,hits,".
                "images,urls,ordering,metakey,metadesc,access,slug,catslug,readmore,".
                "author,usertype,groups,author_email";

        return $fieldList;
    }

    /**
     *
     *
     * @return <type>
     */
    function getOrder() {
        // Get the page/component configuration
        $orderby = "score desc,created desc";
        return $orderby;
    }

    /**
     *
     * @global <type> $mainframe
     * @param <type> $additionalParams
     * @return <type>
     */
    function getWhere($additionalParams = '') {
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
        //$nullDate = $this->_db->getNullDate();

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
            $where .= '+(hasPublishUpDate:false OR publish_up:[* TO NOW])';
            $where .= '+(hasPublishDownDate:false OR publish_down:[NOW TO *])';
        }

        // Zonal
        // lista de zonales, zonal actualmente seleccionado
        require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');
        $helper = new comZonalesHelper();
        $zonal = $helper->getZonal();
        $localidades = $helper->getLocalidadesByPartido($zonal->id);


        $localidadesList = array();
        foreach($localidades as $localidad)
            $localidadesList[] = $localidad->name;

        $where .= '+tags_values:('.implode(" ",$localidadesList).')';

        $where .= $additionalParams;

        return $where;
    }

    /**
     *
     * @return <type>
     */
    function getEqPreferences() {

        require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'eq.php');
        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'tables');
        // controladores
        $ctrlEq = new EqZonalesControllerEq();
        $ctrlEq->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );


        // recupera el usuario
        $user =& JFactory::getUser();

        $bq = "";

        if (!$user->guest) {
            // recupera ecualizador del usuario
            $result = $ctrlEq->retrieveUserEqImpl($user->id);

            if (!is_null($result) && !empty($result)) {
                $eq = $result[0];

                foreach ($eq->bands as $band) {
                    $bq .= " tags_values:$band->band_name^$band->peso";
                }
            }
        }

        return $bq;

    }

}
