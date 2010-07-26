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
    <div class="moduletable_lavozdelvecino_bodyDiv">
        <?php if(!$articles) :?>
        <p>
            <?php echo JText::_('NO_ARTICLES_MESSAGE'); ?>
        </p>
        <?php else :?>
            <?php foreach($articles as $article) : ?>
        <div>
            <table class="contentpaneopen">
                <tbody>
                    <tr>
                        <td width="100%" class="contentheading">
                            <a class="contentpagetitle" href="<?php echo $article->href?>" >
                                        <?php echo $article->title ?>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="contentpaneopen">
                <tbody>
                    <tr>
                        <td valign="top" class="createdate" colspan="2">
                                    <?php echo JHTML::_('date', $article->created, JText::_('DATE_FORMAT_LC2')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" colspan="2">
                            <p>
                                        <?php echo $article->text ?>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <span class="article_separator">&nbsp;</span>

        </div>
            <?php endforeach; ?>


            <?php if($moreArticles) :?>
        <div class="morearticles_lavozdelvecino">
            <strong>Más artículos...</strong>
        </div>
        <ul>
                    <?php foreach ($moreArticles as $doc):?>
            <li>
                <a href="<?php echo $doc->href ?>" class="blogsection">
                                <?php echo $doc->title;?>
                </a>
            </li>
                    <?php endforeach; ?>
        </ul>
            <?php endif; ?>
        <?php endif;?>


    </div>
</div>
<!-- end #moduletable_lavozdelvecino -->
<!-- form -->