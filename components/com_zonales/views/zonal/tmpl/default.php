<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<div id="seleccionaZ">
<img src="templates/<?php echo $this->template; ?>/images/title_inicio.gif" border="0" alt="Zonalizate!"/>
<div id="selZ-container">
<div id="selZ-l">
<img src="templates/<?php echo $this->template; ?>/images/title_inicio_seleccion.gif" border="0" alt="Seleccioná tu Zonal" />
<div class="padd5">
<p><?php echo JText::_('SELECCIONA_ZONAL'); ?></p>
<form action="index.php" method="post" name="setZonalComForm" id="setZonalComForm">
  <select class="cmb" id="selectZonal" name="selectZonal">
    <?php foreach ($this->zonales as $zonal): ?>
    <option value="<?php echo $zonal->id; ?>" <?php echo ($this->zonal_id == $zonal->id ? 'selected="selected"' : '') ?>><?php echo $zonal->label; ?></option>  
    <?php endforeach; ?>
  </select>
  <input type="image" src="templates/<?php echo $this->template; ?>/images/bot_search_orange.gif" class="submit" name="button" title="title_here" />
  <input type="hidden" name="task" value="setZonal" />
  <input type="hidden" name="option" value="com_zonales" />
  <?php echo JHTML::_('form.token'); ?>
</form>
</div><!-- end .padd5 -->
</div><!-- end #selZ-l -->
<div id="selZ-r">
<img src="templates/<?php echo $this->template; ?>/images/title_inicio_visitados.gif" border="0" alt="Los más visitados" />
<div class="padd5">
<a href="/zonales-devel/">Burzaco</a>
<a href="/zonales-devel/">Adrogué</a>
<a href="/zonales-devel/">Lomas de Zamora</a>
</div><!-- end .padd5 -->  
</div><!-- end #selZ-r -->
</div><!-- end #selZ-container -->
</div><!-- end #seleccionaZ -->
