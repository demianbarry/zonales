<?php
/**
 * @version             $Id$
 * @package             JXtended.Comments
 * @subpackage		mod_comments_latest
 * @copyright		(C) 2008 - 2009 JXtended, LLC. All rights reserved, see COPYRIGHT.php
 * @license             GNU General Public License, see LICENSE.php
 * @link                http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

// If there are no items in the list, do not display anything.
if (empty($list)) {
        return;
}

// Attach the comments stylesheet to the document head.
JHTML::stylesheet('comments.css', 'components/com_comments/media/css/');
?>

<ol class="comments-list latest">
<?php
        foreach($list as $item) :
                $itemTitle = JText::sprintf('COMMENTS_RE', !empty($item->subject) ? $item->subject : $item->page_title);
                $itemTitle = htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8');
        ?>
        <li>
                <h4>
                        <a class="item" href="<?php echo JRoute::_($item->page_route); ?>#comment-<?php echo $item->id; ?>" title="<?php echo $itemTitle; ?>">
                                <?php echo $itemTitle; ?></a>
                </h4>
                <small>
                        <?php echo JText::sprintf('COMMENTS_COMMENT_POSTED_ON', JHtml::date($item->created_date, $params->get('date_format')), htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8')); ?>
                </small>
<?php if ($params->get('show_comment_excerpt', 1)) : ?>
                <p class="excerpt">
                        <?php echo htmlspecialchars(modCommentsLatestHelper::truncateBody($item->body, $params->get('max_excerpt_length', 50)), ENT_QUOTES, 'UTF-8'); ?>
                </p>
<?php endif; ?>
        </li>
<?php endforeach; ?>
</ol>
<?php if ($params->get('enable_comment_feeds', 1) && $params->get('show_feed_link', 1)) : ?>
<a class="comments-feed" href="<?php echo JRoute::_('index.php?option=com_comments&view=comments&format=feed'); ?>">Feed</a>
<?php endif; ?>