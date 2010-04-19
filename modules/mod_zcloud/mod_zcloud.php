<?php
/**
 * Derived from JCloud
 * @package    JCloud
 * @subpackage Modules
 * @version    1.0.1
 * @copyright  2009 Jeff Channell
 * mod_jcloud.php
 * @license    GNU/GPL
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

// SORT/FILTER FUNCTIONS
// php.net
function randomSort( $a, $b ) { return rand( -1, 1 ); }
// filter out all the singles
function filterOnes( $var ) { return $var == 1 ? false : true; }

// vars

$width = (int)$params->get( 'zcloud_width' );
$amount = (int)$params->get( 'zcloud_maxtags' );
$maxsize = (float)$params->get( 'zcloud_maxsize' );
$minPriority = $params->get('zcloud_minpriority');

// get keywords from articles
$tags = modCloudHelper::getTags($amount,$minPriority);
if( count( $tags ) > 0 )
{
	
	// range, min & max to determine size ratio
        // obtiene el primer valor (es el mayor)
	$max = array_sum( array_slice( $tags,  0, 1 ) );
        // obtiene el ultimo valor (es el menor)
	$min = array_sum( array_slice( $tags, -1, 1 ) );
	$range = $max - $min;
	// jumble tags
	$i = 2;
	while( $i-- ) uasort( $tags, 'randomSort' );
	// get layout
	require( JModuleHelper::getLayoutPath( 'mod_zcloud' ) );
}

