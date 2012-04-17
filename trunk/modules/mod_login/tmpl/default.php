<?php
/**
 * @version		$Id: default.php 21322 2011-05-11 01:10:29Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
?>
<div id="zlogin" class="moduletable_zlogin">
    <h1></h1>
    <form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >
        <?php if ($type == 'logout') : ?>
        <?php if ($params->get('greeting')) : ?>
                <div id ="idlogin-greeting" class="login-greeting">
            <?php
                if ($params->get('name') == 0) : {
                        echo JText::sprintf('MOD_LOGIN_HINAME', $user->get('name'));
                    } else : {
                        echo JText::sprintf('MOD_LOGIN_HINAME', $user->get('username'));
                    } endif; ?>
            </div>
        <?php endif; ?>
                <div id="idlogout-button" class="logout-button">
                    <a href="/index.php?option=com_aapu">Editar perfil</a>
                    <input id="submit-logout" type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGOUT'); ?>" />
                    <input type="hidden" name="option" value="com_users" />
                    <input type="hidden" name="task" value="user.logout" />
                    <input type="hidden" name="return" value="<?php echo $return; ?>" />
            <?php echo JHtml::_('form.token'); ?>
            </div>
        <?php else : ?>

        <?php if ($params->get('pretext')): ?>
                        <div class="pretext">
                            <p><?php echo $params->get('pretext'); ?></p>
                        </div>
        <?php endif; ?>

                        <fieldset id="" class="userdata">
            <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                            <div id="form-login-username">
                                <p>
                                    <input id="modlgn-username" type="text" name="username" class="inputbox" value="Usuario Zonales" onfocus="if(this.value=='Usuario Zonales')this.value='';" onblur="if(this.value.length==0)this.value='Usuario Zonales';" />
                                </p>
                                <p>
                                    <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
                                               			¿Olvidaste tu usuario?</a>
                                </p>
                            </div>
                            <div id="form-login-password">
                                <p>
                                    <input id="modlgn-passwd" type="password" name="password" onclick="this.value='';" onfocus="this.select()" onblur="this.value=!this.value?'Password':this.value;" value="Password"/>
                                </p>
                                <p>
                                    <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
                			¿Olvidaste tu contraseña?</a>
                    </p>

                </div>
            <?php $usersConfig = JComponentHelper::getParams('com_users');
                            if ($usersConfig->get('allowUserRegistration')) : ?>
            <?php endif; ?>
            <?php endif; ?>
                            </fieldset>
                            <div id="login-buttons">
                                <p>
                                <input id="formzlogin_submit" type="submit" name="Submit" class="button" value="Ingresar" />
                                <input type="hidden" name="option" value="com_users" />
                                <input type="hidden" name="task" value="user.login" />
                                <input type="hidden" name="return" value="<?php echo $return; ?>" />
            <?php echo JHtml::_('form.token'); ?>
                                </p>
                                <p>
                                <input id="registeruser"  name="Submit" type="button" onclick="window.location.href='<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>'" value="Registrate">
                                </p>
                            </div>


        <?php if ($params->get('posttext')): ?>
                                    <div class="posttext">
                                        <p><?php echo $params->get('posttext'); ?></p>
                                    </div>
        <?php endif; ?>
        <?php endif; ?>
    </form>

</div>
