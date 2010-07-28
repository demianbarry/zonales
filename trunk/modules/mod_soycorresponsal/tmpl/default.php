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
$showColapsed = true;
?>
<script type="text/javascript">

    // informa a recaptcha de no generar la interface por default
    var RecaptchaOptions = {
        theme: 'custom',
        lang: 'es',
        custom_theme_widget: 'recaptcha_widget'
    };
</script>

<!-- form -->
<div class="moduletable_formVecinos">
    <h1 id="title_soycorresponsal"><?php echo $module->title; ?></h1>
    <div id="mod_soycorresponsal_main_div" class="moduletable_formVecinos_bodyDiv">
        <?php if($user->guest): ?>
        <div class="moduletable_formVecinos_bodyDiv">
                <?php echo JText::_('MOD_SOYCORRESPONSAL_INVITATION_PRE');?>
            <a href="index.php?option=com_user&view=zlogin"><?php echo JText::_('MOD_SOYCORRESPONSAL_GET_LOGED');?></a>
                <?php echo JText::_('MOD_SOYCORRESPONSAL_INVITATION_INTER');?>
            <a href="index.php?option=com_user&view=register&map=0"><?php echo JText::_('MOD_SOYCORRESPONSAL_GET_REGISTERED');?></a>
                <?php echo JText::_('MOD_SOYCORRESPONSAL_INVITATION_POS');?>
        </div>
        <?php else : ?>
        <p><?php echo JText::_('INTRO');?></p>
        <p><strong><?php echo $module->title; ?></strong> <?php echo JText::_('INTRO2');?> <strong><?php echo JText::_('VIEW_IN');?></strong>.</p>
        <div class="splitter"></div>

        <form action="index.php" method="post" id="formVecinos" name="formVecinos" class="form-validate" >
            <div id="nota">
                <label for="provincias_sc"><?php echo JText::_('COUNTY');?></label>
                    <?php echo $lists['provincias_select']; ?>

                <label id="loc_label" for="localidad" style="display:block;"><?php echo JText::_('CITY');?></label>
                <div id="localidad_container" style="display:block;">
                </div>

                <div class="splitter"></div>

                <label for="title"><?php echo JText::_('TITLE');?></label>
                <input id="title" name="title" type="text" class="required" value="" size="55px"/>

                <label for="text"><?php echo JText::_('TEXT');?></label>
                    <?php echo $editor->display( 'text', null, '100%', '250', '60', '20', false, $editorParams ); ?>
            </div>
            <input id="siguiente" type="submit" name="siguiente" value="<?php echo JText::_('NEXT');?>" />
            <div id="corresponsal" style="display: none;">
                <label for="nombre"><?php echo JText::_('NAME');?><span>(<?php echo JText::_('NO_PUBLIC');?>)</span></label>
                <input id="nombre" name="nombre" type="text" class="" value="<?php echo $user->name; ?>" readonly="true"/>

                    <?php if ($showEmail): ?>
                <label for="email"><?php echo JText::_('MAIL');?><span>(<?php echo JText::_('NO_PUBLIC');?>)</span></label>
                <input id="email" name="email" type="text" class="" value="<?php echo $user->email; ?>" readonly="true"/>
                    <?php endif; ?>

                    <?php if ($showPhone): ?>
        <!--<label for="telefono"><?php //echo JText::_('PHONE');?><span>(<?php //echo JText::_('NO_PUBLIC');?>)</span></label>
        <input id="telefono" name="telefono" type="text" class="" value="<?php //echo $user->telephone; ?>" disabled="true"/>-->
                    <?php endif; ?>

                <div class="splitter"></div>               
                <div id="recaptcha_widget" style="display:none">

                    <div id="recaptcha_image"></div>
                    <div class="recaptcha_only_if_incorrect_sol" style="color:red"><?php echo JText::_('INCORRECT');?></div>

                    <span class="recaptcha_only_if_image"><?php echo JText::_('WORDS_CAPTCHA');?></span>
                    <span class="recaptcha_only_if_audio"><?php echo JText::_('NOMBERS_CAPTCHA');?></span>

                    <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />

                    <div><a href="javascript:Recaptcha.reload()"><?php echo $captchaTextNew;?></a></div>
                    <div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')"><?php echo $captchaTextSnd;?></a></div>
                    <div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')"><?php echo $captchaTextImg;?></a></div>
                    <div><a href="javascript:Recaptcha.showhelp()"><?php echo $captchaTextHelp;?></a></div>
                </div>
                <script type="text/javascript"
                        src="http://www.google.com/recaptcha/api/challenge?k=<?php echo $captcha_publickey; ?>">
                </script>
                <noscript>
                    <iframe src="http://www.google.com/recaptcha/api/noscript?k=<?php echo $captcha_publickey; ?>"
                            height="300" width="500" frameborder="0"></iframe><br>
                    <textarea name="recaptcha_challenge_field" rows="3" cols="40">
                    </textarea>
                    <input type="hidden" name="recaptcha_response_field"
                           value="manual_challenge">
                </noscript>
                <div class="splitter"></div>

                <input id="enviar" type="submit" name="enviar" value="<?php echo JText::_('SEND');?>"/>
                <input id="volver" type="submit" name="volver" value="<?php echo JText::_('BACK');?>"/>
            </div>

            <input type="hidden" name="task" value="saveCorresponsalContent" />
            <input type="hidden" name="option" value="com_zonales" />
            <input type="hidden" name="format" value="raw" />
            <input type="hidden" name="module" value="<?php echo $module->title; ?>" />
                <?php echo JHTML::_('form.token'); ?>
        </form>

        <div id="mensaje" title="mensaje" />    
        <?php endif?>
    </div>
</div><!-- end #moduletable_formVecinos -->
<!-- form -->
<!-- Validacion -->
<script language="javascript" type="text/javascript">
    <!--
<?php if(!$user->guest) :?>
    function validate(form, fn) {
        if (document.formvalidator.isValid(form) == false) {
            return fn(form);
        }
        return true;
    }

    function validateNota(form) {
        if (form.provincias_sc.hasClass('invalid')) {
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
        if (form.provincias_sc.hasClass('invalid')) {
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
        /*else if (form.telefono.hasClass('invalid')) {
            alert("<?php //echo JText::_( 'SC_PHONE_WARNING', true ); ?>");
        }*/
        return false;
    }


<?php endif?>
    window.addEvent('domready', function() {
<?php if(!$user->guest) :?>
        // reconvierte el textarea usado por tinyMCE. neceasario para enviar
        // el formulario por medio de Ajax.Form
        var fixTiny = function(properties) {
            var properties = properties || new Object();
            var instance = properties.instance || 'mce_editor_0';
            tinyMCE.execInstanceCommand(instance,'mceCleanup');
            tinyMCE.triggerSave(true,true);
            return true;
        }

        $('siguiente').addEvent('click', function(e) {
            new Event(e).stop();
            if($('nota').getStyle('display') != 'none') {
                if (validate($('formVecinos'), validateNota)) {
                    $('nota').setStyle('display', 'none');
                    $('siguiente').setStyle('display','none');
                    $('nombre').addClass('required');
                    $('email').addClass('required validate-email');
                    //$('telefono').addClass('required');
                    $('corresponsal').setStyle('display', '');
                    return true;
                }
                return false;
            } else {
                window.location.reload();
            }
        });

        $('volver').addEvent('click', function(e) {
            new Event(e).stop();
            $('nota').setStyle('display', 'block');
            $('siguiente').setStyle('display','block');
            $('corresponsal').setStyle('display', 'none');
            $('nombre').removeClass('required');
            $('email').removeClass('required validate-email');
            //$('telefono').removeClass('required');
        });

        // al cambiar el partido, recupero las localidades
        $('provincias_sc').addEvent('change', function() {
            loadLocalidades(this.value);
        });

        $('formVecinos').addEvent('submit', function(e) {
            new Event(e).stop();

            if(typeof(tinyMCE) != "undefined") {
                fixTiny({instace:'text'});
            }

            if (validate(this, validateForm) == false) {
                return false;
            }
            $('corresponsal').setStyle('display','none');
            $('mensaje').empty().addClass('ajax-loading').setStyle('display', ''),
            this.send({
                onSuccess: function(response) {
                    var resp = Json.evaluate(response);
                    if (resp.result == 'captcha-failure') {
                        //$('captchaStatus').setStyle('display','');
                        Recaptcha.reload();
                        $('corresponsal').setStyle('display','block');
                    }
                    if (resp.result == 'success') {
                        $('siguiente').setStyle('display', 'block');
                    }
                    $('mensaje').removeClass('ajax-loading').setHTML(resp.msg);
                }
            });
        });
        // cargo las localidades de acuerdo al partido que esté seleccionado al cargar la página
        loadLocalidades(<?php echo $selectedOption?>);
<?php endif?>
        // creo el Slider que muestra y oculta el formulario de Soy Corresponsal
        //var mySoyCorresponsalSlider = new Fx.Slide('mod_soycorresponsal_main_div', {fps: 20});
        // lo oculto por defecto
        //mySoyCorresponsalSlider.hide();

<?php if($showColapsed): ?>
        $('mod_soycorresponsal_main_div').setStyle('display','none');
        $('title_soycorresponsal').addClass('show');

        // clickeando en el título muestro u oculto el formulario
        $('title_soycorresponsal').addEvent('click', function(e) {
            if($('mod_soycorresponsal_main_div').getStyle('display') == 'none') {
                $('mod_soycorresponsal_main_div').setStyle('display','block');
                $('title_soycorresponsal').removeClass('show').addClass('hide');
            } else  {
                $('mod_soycorresponsal_main_div').setStyle('display','none');
                $('title_soycorresponsal').removeClass('hide').addClass('show');
            }
        });
<?php endif;?>
    });

<?php if(!$user->guest) :?>
    /**
     * Recupera mediante una llamada Ajax la lista de localidades, de acuerdo al partido seleccionado.
     */
    function loadLocalidades(selected) {
        $('localidad_container').empty().addClass('ajax-loading');
        var url='index.php?option=com_zonales&format=raw&task=getItemsAjax&id='+$('provincias_sc').value+'&name=localidad&selected='+selected;
        new Ajax(url, {
            method: 'get',
            onComplete: function(response) {
                $('localidad_container').removeClass('ajax-loading').setHTML(response);
                if($('localidad') != null && $('localidad').options.length != 0 ) {
                    $('localidad_container').setStyle('display','block');
                    $('loc_label').setStyle('display','block');
                } else {
                    $('localidad_container').setStyle('display','none');
                    $('loc_label').setStyle('display','none');
                }

            }
        }).request();
    }
<?php endif?>
    //-->
</script>