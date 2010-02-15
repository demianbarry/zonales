<?php
/**
 * Default layout for list view
 *
 * @version		$Id: default.php 1321 2009-08-03 20:48:26Z mostafa.muhammad $
 * @package		Joomla
 * @subpackage	Workflows
 * @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
 * @license		GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
?>


<?php   $return = "";
foreach ($this->msg as $m) {
    $return.="<p>$m</p>";
}
$return.="<input class='back-button' type='button' value='<< Back' onclick='javascript:history.back()'/>";
echo $return;
?>

