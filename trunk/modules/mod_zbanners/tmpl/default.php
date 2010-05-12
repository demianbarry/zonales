<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="bannergroup<?php echo $params->get( 'moduleclass_sfx' ) ?>">

<?php if ($headerText) : ?>
	<div class="bannerheader"><?php echo $headerText ?></div>
<?php endif;

$totalElements = count($list);
for ($i = $totalElements - 1; $i >= 0; $i--):
        $item = $list[$i];
	?><div class="banneritem<?php echo $params->get( 'moduleclass_sfx' ) ?>"><?php
	echo modZbannersHelper::renderBanner($params, $item);
	?><div class="clr"></div>
	</div>
<?php endfor; ?>

<?php if ($footerText) : ?>
	<div class="bannerfooter<?php echo $params->get( 'moduleclass_sfx' ) ?>">
		 <?php echo $footerText ?>
	</div>
<?php endif; ?>
</div>