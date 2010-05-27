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
JHTML::_('behavior.formvalidation');
?>

<!-- form -->
<div class="moduletable_lavozdelvecino">
    <h1 id="title_lavozdelvecino"><?php echo $module->title; ?></h1>
    <div id="mod_lavozdelvecino">
        <?php foreach ($return as $doc):?>
        <p>
            <?php echo $doc->text;?>
        </p>
        <?php endforeach; ?>
    </div>    
</div><!-- end #moduletable_lavozdelvecino -->
<!-- form -->