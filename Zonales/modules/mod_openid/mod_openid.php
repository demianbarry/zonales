<?php
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.methods' );

?>

<form id="form-login" action="<?php echo JRoute::_('index.php') ?>" method="post">
    <fieldset class="input">
        <p id="form-login-username">
            <label id="mod_openid_message" name="mod_login_message" for="modlgn_username">Ingrese su identificador OpenID:</label>
            <br />
            <input id="modlgn_username" class="inputbox" alt="username" name="username" size="18" type="text" />
        </p>
        <input name="option" type="hidden" value="com_user" />
        <input name="task" type="hidden" value="login" />
        <input name="return" type="hidden" value="<?php echo base64_encode(JRoute::_('index.php')) ?>" />
        <input id="mod_openid_provider" name="provider" type="hidden" value="OpenID" />
        <?php echo JHTML::_( 'form.token' ); ?>
    </fieldset>
</form>


