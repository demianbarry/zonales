<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<div id="seleccionaZ">
<div id="selZ-container">
<div id="selZ-l">
<img src="templates/<?php echo $template; ?>/images/title_inicio_seleccion.gif" border="0" alt="Seleccioná tu Zonal" />
<div class="padd5">
<p><?php echo JText::_('SELECCIONA_ZONAL'); ?></p>
<form action="index.php" method="post" name="setZonalForm" id="setZonalForm">
  <select class="cmb" id="selectZonal" name="selectZonal">
    <?php foreach ($zonales as $zonal): ?>
    <option value="<?php echo $zonal->id; ?>" <?php echo ($zonal_id == $zonal->id ? 'selected="selected"' : '') ?>><?php echo $zonal->label; ?></option>  
    <?php endforeach; ?>
  </select>
  <input type="image" src="templates/<?php echo $template; ?>/images/bot_search_orange.gif" class="submit" name="button" title="title_here" />
  <input type="hidden" name="task" value="setZonal" />
  <input type="hidden" name="option" value="com_userzonales" />
  <?php echo JHTML::_('form.token'); ?>
</form>
</div><!-- end .padd5 -->
</div><!-- end #selZ-l -->
</div><!-- end #selZ-container -->
</div><!-- end #seleccionaZ -->

