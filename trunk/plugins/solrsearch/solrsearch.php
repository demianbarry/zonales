<?php
/**
 * @version	$Id$
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
defined( '_JEXEC' ) or die( 'Restricted access' );

// librería php solr
jimport('SolrPhpClient.Apache.Solr.Service');

// Agrega el helper de zonales
require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');

// import library dependencies
jimport('joomla.event.plugin');

class plgSearchSolrsearch extends JPlugin
{
	var $_zhelper = null;

	function plgSearchSolrsearch( &$subject)
	{
		parent::__construct($subject);

		// carga los parametros del plugin
		$this->_plugin =& JPluginHelper::getPlugin('search', 'solrsearch');
		$this->_params = new JParameter($this->_plugin->params);

		$this->_zhelper = new comZonalesHelper();
	}

	function onSearchAreas()
	{
		static $areas = array(
			'content' => 'Articles'
		);
		return $areas;
	}

	function onSearch($text, $phrase='', $ordering='', $areas=null)
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_search'.DS.'helpers'.DS.'search.php');

		$searchText = $text;
		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onSearchAreas()))) {
				return array();
			}
		}

		$text = trim( $text );
		if ($text == '') {
			return array();
		}

		// servicio solr
		$solr = new Apache_Solr_Service( 'localhost', '8983', '/solr' );

		if (!$solr->ping()) {
			echo 'Solr service not responding.';
			exit;
		}

		$zonal = $this->_zhelper->getZonal();

		$user =& JFactory::getUser();

		// conexión a la base de datos
		$db =& JFactory::getDBO();
                $nullDate = $db->getNullDate();
		$date =& JFactory::getDate();
		$now = $date->toMySQL();

		// load plugin params info
		$plugin	=& JPluginHelper::getPlugin('search', 'solrsearch');
		$pluginParams = new JParameter( $plugin->params );

		$sContent = $pluginParams->get( 'search_content', 1 );
		$sUncategorised = $pluginParams->get( 'search_uncategorised', 1 );
		$sArchived = $pluginParams->get( 'search_archived', 1 );
		$limit = $pluginParams->def( 'search_limit', 50 );

		// parametros
		$params = array();
		$params['fl'] = '*';
		$params['qt'] = 'dismax';

		switch ($ordering) {
			case 'oldest':
				$params['sort'] = 'score desc,creationDate asc';
				break;

			case 'popular':
				$params['sort'] = 'score desc,hits desc';
				break;

			case 'alpha':
				$params['sort'] = 'score desc,title_s asc';
				break;

			case 'category':
				$params['sort'] = 'score desc,category_s asc,title_s asc';
				break;

			case 'newest':
				default:
				$params['sort'] = 'score desc,creationDate desc';
				break;
		}

		if ( $limit > 0 ) {
			$offset = 0;

			if ( $sContent ) {
				$params['fq'] = '+published:true '.
						'+section_published:true '.
						'+category_published:true '.
						'+a_access:[* TO '. (int) $user->get('aid') .'] '.
						'+section_access:[* TO '. (int) $user->get('aid') .'] '.
						'+category_access:[* TO '. (int) $user->get('aid') .'] '.
						'+(hasPublishUpDate:false OR publishUpDate:[* TO NOW]) '.
						'+(hasPublishDownDate:false OR publishDownDate:[NOW TO *]) '.
						'+tags_names:'.$zonal->name;
				$params['bq'] = 'tags_values:actualidad^40 tags_values:deportes^20';
				$results[] = $solr->search($text, $offset, $limit, $params);
			} 
			if ( $sUncategorised ) {
				$params['fq'] = '+published:true '.
						'+a_access:[* TO '. (int) $user->get('aid') .'] '.
						'+section_id: 0 '.
						'+category_id: 0 '.
						'+(hasPublishUpDate:false OR publishUpDate:[* TO NOW]) '.
						'+(hasPublishDownDate:false OR publishDownDate:[NOW TO *]) '.
						'+tags_names:'.$zonal->name;
				$params['bq'] = 'tags_values:actualidad^40 tags_values:deportes^20';
				$results[] = $solr->search($text, $offset, $limit, $params);
			} 
			if ( $sArchived ) {
				$params['fq'] = '+state:false '.
						'+section_published:true '.
						'+category_published:true '.
						'+a_access:[* TO '. (int) $user->get('aid') .'] '.
						'+section_access:[* TO '. (int) $user->get('aid') .'] '.
						'+category_access:[* TO '. (int) $user->get('aid') .'] '.
						'+(hasPublishUpDate:false OR publishUpDate:[* TO NOW]) '.
						'+(hasPublishDownDate:false OR publishDownDate:[NOW TO *]) '.
						'+tags_names:'.$zonal->name;
				$params['bq'] = 'tags_values:actualidad^40 tags_values:deportes^20';
				$results[] = $solr->search($text, $offset, $limit, $params);
			}
		}		 

		$return = array();

		foreach ( $results as $response ) {
			if ( $response->getHttpStatus() == 200 ) {
				if ( $response->response->numFound > 0 ) {
					$limit -= count($response->response->docs);
					foreach ( $response->response->docs as $doc ) {
						$item = new stdClass();
						$item->href = ContentHelperRoute::getArticleRoute($doc->id, $doc->section_id, $doc->category_id);
						$item->title = $doc->title;

						if (isset($doc->section) && isset($doc->category)) {
							$item->section = $doc->section . '/' . $doc->category;
						} else {
							$item->section = JText::_('Uncategorised Content');
						}

						$item->created = $doc->creationDate;

						$item->text = $doc->intro_content;
						$item->browsernav = '';
						$return[] = $item;
					}
				}
			}
			else {
				echo $response->getHttpStatusMessage();
			}
		}

		return $return;
	}
}
