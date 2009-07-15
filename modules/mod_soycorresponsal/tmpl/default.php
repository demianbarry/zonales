<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<!-- form -->
<div class="moduletable_formVecinos">
	<div style="margin-left:10px; margin-right:10px;">
		<p>Zonales nos da la posibilidad de hacer pública una noticia de su barrio o localidad.</p>
		<p><strong>Soy corresponsal</strong> nos permite ser vecinos y periodístas. Vea las publicaciones en <strong>La voz del vecino</strong>.</p>
		<div class="splitter"></div>

		<form action="index.php" method="post" id="formVecinos" name="formVecinos">
			<label>Nombre y apellido <span>(no será publicado)</span></label>
			<input id="nombre" type="text" value="<?php if (!$user->guest) echo $user->name; ?>"/>

			<?php if ($showEmail): ?>
			<label>E-Mail <span>(no será publicado)</span></label>
			<input id="email" type="text" value="<?php if (!$user->guest) echo $user->email; ?>" />
			<?php endif; ?>

			<?php if ($showPhone): ?>
			<label>Teléfono <span>(no será publicado)</span></label>
			<input id="telefono" type="text" />
			<?php endif; ?>

			<label>Partido</label>
			<?php echo $lists['partido_select']; ?>
			<label>Ciudad</label>
			<?php echo $lists['localidad_select']; ?>

			<div class="splitter"></div>

			<label>Título</label>
			<input id="titulo" />

			<label>Texto</label>
			<?php echo $editor->display( 'texto', '', '100%', '250', '60', '20', false, $editorParams ); ?>

			<input name="" type="image" id="enviar" src="images/bot_sent.gif" />

			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="" />
			<input type="hidden" name="return" value="<?php echo $return; ?>" />
			<?php echo JHTML::_('form.token'); ?>
		</form>
	</div>
</div><!-- end #moduletable_formVecinos -->
<!-- form -->
