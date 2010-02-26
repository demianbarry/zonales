<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 

$elements = array();
$elementsHTML = array();

JHTML::script('webtoolkit.js',JRoute::_('media/system/js/'),false);
?>
<script type="text/javascript">
    function showPass() {
		document.getElementById('pwmsg').style.display = 'block';
                document.getElementById('password').style.display = 'block';
                document.getElementById('pw2msg').style.display = 'block';
                document.getElementById('password2').style.display = 'block';
                document.getElementById('password').className = 'inputbox required validate-passverify';
                document.getElementById('password2').className = 'inputbox required validate-passverify';
            }
    function hidePass() {
		document.getElementById('pwmsg').style.display = 'none';
                document.getElementById('password').style.display = 'none';
                document.getElementById('pw2msg').style.display = 'none';
                document.getElementById('password2').style.display = 'none';
                document.getElementById('password').className = '';
                document.getElementById('password2').className = '';
            }

        function hideAll(){
            <?php
foreach ($this->providerslist as $prov) {
    foreach ($this->inputData[$prov->name] as $input) {
        if (!(!$this->user->guest && $prov->groupname == 'Guest') && $prov->type != 'Tradicional') {
            if (!isset ($elements[$input['name']])) {
                $elements[$input['name']] = 1;
                echo 'document.getElementById(\'' . $input['name'] . 'set\').style.display = \'none\';';
            }
        }
    }
}

?>
        }
        function setElement(elements,provider,type) {
        hideAll();
        for(i=0 ; i < elements.length ; i++){
                if (elements[i] != ''){
                    var fixpart = document.getElementById(elements[i] + '-' + provider + 'fixmessage').value;
                    document.getElementById(elements[i] + 'set').style.display = 'block';
                    document.getElementById('ep' + elements[i] + 'message').innerHTML=sprintf(fixpart,provider);
                }
        }
    }
    <!--
    Window.onDomReady = function(){
        document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
    }
    // -->
</script>

<?php
if(isset($this->message)) {
    $this->display('message');
}
?>

<form action="<?php echo JRoute::_( 'index.php?option=com_user' ); ?>"
      method="post"
      id="josForm"
      name="josForm"
      class="form-validate"
>

    <?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
    <div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
    <?php echo $this->escape($this->params->get('page_title')); ?>
    </div>
    <?php endif; ?>

    <table cellpadding="0"
           cellspacing="0"
           border="0"
           width="100%"
           class="contentpane"
    >
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
                <input type="text" id="email2" name="email2" size="40" value="<?php echo $this->escape($this->user->get( 'email2' ));?>" class="inputbox validate-email" maxlength="100" />
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
                <?php echo JHTML::calendar(date('Y-m-d'),'birthdate','birthdate','%Y-%m-%d',array('class' => 'date')) ?>
               <!-- <input class="inputbox required validate-birthdate" type="text" id="birthdate" name="birthdate" value="" size="40" /> -->
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




<select class="providers" id="selprovider" name="selprovider" >
                    <?php foreach ($this->providerslist as $provider): ?>
                        <?php if (!(!$this->user->guest && $provider->groupname == 'Guest')): ?>
                    <option value="<?php echo $provider->name ?>"
                            style="background-image: url(<?php echo $provider->icon_url ?>); background-repeat: no-repeat; background-position: right;"
                            class="providers-option"
                            onclick="function func<?php echo $provider->name ?>(){ <?php
                            if ($provider->type == 'Tradicional') {
                                echo 'hideAll();';
                                echo 'showPass();}';
                            }else {
                                echo 'var elements = new Array();';
                                                                            foreach ($this->inputData[$provider->name] as $input) {
                                                                                if (!(!$this->user->guest && $provider->groupname == 'Guest')) {
                                                                                    echo 'elements.push(\'' . $input['name'] . '\'); ';
                                                                                }
                                                                            }
                                                                            echo 'hidePass();';
                                                                            echo 'setElement(elements,\'' . $provider->name . '\',\'' . $provider->type . '\');';
                                                                            echo '}';
                            }
                            echo 'func' . $provider->name . '()';


                    ?>"
                            >
                                                                                        <?php echo $provider->name ?>
                    </option>
                        <?php endif ?>
                    <?php endforeach ?>
                </select>


                                    <!-- aqui va el modulo o el boton de conexion -->
                <?php foreach ($this->providerslist as $provider): ?>
                    <?php if (!(!$this->user->guest && $provider->groupname == 'Guest')): ?>
                <div>
                    <ul>
                        <li>
                                    <?php    foreach ($this->inputData[$provider->name] as $inputElement):
                                    $name = $inputElement['name'];
                                                $type = $inputElement['type'];
                                                $message = $inputElement['message'];
                                                echo '<input type="hidden" id="'.$name . '-' . $provider->name.'fixmessage" value="'. JText::_($message) .'" />';
                                    if (!isset ($elementsHTML[$inputElement['name']])) : ?>
                            <div style="display: none;" id="<?php echo $inputElement['name'] . 'set' ?>">
                                                <?php

                                                    $elementsHTML[$name] = 1;

                                                    echo '<label id="ep'. $name .'message" for="ep'. $name .'" >'. sprintf(JText::_($message),$provider->name) .'</label>';
                                                    echo '<br>';
                                                    echo '<input type="'. $type .'" name="ep'. $name . '" id="ep'. $name .'" />';
                                                    echo '<br>';

                                                ?>
                            </div>
                                        <?php endif ?>
                                       <?php endforeach ?>
                        </li>
                    </ul>
                </div>
                    <?php endif ?>
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
    <input type="hidden" id="fixbutton" name="fixbutton" value="<?php echo JText::_('ZONALES_PROVIDER_CONNECT') ?>" />
    <input type="hidden" name="task" value="register_save" />
    <input type="hidden" name="id" value="0" />
    <input type="hidden" name="gid" value="0" />
    <input type="hidden" name="providerid" value="<?php echo $this->providerid; ?>" />
    <input type="hidden" name="externalid" value="<?php echo $this->externalid; ?>" />
    <input type="hidden" name="force" value="<?php echo $this->force; ?>" />
    <?php echo JHTML::_( 'form.token' ); ?>
</form>
