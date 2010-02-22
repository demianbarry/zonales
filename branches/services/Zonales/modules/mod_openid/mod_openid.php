<?php
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.methods' );

$providerid = JRequest::getInt('providerid', '0', 'method');
$externalid = JRequest::getVar('externalid', '', 'method', 'string');

$message = JText::_('ZONALES_OPENID_ENTER_IDENTIFIER');

?>



        <p id="form-login-username">
            <label id="mod_openid_message" name="mod_login_message" for="modlgn_username"><?php echo $message ?></label>
            <br />
            <input id="modlgn_username" class="inputbox" alt="username" name="username" size="18" type="text" />
        </p>




