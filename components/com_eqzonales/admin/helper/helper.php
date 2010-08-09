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
 * Funciones de apoyo para los controladores de EqZonales.
 */
class comEqZonalesHelper
{
    const FAILURE = 'FAIL';
    const SUCCESS = 'SUCCESS';

    /**
     * Serializa los parametros como un cadena JSON.
     *
     * @param String $status Resultado de la operación
     * @param String $msg Detalle del resultado de la operación
     * @return String Una cadena JSON con los parametros serializados
     */
    function getEqJsonResponse($status, $msg)
    {
        $response = array('status' => $status, 'msg' => $msg);
        return json_encode($response);
    }

    /**
     * Recupera de una cadena JSON un arreglo de parámetros.
     *
     * @param String $json_params Cadena JSON que contiene el parametro a extraer
     * @param String $type Mensaje de error a retornar si falla el parseo
     * @return StdObject El parametro recuperado
     */
    function getJsonParams($json_params,$type) {
        $params = json_decode($json_params);
        $jtext = new JText();
        
        if (is_null($params)) {
            echo $this->getEqJsonResponse(comEqZonalesHelper::FAILURE,
                    $jtext->sprintf('ZONALES_JSON_READ_FAILURE', $type));
            return false;
        }

        return $params;
    }
    
    /**
     * Lanzo un import de Solr.
     *
     * @param 	object		A JTableContent object
     * @param 	bool		If the content is just about to be created
     * @return	void
     */
    function launchSolrImport($fullImport = false) {
        $eqzParams = &JComponentHelper::getParams( 'com_eqzonales' );

        // recupera parametros
        $solr_url = $eqzParams->get( 'solr_url', null );
        $solr_port = $eqzParams->get( 'solr_port', null );
        $solr_webapp = $eqzParams->get( 'solr_webapp', null );
        $solr_dataimport = $eqzParams->get( 'solr_dataimport', null);
        $solr_import = $eqzParams->get( $fullImport ? 'solr_fullimport' : 'solr_deltaimport', null);

        // No se especifico la url de solr
        if ($solr_url == null) {
            return;
        }
        // No se especifico el puerto de solr
        if ($solr_port == null) {
            return;
        }
        // No se especifico el puerto de webapp
        if ($solr_webapp == null) {
            return;
        }
        // No se especifico el request handler
        if ($solr_dataimport == null) {
            return;
        }
        // No se especifico el comando de indexado incremental
        if ($solr_import == null) {
            return;
        }

        // Se construye el request
        $http = new HttpRequest("$solr_url:$solr_port/$solr_webapp/$solr_dataimport?command=$solr_import", HttpRequest::METH_GET);
        $http->send();

        return true;
    }
}