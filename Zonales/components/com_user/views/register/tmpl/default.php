<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<script type="text/javascript">
    function showPass() {
		document.getElementById('pwmsg').style.display = 'block';
                document.getElementById('password').style.display = 'block';
                document.getElementById('pw2msg').style.display = 'block';
                document.getElementById('password2').style.display = 'block';
                document.getElementById('password').className = 'inputbox required validate-passverify';
                document.getElementById('password2').className = 'inputbox required validate-passverify';
            }
    <!--
    Window.onDomReady(function(){
        document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
    });
    // -->
</script>

<?php
if(isset($this->message)) {
    $this->display('message');
}
?>

<form action="<?php echo JRoute::_( 'index.php?option=com_user' ); ?>" method="post" id="josForm" name="josForm" class="form-validate">

    <?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
    <div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
    <?php endif; ?>

    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
        <!-- SOLICITUD DE NOMBRE COMPLETO -->
        <tr>
            <td width="30%" height="40">
                <label id="namemsg" for="name">
                    <?php echo '*' . JText::_( 'Name' ); ?>:
                </label>
            </td>
            <td>
                <input type="text" name="name" id="name" size="40" value="<?php echo $this->escape($this->user->get( 'name' ));?>" class="inputbox required" maxlength="50" /> 
            </td>
        </tr>
        <!-- SOLICITUD DE NOMBRE DE USUARIO -->
        <tr>
            <td height="40">
                <label id="usernamemsg" for="username">
                    <?php echo '*' . JText::_( 'User name' ); ?>:
                </label>
            </td>
            <td>
                <input type="text" id="username" name="username" size="40" value="<?php echo $this->escape($this->user->get( 'username' ));?>" class="inputbox required validate-username" maxlength="25" /> 
            </td>
        </tr>
        <!-- SOLICITUD DE CORREO ELECTRONICO -->
        <tr>
            <td height="40">
                <label id="emailmsg" for="email">
                    <?php echo '*' . JText::_( 'Email' ); ?>:
                </label>
            </td>
            <td>
                <input type="text" id="email" name="email" size="40" value="<?php echo $this->escape($this->user->get( 'email' ));?>" class="inputbox required validate-email" maxlength="100" /> 
            </td>
        </tr>
        <!-- SOLICITUD DE CORREO ELECTRONICO AUXILIAR-->
        <tr>
            <td height="40">
                <label id="email2msg" for="email2">
                    <?php echo '*' . JText::_( 'Backup Email' ); ?>:
                </label>
            </td>
            <td>
                <input type="text" id="email2" name="email2" size="40" value="<?php echo $this->escape($this->user->get( 'email2' ));?>" class="inputbox required validate-email" maxlength="100" />
            </td>
        </tr>

        <!-- SOLICITUD DE FECHA DE NACIMIENTO -->
        <!-- agregado por G2P -->
        <tr>
            <td>
                <label for="birthdate">
                    <?php echo '*' . JText::_( 'Birthdate' ); ?>:
                </label>
            </td>
            <td>
                <input class="inputbox required validate-birthdate" type="text" id="birthdate" name="birthdate" value="" size="40" /> 
            </td>
        </tr>
        <!-- agregado por G2P -->
        <!-- SOLICITUD DEL SEXO -->
        <tr>
            <td height="40">
                <label id="sexmsg" for="sex">
                    <?php echo JText::_( 'Sex' ); ?>:
                </label>
            </td>
            <td>
                <ul>
                    <li><input type="radio" name="sex" value="F" /><?php echo JText::_( 'Female' ); ?></li>
                    <li><input type="radio" name="sex" value="M" /><?php echo JText::_( 'Male' ); ?></li>
                </ul>
            </td>
        </tr>
        <tr>
            <?php
                    $provid = JRequest::getVar('providerid', '', 'get', 'string');
                    $style = ($provid == '') ? 'block' : 'none';
                    ?>
            <td height="40">
                <label style="display: <?php echo $style ?>;" id="providermsg" for="provider">
                    <?php echo JText::_( 'Choose how to autenticate' ); ?>:
                </label>
            </td>
            <td>
                <div style="display: <?php echo $style ?>;">
                    <!-- CREA UNA LISTA DE PROVEEDORES CON LOS CUALES ES POSIBLE ACCEDER -->
                    <?php foreach ($this->providerslist as $provider):
                    if ($provider->protocolname == 'Tradicional'): ?>
                    <a href="#passlocation" onClick="showPass()"/>
                    <img src="<?php echo $provider->icon_url ?>"
                         alt="<?php echo $provider->providername ?>"
                         title="Ingrese a Zonales mediante <?php echo $provider->providername ?>"
                    />
                    <?php endif ?>

<!--                    <a href=<?php if ($provider->protocolname == 'Tradicional'){
                        echo '"#passlocation" onClick="showPass()"';
                    }
                    else {
                        $url = 'index.php?option=com_user&task=login&provider=' .
                            $provider->providername . '&' . JUtility::getToken() .'=1';
                        echo '"' . $url . '"';
                    }
                    ?>>
                        <img src="<?php echo $provider->icon_url ?>"
                         alt="<?php echo $provider->providername ?>"
                         title="Ingrese a Zonales mediante <?php echo $provider->providername ?>"
                         />
                    </a>
-->
                    <?php endforeach ?>
                </div>
            </td>
        </tr>
        <tr>
            <td height="40">
                <a name="passlocation"></a>
                <label style="display: none;" id="pwmsg" for="password">
                    <?php echo '*' . JText::_( 'Password' ); ?>:
                </label>
            </td>
            <td>
                <input style="display: none;" class="" type="password" id="password" name="password" size="40" value="" /> 
            </td>
        </tr>
        <tr>
            <td height="40">
                <label style="display: none;" id="pw2msg" for="password2">
                    <?php echo '*' . JText::_( 'Verify Password' ); ?>:
                </label>
            </td>
            <td>
                <input style="display: none;" class="" type="password" id="password2" name="password2" size="40" value="" /> 
            </td>
        </tr>
        <tr>
            <td colspan="2" height="40">
                <?php echo JText::_( 'REGISTER_REQUIRED' ); ?>
            </td>
        </tr>
    </table>
    <button class="button validate" type="submit"><?php echo JText::_('Register'); ?></button>
    <input type="hidden" name="task" value="register_save" />
    <input type="hidden" name="id" value="0" />
    <input type="hidden" name="gid" value="0" />
    <input type="hidden" name="providerid" value="<?php echo $this->providerid; ?>" />
    <input type="hidden" name="externalid" value="<?php echo $this->externalid; ?>" />
    <input type="hidden" name="force" value="<?php echo $this->force; ?>" />
    <?php echo JHTML::_( 'form.token' ); ?>
</form>
