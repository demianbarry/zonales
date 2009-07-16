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

<!-- form -->
<div class="moduletable_formVecinos">
	<h1><?php echo $module->title; ?></h1>
	<div style="margin-left:10px; margin-right:10px;">
		<p>Zonales nos da la posibilidad de hacer pública una noticia de su barrio o localidad.</p>
		<p><strong>Soy corresponsal</strong> nos permite ser vecinos y periodístas. Vea las publicaciones en <strong>La voz del vecino</strong>.</p>
		<div class="splitter"></div>

		<form action="index.php" method="post" id="formVecinos" name="formVecinos">
			<label for="nombre">Nombre y apellido <span>(no será publicado)</span></label>
			<input id="nombre" name="nombre" type="text" value="<?php if (!$user->guest) echo $user->name; ?>"/>

			<?php if ($showEmail): ?>
			<label for="email">E-Mail <span>(no será publicado)</span></label>
			<input id="email" name="email" type="text" value="<?php if (!$user->guest) echo $user->email; ?>" />
			<?php endif; ?>

			<?php if ($showPhone): ?>
			<label for="telefono">Teléfono <span>(no será publicado)</span></label>
			<input id="telefono" name="telefono" type="text" />
			<?php endif; ?>

			<label for="partidos">Partido</label>
			<?php echo $lists['partido_select']; ?>
			<label for="localidad">Ciudad</label>
			<?php echo $lists['localidad_select']; ?>

			<div class="splitter"></div>

			<label for="title">Título</label>
			<input id="title" name="title" type="text" />

			<label for="text">Texto</label>
			<?php echo $editor->display( 'text', '', '100%', '250', '60', '20', false, $editorParams ); ?>

			<input id="enviar" name="enviar" src="templates/<?php echo $template; ?>/images/bot_sent.gif" type="image">

			<input type="hidden" name="task" value="saveCorresponsalContent" />
			<input type="hidden" name="option" value="com_zonales" />
			<input type="hidden" name="module" value="<?php echo $module->title; ?>"
			<?php echo JHTML::_('form.token'); ?>
		</form>
	</div>
</div><!-- end #moduletable_formVecinos -->
<!-- form -->