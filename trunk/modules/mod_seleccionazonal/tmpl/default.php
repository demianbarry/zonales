<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<div id="seleccionaZ">
<p>
<?php if($showHeader): ?>
  <?php if($useCustomHeader): ?>
    <?php echo $customHeaderText; ?>
  <?php else: ?>
    <?php echo JText::_('SELECCIONA_ZONAL'); ?>
  <?php endif; ?>
<?php endif; ?>
</p>
<form action="index.php" method="post" name="setZonalModForm" id="setZonalModForm">
  <select class="cmb" id="selectZonal" name="selectZonal" <?php if (!$useSubmitButton) {echo 'OnChange="document.setZonalModForm.submit()"';} ?> >
    <?php foreach ($zonales as $zonal): ?>
    <option value="<?php echo $zonal->id; ?>" <?php echo ($zonal_id == $zonal->id ? 'selected="selected"' : '') ?>><?php echo $zonal->label; ?></option>  
    <?php endforeach; ?>
  </select>
  <?php if (!$useSubmitButton) echo "<noscript>"; ?>
  <input type="image" src="templates/<?php echo $template; ?>/images/bot_search_orange.gif" class="submit" name="button" title="title_here" />
  <?php if (!$useSubmitButton) echo "</noscript>"; ?>
  <input type="hidden" name="task" value="setZonal" />
  <input type="hidden" name="option" value="com_zonales" />
  <input type="hidden" name="return" value="<?php echo $query; ?>" />
  <?php echo JHTML::_('form.token'); ?>
</form>
</div><!-- end #seleccionaZ -->

