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

// informa a recaptcha de no generar la interface por default
var RecaptchaOptions = {
	theme: 'custom',
	lang: 'es',
	custom_theme_widget: 'recaptcha_widget'
};

window.addEvent('domready', function() {
	// reconvierte el textarea usado por tinyMCE. neceasario para enviar
	// el formulario por medio de Ajax.Form
	var fixTiny = function(properties) {
		var properties = properties || new Object();
		var instance = properties.instance || 'mce_editor_0';
		tinyMCE.execInstanceCommand(instance,'mceCleanup');
		tinyMCE.triggerSave(true,true);
		return true;
	}

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
			}
		}).request();
	});

	$('formVecinos').addEvent('submit', function(e) {
		new Event(e).stop();

		fixTiny({instace:'text'});

		if (validate(this, validateForm) == false) {
			return false;
		}

		$('mensaje').empty().addClass('ajax-loading').setStyle('display', ''),
		this.send({
			onSuccess: function(response) {
				var resp = Json.evaluate(response);
				if (resp.result == 'captcha-failure') {
					$('captchaStatus').setStyle('display','');
					Recaptcha.reload();
				}
				if (resp.result == 'success') {
					$('formVecinos').setStyle('display','none');
				}
				$('mensaje').removeClass('ajax-loading').setHTML(resp.msg);
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
		<p><?php echo JTEXT::_('INTRO');?></p>
		<p><strong><?php echo $module->title; ?></strong> <?php echo JTEXT::_('INTRO2');?> <strong><?php echo JTEXT::_('VIEW_IN');?></strong>.</p>
		<div class="splitter"></div>

		<form action="index.php" method="post" id="formVecinos" name="formVecinos" class="form-validate" >
			<div id="nota">
				<label for="partidos"><?php echo JTEXT::_('COUNTY');?></label>
				<?php echo $lists['partido_select']; ?>

                                <label id="loc_label" for="localidad" style="display:none;"><?php echo JTEXT::_('CITY');?></label>
                                <div id="localidad_container" style="display:none;">
                                    <?php echo $lists['localidad_select']; ?>
                                </div>

				<div class="splitter"></div>

				<label for="title"><?php echo JTEXT::_('TITLE');?></label>
				<input id="title" name="title" type="text" class="required" value="" />

				<label for="text"><?php echo JTEXT::_('TEXT');?></label>
				<?php echo $editor->display( 'text', null, '100%', '250', '60', '20', false, $editorParams ); ?>

				<a id="siguiente" name="siguiente"><?php echo JTEXT::_('NEXT');?></a>
			</div>

			<div id="corresponsal" style="display: none;">
				<label for="nombre"><?php echo JTEXT::_('NAME');?><span>(<?php echo JTEXT::_('NO_PUBLIC');?>)</span></label>
				<input id="nombre" name="nombre" type="text" class="" value="<?php if (!$user->guest) echo $user->name; ?>"/>

				<?php if ($showEmail): ?>
				<label for="email"><?php echo JTEXT::_('MAIL');?><span>(<?php echo JTEXT::_('NO_PUBLIC');?>)</span></label>
				<input id="email" name="email" type="text" class="" value="<?php if (!$user->guest) echo $user->email; ?>" />
				<?php endif; ?>

				<?php if ($showPhone): ?>
				<label for="telefono"><?php echo JTEXT::_('PHONE');?><span>(<?php echo JTEXT::_('NO_PUBLIC');?>)</span></label>
				<input id="telefono" name="telefono" type="text" class="" />
				<?php endif; ?>

				<div class="splitter"></div>
				<div id="captchaStatus" style="display: none; color:red;"><?php echo JTEXT::_('INCORRECT');?></div>
				<div id="recaptcha_widget" style="display:none">
					<div id="recaptcha_image"></div>
					<br/>
					<span class="recaptcha_only_if_image"><?php echo JTEXT::_('WORDS_CAPTCHA');?></span>
					<span class="recaptcha_only_if_audio"><?php echo JTEXT::_('NOMBERS_CAPTCHA');?></span>
					<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />

					<div><a href="javascript:Recaptcha.reload()"><?php echo $captchaTextNew;?></a></div>
					<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')"><?php echo $captchaTextSnd;?></a></div>
					<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')"><?php echo $captchaTextImg;?></a></div>
					<div><a href="javascript:Recaptcha.showhelp()"><?php echo $captchaTextHelp;?></a></div>
					<noscript>
						<iframe src="http://api.recaptcha.net/noscript?k=<?php echo $captcha_publickey; ?>" height="300" width="500" frameborder="0"></iframe><br>
						<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
						<input type="hidden" name="recaptcha_response_field" value="manual_challenge">
					</noscript>
				</div>
				<script language="javascript" type="text/javascript" src="http://api.recaptcha.net/challenge?k=<?php echo $captcha_publickey; ?>"></script>
				<div class="splitter"></div>

				<input id="enviar" name="enviar" src="templates/<?php echo $template; ?>/images/<?php echo $mainColor; ?>/bot_sent.gif" type="image" />
			</div>

			<input type="hidden" name="task" value="saveCorresponsalContent" />
			<input type="hidden" name="option" value="com_zonales" />
			<input type="hidden" name="format" value="raw" />
			<input type="hidden" name="module" value="<?php echo $module->title; ?>" />
			<?php echo JHTML::_('form.token'); ?>
		</form>

		<div id="mensaje" title="mensaje" />
	</div>
</div><!-- end #moduletable_formVecinos -->
<!-- form -->