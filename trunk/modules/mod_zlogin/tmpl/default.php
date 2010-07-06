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

<!-- no tocar este display -->
<div id="zlogin" style="display: <?php echo ($userislogged) ? 'none' : 'block' ?>; float: left;" class="moduletable_zlogin">
    <h1><?php echo $module->title; ?></h1>    
    <form id="formzlogin" name="formzlogin" action="<?php echo $routeAction ?>" method="post">

        <div style="margin-left:10px; margin-right:10px; margin-bottom: 10px">

            <p class="connect-message"><?php echo $connectMessage ?></p>

            <!-- aqui va el selector de proveedores -->
            <select class="providers" id="formzlogin_selprovider" name="selprovider" onchange="setElements();">
                <?php foreach ($providerslist as $provider): ?>
                    <?php if (!(!$user->guest && $provider->groupname == 'Guest')): ?>
                <option value="" style="background-image: url(<?php if(isset($provider->icon_url)) echo $provider->icon_url ?>); background-repeat: no-repeat; background-position: right;"
                        class="providers-option" <?php if($provider->name == "Zonales") echo "selected='selected'" ?>>
                                    <?php echo $provider->name ?>
                </option>
                    <?php endif ?>
                <?php endforeach ?>
            </select>

            <!-- aqui va el modulo o el boton de conexion -->
            <?php foreach ($providerslist as $provider): ?>
                <?php if (!(!$user->guest && $provider->groupname == 'Guest')): ?>
                    <?php foreach ($inputData[$provider->name] as $inputElement):
                        $name = $inputElement['name'];
                        $type = $inputElement['type'];
                        $message = $inputElement['message'];
                        $callback = $inputElement['callback'];
                        $value = '';
                        if (!isset ($elementsHTML[$inputElement['name']]) && strlen($inputElement['name']) > 0) : ?>
            <!-- no tocar este display -->
            <div style="display: none; margin-top: 10px;" id="formzlogin_<?php echo $inputElement['name']?>set">
                                <?php

                                $elementsHTML[$name] = 1;
                                $value = sprintf(JText::_($message),$provider->name);
                                $show = 'none';

                                if ($type != 'button') {
                                    //echo '<label id="'. $name .'message" for="'. $name .'" >'. sprintf(JText::_($message),$provider->name) .'</label>';
                                    $value = '';
                                    $show = 'block';
                                }

                                echo '<input type="'. $type .'" name="'. $name . '" id="formzlogin_'. $name .'" onfocus="onFocus(this);" onblur="onBlur(this);" onclick="'. $callback .'" value="'.$value.'"/>';

                                ?>

                                <?php if (strcasecmp($inputElement['name'], 'password') == 0): ?>
                                <div id="formzlogin_useroptions" >
                                    <a id="forgotpass" href="<?php echo $routeReset ?>"><?php echo $forgotPasswordMessage ?></a>
                                    <a id="forgotusername" href="<?php echo $routeRemind ?>"><?php echo $forgotUsernameMessage ?></a>
                                    <?php if ($allowRegistration) : ?>
                                    <a id="registeruser" href="<?php echo $routeRegister; ?>"><?php echo $registerMessage ?></a>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
            </div>
                        <?php endif ?>
                    <?php endforeach ?>

                <?php endif ?>
            <?php endforeach ?>
            <input id="formzlogin_submit" type="submit" name="Submit" class="button" value="<?php echo $providerConnectMessage ?>" />
        </div>        

        <fieldset class="input">
            <input name="option" type="hidden" value="com_user" />
            <input name="task" type="hidden" value="login" />
            <input name="return" type="hidden" value="<?php echo base64_encode(JRoute::_('index.php')) ?>" />
            <input name="providerid" type="hidden" value=<?php echo $providerid ?> />
            <input name="externalid" type="hidden"  value="<?php echo urlencode($externalid) ?>" />
            <input name="provider" type="hidden" value="OpenID" id="formzlogin_provider"/>
            <?php echo JHTML::_( 'form.token' ); ?>
        </fieldset>
    </form>
    <div>
        <?php echo $htmlMessage ?>
    </div>
</div>