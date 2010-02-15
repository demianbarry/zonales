<?php
/**
* JWF Uninstall script
*
* @version		$Id: uninstall.jwf.php 1440 2009-08-16 14:06:09Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.install
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

ignore_user_abort( true );

/**
 * Uninstalls JWF core system plugin, Both under J! 1.5 and J! 1.6
 * 
 * @access	public
 */
function com_uninstall() {

	$db =& JFactory::getDBO();
	/*					uninstall plugin 					*/
	JFile::delete(JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jwf.cron.php');
	JFile::delete(JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jwf.php');
	JFile::delete(JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jwf.xml');

	list($major,$minor,$version) = explode('.',JVERSION);
	if( $major == 1 && $minor == 5 ){
		$query = 'DELETE FROM `#__plugins` WHERE `element`="jwf"';
		$db->setQuery($query);
		$db->query();
	}
	if( $major == 1 && $minor == 6 ){
		$query = 'DELETE FROM `#__extensions` WHERE `type`="plugin" AND `element`="jwf"';
		$db->setQuery($query);
		$db->query();
	}
	/*					Done uninstalling plugin					*/
}