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

}