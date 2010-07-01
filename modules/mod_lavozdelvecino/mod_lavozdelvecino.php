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

// helper ContentHelperRoute
require_once(JPATH_BASE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

// helper zonales
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'contenthelper.php');

// lista de zonales, zonal actualmente seleccionado
$helper = new comEqZonalesContentHelper();

/*
 * Ejecuto una búsqueda Solr de los artículos publicados como soy corresponsal.
*/
$limit = 100;
$results = array();
try {
    $results = $helper->getContent(0, $limit, "tags_values:la_voz_del_vecino");
}
catch (Exception $e) {
    print_r($e->getMessage());
}
$articles = array();
$moreArticles = array();
$cantArticles = 0;
// display results
if ($results) {

    // parametros
    $cantArticles = $params->get('cant_articles');

    $total = (int) count($results);
    $start = min(1, $total);
    $end = min($limit, $total);

    // iterate result documents
    foreach ($results as $key => $doc) {
        $item = new stdClass();
        $item->href = ContentHelperRoute::getArticleRoute($doc->id);
        $item->title = $doc->title;

        if (isset($doc->section) && isset($doc->category)) {
            $item->section = $doc->section . '/' . $doc->category;
        } else {
            $item->section = JText::_('Uncategorised Content');
        }

        $item->created = $doc->created;

        $imgPos = strpos($doc->introtext, "<img ");
        $strPre = substr($doc->introtext, 0, $imgPos+5);
        $strPos = substr($doc->introtext, $imgPos+5);

        $item->introtext = $strPre.' style="width: 280px" '.$strPos;
        $item->text = $doc->fulltext;
        $item->browsernav = '';
        if($key < $cantArticles)
            $articles[] = $item;
        else
            $moreArticles[] = $item;
    }

}

require(JModuleHelper::getLayoutPath('mod_lavozdelvecino'));