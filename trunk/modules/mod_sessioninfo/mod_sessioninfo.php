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
defined('_JEXEC') or die('Restricted access');

$user =& JFactory::getUser();
$session =& JFactory::getSession();

$profileLink = $params->get('profilelink');
$greetingMessage = sprintf(JText::_('ZONALES_SESSION_GREETING'),$user->get('name'));
$sessionCloseMessage = JText::_('ZONALES_SESSION_CLOSE');
$logoutRoute = JRoute::_('index.php?option=com_user&task=logout');
$protocol = $session->get('accessprotocol');

require(JModuleHelper::getLayoutPath('mod_sessioninfo'));