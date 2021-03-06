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

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<div id="zonalactual_zName">
	<?php if ($showLabel): ?>
	<p><?php echo $labelText; ?></p>
	<?php endif; ?>
	<img alt="<?php echo $zonalName; ?>" class="zonalactual" title="<?php echo $zonalName; ?>"
	     src="templates/<?php echo $template; ?>/images/<?php echo $mainColor; ?>/logozonal.gif" />
        <div class="zonalactual_div">
            <p><?php echo $zonalName; ?></p>
        </div>
</div>
