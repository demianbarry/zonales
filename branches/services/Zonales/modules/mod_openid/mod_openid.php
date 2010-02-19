<?php
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.methods' );

$providerid = JRequest::getInt('providerid', '0', 'method');
$externalid = JRequest::getVar('externalid', '', 'method', 'string');

?>

<form id="form-login" action="<?php echo JRoute::_('index.php') ?>" method="post">
    <fieldset class="input">
        <p id="form-login-username">
            <label id="mod_openid_message" name="mod_login_message" for="modlgn_username">Ingrese su identificador OpenID:</label>
            <br />
            <input id="modlgn_username" class="inputbox" alt="username" name="username" size="18" type="text" />
        </p>
        <input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" />
        <input name="option" type="hidden" value="com_user" />
        <input name="task" type="hidden" value="login" />
        <input name="return" type="hidden" value="<?php echo base64_encode(JRoute::_('index.php')) ?>" />
        <input id="mod_openid_provider" name="provider" type="hidden" value="OpenID" />
        <input type="hidden" name="providerid" value=<?php echo $providerid ?> />
        <input type="hidden" name="externalid" value="<?php echo urlencode($externalid) ?>" />
        <?php echo JHTML::_( 'form.token' ); ?>
    </fieldset>
</form>


