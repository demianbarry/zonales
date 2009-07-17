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

<!-- Validacion -->
<script language="javascript" type="text/javascript">
<!--
function submitform() {
    var form = document.formVecinos;

    if (document.formvalidator.isValid(form) == false) {
        return validateForm(form);
    }

    form.submit();
    return true;
}

function validateForm(form) {
    if (form.nombre.hasClass('invalid')) {
        alert("<?php echo JText::_( 'SC_NAME_WARNING', true ); ?>");
    }
    else if (form.email.hasClass('invalid')) {
        alert("<?php echo JText::_( 'SC_EMAIL_WARNING', true ); ?>");
    }
    else if (form.telefono.hasClass('invalid')) {
        alert("<?php echo JText::_( 'SC_PHONE_WARNING', true ); ?>");
    }
    else if (form.partidos.hasClass('invalid')) {
        alert("<?php echo JText::_( 'SC_PARTIDO_WARNING', true ); ?>");
    }
    else if (form.localidad.hasClass('invalid')) {
        alert("<?php echo JText::_( 'SC_LOCALIDAD_WARNING', true ); ?>");
    }
    else if (form.title.hasClass('invalid')) {
        alert("<?php echo JText::_( 'SC_TITLE_WARNING', true ); ?>");
    }
    return false;
}
//-->
</script>

<!-- form -->
<div class="moduletable_formVecinos">
	<h1><?php echo $module->title; ?></h1>
	<div style="margin-left:10px; margin-right:10px;">
		<p>Zonales nos da la posibilidad de hacer pública una noticia de su barrio o localidad.</p>
		<p><strong>Soy corresponsal</strong> nos permite ser vecinos y periodístas. Vea las publicaciones en <strong>La voz del vecino</strong>.</p>
		<div class="splitter"></div>

		<form action="index.php" method="post" id="formVecinos" name="formVecinos" class="form-validate" onsubmit="return submitform()">
			<label for="nombre">Nombre y apellido <span>(no será publicado)</span></label>
			<input id="nombre" name="nombre" type="text" class="required" value="<?php if (!$user->guest) echo $user->name; ?>"/>

			<?php if ($showEmail): ?>
			<label for="email">E-Mail <span>(no será publicado)</span></label>
			<input id="email" name="email" type="text" class="required validate-email" value="<?php if (!$user->guest) echo $user->email; ?>" />
			<?php endif; ?>

			<?php if ($showPhone): ?>
			<label for="telefono">Teléfono <span>(no será publicado)</span></label>
			<input id="telefono" name="telefono" type="text" class="required" />
			<?php endif; ?>

			<label for="partidos">Partido</label>
			<?php echo $lists['partido_select']; ?>
			<label for="localidad">Ciudad</label>
			<?php echo $lists['localidad_select']; ?>

			<div class="splitter"></div>

			<label for="title">Título</label>
			<input id="title" name="title" type="text" class="required" value="<?php echo $title; ?>"/>

			<label for="text">Texto</label>
			<?php echo $editor->display( 'text', $text, '100%', '250', '60', '20', false, $editorParams ); ?>

			<input id="enviar" name="submit" src="templates/<?php echo $template; ?>/images/bot_sent.gif" type="image" />

			<input type="hidden" name="task" value="saveCorresponsalContent" />
			<input type="hidden" name="option" value="com_zonales" />
			<input type="hidden" name="module" value="<?php echo $module->title; ?>"
			<?php echo JHTML::_('form.token'); ?>
		</form>
	</div>
</div><!-- end #moduletable_formVecinos -->
<!-- form -->