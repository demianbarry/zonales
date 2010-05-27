<?php
/**
 * @version	$Id: mod_soycorresponsal.php 408 2010-03-02 21:40:33Z ignacioaita $
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
defined('_JEXEC') or die('Restricted access');

// helper zonales
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'contenthelper.php');

// lista de zonales, zonal actualmente seleccionado
$helper = new comEqZonalesContentHelper();

/*
 * Ejecuto una búsqueda Solr de los artículos publicados como soy corresponsal.
*/
$limit = 0;
$results = array();
try {
    $results = $helper->getContent(0, $limit, "+tags_values:la_voz_del_vecino");
}
catch (Exception $e) {
    print_r($e->getMessage());
}
$return = array();
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

        $item->created = $doc->created;
        $item->text = $doc->introtext;
        $item->browsernav = '';
        $return[] = $item;
    }

}

require(JModuleHelper::getLayoutPath('mod_lavozdelvecino'));