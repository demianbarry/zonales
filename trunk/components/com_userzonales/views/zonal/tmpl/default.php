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
<p><?php echo JText::_('select_description'); ?></p>
<form>
  <select class="cmb" id="select" name="select">
    <?php foreach ($zonales as $zonal): ?>
    <option><?php echo $zonal; ?></option>  
    <?php endforeach; ?>
  </select>
  <input type="image" src="templates/<?php echo $this->template; ?>/images/bot_search_orange.gif" class="submit" value="Submit" id="button" name="button"/>
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
