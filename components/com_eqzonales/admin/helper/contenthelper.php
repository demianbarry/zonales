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
     * Controlador ecualizador
     * @var EqZonalesControllerEq
     */
    var $_ctrlEq = null;

    function __construct() {
        require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'eq.php');
        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'tables');

        // controladores
        $this->_ctrlEq = new EqZonalesControllerEq();
        $this->_ctrlEq->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );
    }

    /**
     * Recupera el total de resultados.
     *
     * @param int $limit
     * @param int $limitstart
     * @param string $queryParams
     * @return Array
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
     * Recupera artículos del indice de solr.
     *
     * @param int $limit
     * @param int $limitstart
     * @param string $queryParams
     * @return Array
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
     * Realiza un query en Solr, aplicando la configuración estándar especificada
     * en Joomla y los parámetros adicionales indicados.
     *
     * @param int $limit
     * @param int $limitstart
     * @param string $queryParams
     * @return Array
     */
    function getSolrResults($limitstart = 0, $limit = 0, $additionalParams = '') {

        $eqZonalesParams = &JComponentHelper::getParams( 'com_eqzonales' );

        // recupera parametros
        $solr_url = $eqZonalesParams->get( 'solr_url', null );
        $solr_port = $eqZonalesParams->get( 'solr_port', null );
        $solr_webapp = $eqZonalesParams->get( 'solr_webapp', null );
        $solr_querytype = $eqZonalesParams->get( 'solr_querytype', null );

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
        // No se especifico un QueryType apropiado
        if ($solr_querytype == null) {
            return null;
        }

        // The Apache Solr Client library should be on the include path
        // which is usually most easily accomplished by placing in the
        // same directory as this script ( . or current directory is a default
        // php include path entry in the php.ini)
        jimport('SolrPhpClient.Apache.Solr.Service');

        // create a new solr service instance - host, port, and webapp
        // path (all defaults in this example)
        $solr = new Apache_Solr_Service($solr_url, $solr_port, $solr_webapp);

        $queryParams = array();

        $fqParams = array();

        $fqParams[] = $this->getWhere();

        if (strlen($additionalParams) > 0) {
            $fqParams[] = $additionalParams;
        }

        $menu = $this->getMenuValue();
        if ($menu) {
            $fqParams[] = "tags_values:$menu";
        }

        $disabledBands = $this->getDisabledBands();
        if (strlen($disabledBands) > 0) {
            $fqParams[] = $disabledBands;
        }

        $queryParams['fq'] = $fqParams;
        $queryParams['sort'] = $this->getOrder();
        $queryParams['fl'] = $this->getFieldList();
        $queryParams['bq'] = $this->getEqPreferences();
        $queryParams['qt'] = $solr_querytype;

        try {
            $results = $solr->search("", $limitstart, $limit, $queryParams);
        }
        catch (Exception $e) {
            return null;
        }

        return $results;
    }

    /**
     * Lista de fields a recuperar como resultado de la búsqueda en Solr.
     *
     * @return String lista de fields a recuperar
     */
    function getFieldList() {
        $fieldList =
                "category,id,title,alias,title_alias,introtext,fulltext,sectionid,".
                "state,catid,created,created_by,created_by_alias,modified,modified_by,".
                "checked_out,checked_out_time,publish_up,publish_down,attribs,hits,".
                "images,urls,ordering,metakey,metadesc,access,slug,catslug,readmore,".
                "author,usertype,groups,author_email,catalias";

        return $fieldList;
    }

    /**
     * Especifica sobre que campos se utilizarán para ordenar el contenido,
     * y la dirección del ordenamiento.
     *
     * @return String query con parámetros solr para la búsqueda
     */
    function getOrder() {
        // Get the page/component configuration
        $orderby = "score desc,created desc";
        return $orderby;
    }

    /**
     * Genera los parámetros necesarios para recuperar los contenidos de acuerdo
     * a la configuración de Joomla: artículos publicados, nivel de acceso, etc.
     *
     * @global $mainframe
     * @return String query con parámetros solr para la búsqueda
     */
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

        // Zonal - lista de zonales, zonal actualmente seleccionado
        require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');
        $helper = new comZonalesHelper();
        $zonal = $helper->getZonal();

        // Si se recupera correctamente el zonal actual, se compone la lista
        // de zonales de preferencia para el usuario
        if ($zonal->id) {
            $localidades = $helper->getLocalidadesByPartido($zonal->id);

            $localidadesList = array();
            foreach($localidades as $localidad) {
                $localidadesList[] = $localidad->name;
            }

            $where .= '+tags_values:('.implode(" ",$localidadesList).')';
        }

        return $where;
    }

    /**
     * Genera opciones de búsqueda para recuperar contenido de acuerdo a los
     * pesos indicados en el ecualizador de interés.
     *
     * @return String parametros solr para el query.
     */
    function getEqPreferences() {
        // recupera el usuario
        $user =& JFactory::getUser();

        $bq = "";

        // recupera ecualizador del usuario
        $result = $this->_ctrlEq->retrieveUserEqImpl($user->id);

        if (!is_null($result) && !empty($result)) {
            $eq = $result[0];

            foreach ($eq->bands as $band) {
                if ($band->peso > 0) {
                    $bq .= " tags_values:$band->band_name^$band->peso";
                }
            }
        }

        return $bq;
    }

    /**
     * Construye parámetros para filter query que excluye las bandas que
     * cuentan con un peso igual a cero.
     *
     * @return String parámetros de solr para la búsqueda.
     */
    function getDisabledBands() {
        // recupera el usuario
        $user =& JFactory::getUser();

        $fq = "";

        // recupera ecualizador del usuario
        $result = $this->_ctrlEq->retrieveUserEqImpl($user->id);

        if (!is_null($result) && !empty($result)) {
            $eq = $result[0];

            foreach ($eq->bands as $band) {
                if ($band->peso == 0) {
                    //$fq .= " tags_values:$band->band_name^$band->peso";
                    $fq .= " -tags_values:$band->band_name";
                }
            }
        }

        return $fq;
    }

    /**
     * Recupera el tag_value asociado al menu actual.
     *
     * @return String
     */
    function getMenuValue() {
        $banda = JRequest::getString('banda', NULL, 'get');
        return $banda;
    }

}
