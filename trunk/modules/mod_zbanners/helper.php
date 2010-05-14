<?php
/**
 * @version		$Id: helper.php 10554 2008-07-15 17:15:19Z ircmaxell $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_banners'.DS.'helpers'.DS.'banner.php');
require_once JPATH_ROOT . DS . 'components' . DS . 'com_eqzonales' . DS . 'hlapi.php';

class modZbannersHelper {
    const MAXTOTAL = 100;

    function getBanners($userid,$bannersList) {
        $db =& JFactory::getDBO();
        $session = JFactory::getSession();

        $zonal_actual = $session->get('zonales_zonal_name', null);

        $tags = HighLevelApi::getAncestors($userid, $zonal_actual);

        // suma uno a todos los elementos del array
        // para que hasta los que no tengan valor se tengan en cuenta
        foreach ($tags as $key => $t) {
            $tags[$key] = $t + 1;
        }

        $max = max($tags);
        $random = rand(0, $max);
        $pieces = array_keys($tags);

        $query = 'select cp.content_id as banner_id, cpv.name as tag from jos_custom_properties cp, jos_custom_properties_fields cpf, jos_custom_properties_values cpv where cp.field_id=cpf.id and cp.value_id=cpv.id and cp.ref_table="banner" and cpf.name="root_zonales" and cpv.name in (\''. implode("','", $pieces) . '\')';
        $db->setQuery($query);
        $dbBanners = $db->loadObjectList();

        // mapeo los banners obtenidos con la relevancia correspondiente
        $bannersW = array();
        foreach ($dbBanners as $bannerTag) {
            $currentTag = $bannerTag->tag;
            foreach ($tags as $tag => $relevance) {
                if ($tag == $currentTag) {
                    $auxB = (isset ($bannersW[$bannerTag->banner_id])) ? $bannersW[$bannerTag->banner_id] : 0;
                    $bannersW[$bannerTag->banner_id] = $auxB + $relevance;
                }
            }
        }

        // solo me interensan los banners que deben ser mostrados
        $bannersM = array();
        foreach ($bannersList as $banner) {
            foreach ($bannersW as $bannerId => $relevanceB) {
                if ($banner_id == $banner->bid) {
                    $bannersM[$banner_id] = $relevanceB;
                }
            }
        }

        // ordeno los banners por relevancia
        $bannersSorted = arsort($bannersM, SORT_NUMERIC);
        // elimino la relevancia. ya no es necesaria
        $banners = array_keys($bannersSorted);

        return $banners;
    }
    function getList(&$params) {
        $model		= modZbannersHelper::getModel();

        // Model Variables
        $vars['cid']		= (int) $params->get( 'cid' );
        $vars['catid']		= (int) $params->get( 'catid' );
        $vars['limit']		= (int) $params->get( 'count', 1 );
        $vars['ordering']	= $params->get( 'ordering' );

        $bannersList = $model->getList( $vars );

        if ($params->get( 'tag_search' )) {
            $user =& JFactory::getUser();
 //           $banners = $this->getBanners($user->id, $bannersList);



            #####################3

                    $db =& JFactory::getDBO();
        $session = JFactory::getSession();

        $zonal_actual = $session->get('zonales_zonal_name', null);

        $tags = HighLevelApi::getAncestors($user->id, $zonal_actual);

        // suma uno a todos los elementos del array
        // para que hasta los que no tengan valor se tengan en cuenta
        foreach ($tags as $key => $t) {
            $auxA = ($key == $zonal_actual) ? 1 : 0;
            $tags[$key] = $t + 1 + $auxA;
        }
        
        $pieces = array_keys($tags);

        $query = 'select cp.content_id as banner_id, cpv.name as tag from #__custom_properties cp, #__custom_properties_fields cpf, #__custom_properties_values cpv where cp.field_id=cpf.id and cp.value_id=cpv.id and cp.ref_table="banner" and cpf.name="root_zonales" and cpv.name in (\''. implode("','", $pieces) . '\')';
        $db->setQuery($query);
        $dbBanners = $db->loadObjectList();

        // mapeo los banners obtenidos con la relevancia correspondiente
        $bannersW = array();
        foreach ($dbBanners as $bannerTag) {
            $currentTag = $bannerTag->tag;
            foreach ($tags as $tag => $relevance) {
                if ($tag == $currentTag) {
                    $auxB = (isset ($bannersW[$bannerTag->banner_id])) ? $bannersW[$bannerTag->banner_id] : 0;
                    $bannersW[$bannerTag->banner_id] = $auxB + $relevance;
                }
            }
        }

        // solo me interensan los banners que deben ser mostrados
//        $bannersM = array();
//        foreach ($bannersList as $banner) {
//            foreach ($bannersW as $bannerId => $relevanceB) {
//                if ($bannerId == $banner->bid) {
//                    $bannersM[$bannerId] = $relevanceB;
//                }
//            }
//        }
        $bannersM = $bannersW;

        // ordeno los banners por relevancia
        $bannersSorted = arsort($bannersM, SORT_NUMERIC);
        
        /*
         *  ##################
         */
        
        // normalizo las relevancias
//        $total = array_sum($tags);
//        $contributions = array();
//        foreach ($tags as $name => $value) {
//            $contributions[$name] = ($value * modZbannersHelper::MAXTOTAL) / $total;
//        }
//
//
//        $random = rand(0, modZbannersHelper::MAXTOTAL);
//
//        $max = max($tags);
//
//        // obtengo las posiciones donde cambian los valores
//        $positions = array();
//        $positions[] = 0;
//        $count = count($contributions);
//        for ($j = 1; $j < $count;$j++){
//            if ($contributions[$j] != $contributions[$j - 1]){
//                $positions[] = $j;
//            }
//        }
//
//        $countP = count($positions);
//        for ($i = 1;$i < $countP;$i++){
//            $pos = $positions[$i];
//            $posPrevius = $positions[$i - 1];
//            if ($random >= $contributions[$posPrevius] && $random < $contributions[$pos]){
//                $showMe = $posPrevius;
//            }
//        }

        /*
         *  ###################
         */
        // elimino la relevancia. ya no es necesaria
        $bannersIdsRaw = array_keys($bannersM);

        $totalBanners = count($bannersIdsRaw);
        // si tengo mas banners que los solicitados
if ($totalBanners > $vars['limit']) {
    // elimino los menos relevantes que sobren
    $bannersIds = array_slice($bannersIdsRaw, 0,$vars['limit']);
}
else { // si no
    $bannersIds = $bannersIdsRaw;
    // si faltan banners
    if ($totalBanners < $vars['limit']) {
        // busco en la base de datos banners que no tengan zona asociada
        // solo la cantidad que faltan
        $totalBannersNeeded = $vars['limit'] - $totalBanners;
        $selectNonZone = "SELECT bn.bid FROM #__banner bn where bn.bid not in
                            (SELECT b.bid FROM #__custom_properties j,
                                                #__banner b,
                                                #__custom_properties_fields f
                                          where j.ref_table='banner'
                                                and j.content_id=b.bid
                                                and f.name='root_zonales'
                                                 and f.id=j.field_id
                                                ) limit $totalBannersNeeded";

        $db->setQuery($selectNonZone);
        $noZoneBanners = $db->loadObjectList();

        if ($bannersSorted) {
            foreach ($noZoneBanners as $currentBanner) {
                // los agrego al final porque no son relevantes
                $bannersIds[] = $currentBanner->bid;
            }
        }
    }
}

        if (count($bannersIds) <= 0) return;

        $case = '';
        foreach ($bannersIds as $pos => $id) {
            $case .= "when $id then $pos ";
        }

        $catId = $vars['catid'];
        $cId = $vars['cid'];

        $select = "select b.bid, b.impmade, b.imptotal, b.custombannercode, b.name,
        b.imageurl, b.clickurl, b.params, case b.bid
            $case end as orden from #__banner b  where b.bid in (" .
    //        implode(',', $bannersIds) . ") order by orden DESC";
        implode(',', $bannersIds) . ") and b.catid=$catId and b.cid=$cId and b.showBanner=1 order by orden DESC";

        $db->setQuery($select);
        $banners = $db->loadObjectList();

            ##########################

//			$document		=& JFactory::getDocument();
//			$keywords		=  $document->getMetaData( 'keywords' );
//
//
//
//			$vars['tag_search'] = array($zonal_actual);
        }
        else {
            $banners = $bannersList;
        }


        $model->impress( $banners );

        return $banners;
    }

    function getModel() {
        if (!class_exists( 'ZonalesModelZbanner' )) {
            // Build the path to the model based upon a supplied base path
            $path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_zonales'.DS.'models'.DS.'zbanner.php';
            $false = false;

            // If the model file exists include it and try to instantiate the object
            if (file_exists( $path )) {
                require_once( $path );
                if (!class_exists( 'ZonalesModelZbanner' )) {
                    JError::raiseWarning( 0, 'Model class ZonalesModelZbanner not found in file.' );
                    return $false;
                }
            } else {
                JError::raiseWarning( 0, 'Model ZonalesModelZbanner not supported. File not found.' );
                return $false;
            }
        }

        $model = new ZonalesModelZbanner();
        return $model;
    }

    function renderBanner($params, &$item) {
        $link = JRoute::_( 'index.php?option=com_banners&task=click&bid='. $item->bid );
        $baseurl = JURI::base();

        $html = '';
        if (trim($item->custombannercode)) {
            // template replacements
            $html = str_replace( '{CLICKURL}', $link, $item->custombannercode );
            $html = str_replace( '{NAME}', $item->name, $html );
        }
        else if (BannerHelper::isImage( $item->imageurl )) {
            $image 	= '<img src="'.$baseurl.'images/banners/'.$item->imageurl.'" alt="'.JText::_('Banner').'" />';
            if ($item->clickurl) {
                switch ($params->get( 'target', 1 )) {
                    // cases are slightly different
                    case 1:
                    // open in a new window
                        $a = '<a href="'. $link .'" target="_blank">';
                        break;

                    case 2:
                    // open in a popup window
                        $a = "<a href=\"javascript:void window.open('". $link ."', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\">";
                        break;

                    default:	// formerly case 2
                    // open in parent window
                        $a = '<a href="'. $link .'">';
                        break;
                }

                $html = $a . $image . '</a>';
            }
            else {
                $html = $image;
            }
        }
        else if (BannerHelper::isFlash( $item->imageurl )) {
            //echo $item->params;
            $banner_params = new JParameter( $item->params );
            $width = $banner_params->get( 'width');
            $height = $banner_params->get( 'height');

            $imageurl = $baseurl."images/banners/".$item->imageurl;
            $html =	"<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" border=\"0\" width=\"$width\" height=\"$height\">
						<param name=\"movie\" value=\"$imageurl\"><embed src=\"$imageurl\" loop=\"false\" pluginspage=\"http://www.macromedia.com/go/get/flashplayer\" type=\"application/x-shockwave-flash\" width=\"$width\" height=\"$height\"></embed>
					</object>";
        }

        return $html;
    }
}
