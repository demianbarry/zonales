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

<?php if($user->get('aid') >= 2):?>
<div class="moduletable_menueditor">
    <h1 style="display:block; padding:10px;  background-color: #009CC9; font-family:Georgia, 'Times New Roman', Times, serif; font-size:16px; color:#FFFFFF; font-weight:bold;"><?php echo $module->title; ?></h1>
    <p>
        <a class="modal-button" href="<?php echo $createArticleRoute ?>" rel="{handler: 'iframe', size: {x: 570, y: 600}}">
                <?php echo JText::_('MOD_MENUEDITOR_CREATE_ARTICLE'); ?>
        </a>
    </p>
    <p>
        <a href="<?php echo $viewPublishedArticles ?>">
                <?php echo JText::_('MOD_MENUEDITOR_VIEW_PUBLISHED_ARTICLES'); ?>
        </a>
    </p>
    <p>
        <a href="<?php echo $viewUnpublishedArticles ?>">
                <?php echo JText::_('MOD_MENUEDITOR_VIEW_UNPUBLISHED_ARTICLES'); ?>
        </a>
    </p>
    <p>
        <a href="<?php echo $viewDenouncedArticles ?>">
                <?php echo JText::_('MOD_MENUEDITOR_VIEW_DENOUNCED_ARTICLES'); ?>
        </a>
    </p>
</div>
<?php endif;?>