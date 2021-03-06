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
JHtml::_('behavior.modal', 'a.modal');

$user =& JFactory::getUser();

$createArticleRoute = JRoute::_('index.php?option=com_content&task=article.add&tmpl=component_only', false);
$viewPublishedArticles = JRoute::_('index.php?view=myarchive&stateFrom=1&stateTo=1&option=com_content', false);
$viewUnpublishedArticles = JRoute::_('index.php?view=myarchive&stateFrom=0&stateTo=0&option=com_content', false);
$viewUnpublishedArticles = JRoute::_('index.php?view=myarchive&stateFrom=0&stateTo=0&option=com_content', false);
$viewDenouncedArticles = JRoute::_('index.php?view=myarchive&getDenounced=1&option=com_content', false);

require(JModuleHelper::getLayoutPath('mod_menueditor'));