<?php
/**
* JWF Installation script
*
* @version		$Id: install.jwf.php 1440 2009-08-16 14:06:09Z mostafa.muhammad $
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

define('JWF_BACKEND_PATH' ,  JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_jwf');
define('JWF_FRONTEND_PATH',  JPATH_ROOT.DS.'components'.DS.'com_jwf');

/**
 * Installs and publishes JWF system core plugin, Both under J! 1.5 and J! 1.6
 * 
 * @access	public
 */
function com_install() {

	list($major,$minor,$version) = explode('.',JVERSION);
	if( $major == 1 && $minor == 5 ){
		$db =& JFactory::getDBO();
	
		/*					Install plugin 					*/
		if(!JFile::exists(JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jwf.php')){
			JFile::move(
					JWF_BACKEND_PATH.DS.'plugins'.DS.'system'.DS.'jwf.php',
					JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jwf.php'
			);
			JFile::move(
					JWF_BACKEND_PATH.DS.'plugins'.DS.'system'.DS.'jwf.xml',
					JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jwf.xml'
			);
			JFile::move(
					JWF_BACKEND_PATH.DS.'plugins'.DS.'system'.DS.'jwf.cron.php',
					JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jwf.cron.php'
			);
		}
		JFolder::delete(JWF_BACKEND_PATH.DS.'plugins'.DS.'system');

		$query = 'SELECT `published` FROM `#__plugins` WHERE `element`="jwf"';
		$db->setQuery($query);
		$result = $db->loadObject();

	
		if($result == null){
			$query = 
			"INSERT INTO `#__plugins` ( `name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`)"
			."\nVALUES"
			."\n('System - JWF', 'jwf', 'system', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', '')";
			$db->setQuery($query);
			$db->query();
		} else {
			$query = 'UPDATE `#__plugins` SET `published` = 1 WHERE `element`="jwf"';
			$db->setQuery($query);
			$db->query();
		}
		/*					Done installing plugin					*/
		$db->setQuery("UPDATE #__components SET admin_menu_img='../media/com_jwf/images/logo-16.png' WHERE admin_menu_link='option=com_jwf'");
		$res = $db->query();
	}
	
	if( $major == 1 && $minor == 6 ){
			$db =& JFactory::getDBO();
	
		/*					Install plugin 					*/
		if(!JFile::exists(JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jwf.php')){
			JFile::move(
					JWF_BACKEND_PATH.DS.'plugins'.DS.'system'.DS.'jwf.php',
					JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jwf.php'
			);
			JFile::move(
					JWF_BACKEND_PATH.DS.'plugins'.DS.'system'.DS.'jwf.xml',
					JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jwf.xml'
			);
			JFile::move(
					JWF_BACKEND_PATH.DS.'plugins'.DS.'system'.DS.'jwf.cron.php',
					JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jwf.cron.php'
			);
		}
		JFolder::delete(JWF_BACKEND_PATH.DS.'plugins'.DS.'system');

		$query = 'SELECT `enabled` FROM `#__extensions` WHERE `type`="plugin" AND `element`="jwf"';
		$db->setQuery($query);
		$result = $db->loadObject();

		if($result == null){
			$query = 
			"INSERT INTO `#__extensions` (`name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`, `custom_data`, `system_data`, `checked_out`, `checked_out_time`, `ordering`, `state`)"
			."\nVALUES"
			."\n( 'System - JWF', 'plugin', 'jwf', 'system', 0, 1, 1, 0, '', '', '', '', 0, '0000-00-00 00:00:00', 1, 0)";
			$db->setQuery($query);
			$db->query();
		} else {
			$query = 'UPDATE `#__extensions` SET `enabled`=1 WHERE `type`="plugin" AND `element`="jwf"';
			$db->setQuery($query);
			$db->query();
		}
		/*					Done installing plugin					*/
		$db->setQuery("UPDATE #__components SET admin_menu_img='../media/com_jwf/images/logo-16.png' WHERE admin_menu_link='option=com_jwf'");
		$res = $db->query();
	}
}