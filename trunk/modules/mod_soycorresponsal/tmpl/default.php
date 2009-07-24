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
function validate(form, fn) {
    if (document.formvalidator.isValid(form) == false) {
        return fn(form);
    }
    return true;
}

function validateNota(form) {
    if (form.partidos.hasClass('invalid')) {
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

function validateForm(form) {
    if (form.partidos.hasClass('invalid')) {
        alert("<?php echo JText::_( 'SC_PARTIDO_WARNING', true ); ?>");
    }
    else if (form.localidad.hasClass('invalid')) {
        alert("<?php echo JText::_( 'SC_LOCALIDAD_WARNING', true ); ?>");
    }
    else if (form.title.hasClass('invalid')) {
        alert("<?php echo JText::_( 'SC_TITLE_WARNING', true ); ?>");
    }
    else if (form.nombre.hasClass('invalid')) {
        alert("<?php echo JText::_( 'SC_NAME_WARNING', true ); ?>");
    }
    else if (form.email.hasClass('invalid')) {
        alert("<?php echo JText::_( 'SC_EMAIL_WARNING', true ); ?>");
    }
    else if (form.telefono.hasClass('invalid')) {
        alert("<?php echo JText::_( 'SC_PHONE_WARNING', true ); ?>");
    }
    return false;
}

window.addEvent('domready', function() {
	$('siguiente').addEvent('click', function() {
		if (validate($('formVecinos'), validateNota)) {
			$('nota').setStyle('display', 'none');
			$('nombre').addClass('required');
			$('email').addClass('required validate-email');
			$('telefono').addClass('required');
			$('corresponsal').setStyle('display', '');
			return true;
		}
		return false;
	});

	$("partidos").addEvent("change", function() {
		$("localidad_container").empty().addClass("ajax-loading");
		var url="index.php?option=com_zonales&format=raw&task=getFieldValuesAjax&fieldId="+this.getValue();
		new Ajax(url, {
			method: 'get',
			onComplete: function(response) {
				$("localidad_container").removeClass("ajax-loading").setHTML(response);
				fx.set("#fff").start("#f60").chain(function() {
					this.start.delay(2000, this, "#000");
				});
			}
		}).request();
	});

	$('formVecinos').addEvent('submit', function(e) {
		new Event(e).stop();

		if (validate(this, validateForm) == false) {
			return false;
		}

		$('mensaje').empty().addClass('ajax-loading').setStyle('display', ''),
		this.send({
			update: $('mensaje'),
			onComplete: function() {
				$('formVecinos').setStyle('display', 'none');
				$('mensaje').removeClass('ajax-loading');
			}
		});
	});
});
//-->
</script>

<!-- form -->
<div class="moduletable_formVecinos">
	<h1><?php echo $module->title; ?></h1>
	<div style="margin-left:10px; margin-right:10px;">
		<p>Zonales nos da la posibilidad de hacer pública una noticia de su barrio o localidad.</p>
		<p><strong>Soy corresponsal</strong> nos permite ser vecinos y periodístas. Vea las publicaciones en <strong>La voz del vecino</strong>.</p>
		<div class="splitter"></div>

		<form action="index.php" method="post" id="formVecinos" name="formVecinos" class="form-validate" >
			<div id="nota">
				<label for="partidos">Partido</label>
				<?php echo $lists['partido_select']; ?>
				<label for="localidad">Localidad</label>
				<div id="localidad_container"><?php echo $lists['localidad_select']; ?></div>

				<div class="splitter"></div>

				<label for="title">Título</label>
				<input id="title" name="title" type="text" class="required" value="<?php echo $title; ?>"/>

				<label for="text">Texto</label>
				<?php echo $editor->display( 'text', $text, '100%', '250', '60', '20', false, $editorParams ); ?>

				<a id="siguiente" name="siguiente" />Siguiente</a>
			</div>

			<div id="corresponsal" style="display: none;">
				<label for="nombre">Nombre y apellido <span>(no será publicado)</span></label>
				<input id="nombre" name="nombre" type="text" class="" value="<?php if (!$user->guest) echo $user->name; ?>"/>

				<?php if ($showEmail): ?>
				<label for="email">E-Mail <span>(no será publicado)</span></label>
				<input id="email" name="email" type="text" class="" value="<?php if (!$user->guest) echo $user->email; ?>" />
				<?php endif; ?>

				<?php if ($showPhone): ?>
				<label for="telefono">Teléfono <span>(no será publicado)</span></label>
				<input id="telefono" name="telefono" type="text" class="" />
				<?php endif; ?>

				<input id="enviar" name="submit" src="templates/<?php echo $template; ?>/images/<?php echo $mainColor; ?>/bot_sent.gif" type="image" />
			</div>

			<input type="hidden" name="task" value="saveCorresponsalContent" />
			<input type="hidden" name="option" value="com_zonales" />
			<input type="hidden" name="format" value="raw" />
			<input type="hidden" name="module" value="<?php echo $module->title; ?>" />
			<?php echo JHTML::_('form.token'); ?>
		</form>

		<p id="mensaje" title="mensaje" />
	</div>
</div><!-- end #moduletable_formVecinos -->
<!-- form -->