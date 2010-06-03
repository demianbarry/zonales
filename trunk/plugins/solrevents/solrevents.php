<?php
/**
 * @version		$Id$
 * @package		Zonales
 * @subpackage	Ecualizador de Interés
 * @copyright	Copyright (C) 2009 - 2010 Mediabit
 * @license		GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

/**
 * EqEvents User Plugin
 *
 * El presente plugin se encarga de ejecutar las accioens de creación,
 * modificación o eliminación de ecualizadores según los distintos eventos que
 * puedan darse en la administración de usuarios.
 *
 * @package	Zonales
 * @subpackage	Ecualizador de Interés
 * @since 	1.0
 */
class plgContentSolrEvents extends JPlugin {

    /**
     * Constructor
     *
     * @param object $subject The object to observe
     * @param object $params  The object that holds the plugin parameters
     * @since 1.5
     */
    function plgContentExample( &$subject, $params ) {
        parent::__construct( $subject, $params );
    }


    /**
     * Una vez que el contenido es creado o actualizado, se reindexa el contenido
     * en Solr.
     *
     * @param 	object		A JTableContent object
     * @param 	bool		If the content is just about to be created
     * @return	void
     */
    function onAfterContentSave( &$article = null, $isNew = null) {
        global $mainframe;

       $eqzParams = &JComponentHelper::getParams( 'com_eqzonales' );

        // recupera parametros
        $solr_url = $eqzParams->get( 'solr_url', null );
        $solr_port = $eqzParams->get( 'solr_port', null );
        $solr_webapp = $eqzParams->get( 'solr_webapp', null );
        $solr_dataimport = $eqzParams->get( 'solr_dataimport', null);
        $solr_deltaimport = $eqzParams->get( 'solr_deltaimport', null);

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
        if ($solr_deltaimport == null) {
            return;
        }

        // Se construye el request
        $http = new HttpRequest("$solr_url:$solr_port/$solr_webapp/$solr_dataimport?command=$solr_deltaimport", HttpRequest::METH_GET);
        $http->send();

        return true;
    }

}