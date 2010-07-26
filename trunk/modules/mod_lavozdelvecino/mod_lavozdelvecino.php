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
    $results = $helper->getContent(0, $limit, array("tags_values:la_voz_del_vecino"));
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
        $item->id = $doc->id;
        $item->title = $doc->title;
        $item->sectionid = $doc->sectionid;
        $item->catid = $doc->catid;
        $item->state = $doc->state;
        $item->access = $doc->access;
        $item->slug = $doc->slug;
        $item->catslug = $doc->catslug;
        $item->created_by = $doc->created_by;


        if (isset($doc->section) && isset($doc->category)) {
            $item->section = $doc->section . '/' . $doc->category;
        } else {
            $item->section = JText::_('Uncategorised Content');
        }

        $item->created = $doc->created;

        /**
         * Si hay imágenes, les fijo un tamaño específico
         */
        $img_path = null;
        if(($imgPos = strpos($doc->introtext, "<img "))) {
            $urlPos = strpos($doc->introtext, "src=\"", $imgPos);
            $img_path = substr($doc->introtext,$urlPos+4, strpos($doc->introtext, '"', $urlPos+5));

            $strPre = substr($doc->introtext, 0, $imgPos+5);
            $strPos = substr($doc->introtext, $imgPos+5);
        }
        $item->text = $imgPos && checkRezizeImageNeeded($img_path) ? $strPre.' style="width: 280px" '.$strPos : $doc->introtext;

        $item->fulltext = $doc->fulltext;
        $item->browsernav = '';
        // Process the content preparation plugins
        JPluginHelper::importPlugin('content');
        $dispatcher =& JDispatcher::getInstance();
        $dispatcher->trigger('onPrepareContent', array (& $item, null, 0));
        if($key < $cantArticles)
            $articles[] = $item;
        else
            $moreArticles[] = $item;
    }

}

require(JModuleHelper::getLayoutPath('mod_lavozdelvecino'));

function checkRezizeImageNeeded($img_path) {
    if(!$img_path)
        return false;
    $max_width = 260;
    $max_height = 200;

    list($width, $height) = getimagesize($img_path);
    return (($max_height<$height) || ($max_width < $width));
}