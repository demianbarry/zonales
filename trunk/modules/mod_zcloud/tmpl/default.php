<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="zcloud_container" style="position: relative; overflow: hidden; width: <?php echo $width; ?>px;">
	<div class="jcloud_tags" style="text-align: center; line-height: 1.6em;">
		<?php foreach( $tags as $tag => $count ): ?>
		<?php $size = ( ( $maxsize * 0.65 ) * ( $count - $min ) / $max ) + ( $maxsize * 0.35 ); ?>
		<span class="zcloud_tag">
			<?php modCloudHelper::showHtmlTag($tag,$size) ?>
		</span>
		<span style="font-size:1px;">&nbsp;</span>
		<?php endforeach; ?>
	</div>
</div>
