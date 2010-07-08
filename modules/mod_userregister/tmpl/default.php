<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$elements = array();
$elementsHTML = array();

JHTML::script('webtoolkit.js',JRoute::_('media/system/js/'),false);
//JHTML::script('cookies.js',JRoute::_('media/system/js/'),false);
?>
<script type="text/javascript" language="javascript">
    function showPass() {
        $('pwmsg').style.display = 'block';
        $('passwordt').style.display = 'block';
        $('pw2msg').style.display = 'block';
        $('password2').style.display = 'block';
        $('passwordt').className = 'inputbox required validate-passverify';
        $('password2').className = 'inputbox required validate-passverify';
    }
    function hidePass() {
        document.getElementById('pwmsg').style.display = 'none';
        document.getElementById('passwordt').style.display = 'none';
        document.getElementById('pw2msg').style.display = 'none';
        document.getElementById('password2').style.display = 'none';
        document.getElementById('passwordt').className = '';
        document.getElementById('password2').className = '';
    }

    function hideAll(){
<?php
foreach ($providerslist as $prov) {
    foreach ($inputData[$prov->name] as $input) {
        if (!(!$user->guest && $prov->groupname == 'Guest') && $prov->type != 'Tradicional') {
            if (!isset ($elements[$input['name']])) {
                $elements[$input['name']] = 1;
                echo '$(\'' . $input['name'] . 'set\').setStyle(\'display\',\'none\');';
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
                var fixpart = $(elements[i] + '-' + provider + 'fixmessage').value;
                $(elements[i] + 'set').setStyle('display','block');
                //                if (showsubmit == 'block'){
                $('ep' + elements[i] + 'message').innerHTML=sprintf(fixpart,provider);
                //                }
                //                else {
                //                    document.getElementById(elements[i]).innerHTML=sprintf(fixpart,provider);
                //                }
            }
        }
    }


    /**
     * Recupera mediante una llamada Ajax la disponibilidad de username indicado.
     */
    function checkUsernameDisponibility(username) {
        if(username != null && username.length > 0 ) {
            $('user_message').empty().addClass('ajax-loading');
            var url='index.php?option=com_zonales&format=raw&task=getUsernameDisponibility&username='+$('usernamer').value;
            new Ajax(url, {
                method: 'get',
                update: 'user_message',
                onComplete: function(response) {
                    $('user_message').removeClass('ajax-loading').setHTML(response);
                    if(response.length > 0) {
                        $('usernamer').value = '';
                        $('usernamer').focus();
                    }
                }
            }).request();
        } else {
            $('user_message').empty();
        }
    }

    window.addEvent('domready', function() {
        $('reg_provincias').addEvent('change', function(value) {
            regLoadMunicipios('');
        });

        regLoadMunicipios(<?php echo $selectedOption?>);
        document.formvalidator.setHandler('passverify', function (value) { return ($('passwordt').value == value); }    );

<?php if($showColapsed): ?>
                $('selectZonalDiv').setStyle('display','none');
                $('zonalmsg').addClass('show');

                // clickeando en el título muestro u oculto el formulario
                $('zonalmsg').addEvent('click', function(e) {
                    if($('selectZonalDiv').getStyle('display') == 'none') {
                        $('selectZonalDiv').setStyle('display','block');
                        $('zonalmsg').removeClass('show').addClass('hide');
                    } else  {
                        $('selectZonalDiv').setStyle('display','none');
                        $('zonalmsg').removeClass('hide').addClass('show');
                    }
                });

                $('selectProviderDiv').setStyle('display','none');
                $('providermsg').addClass('show');

                // clickeando en el título muestro u oculto el formulario
                $('providermsg').addEvent('click', function(e) {
                    if($('selectProviderDiv').getStyle('display') == 'none') {
                        $('selectProviderDiv').setStyle('display','block');
                        $('providermsg').removeClass('show').addClass('hide');
                    } else  {
                        $('selectProviderDiv').setStyle('display','none');
                        $('providermsg').removeClass('hide').addClass('show');
                    }
                });
<?php endif;?>
            });

            function regLoadMunicipios(selected){
                $('reg_z_localidad_container').empty().addClass('ajax-loading');
                var url='index.php?option=com_zonales&format=raw&task=getItemsAjax&id='+$('reg_provincias').value+'&name=zonal&selected='+selected+'&class=reg_item_ajax_select';
                new Ajax(url, {
                    method: 'get',
                    onComplete: function(response) {
                        $('reg_z_localidad_container').removeClass('ajax-loading').setHTML(response);
                        $('reg_municipio_container').setStyle('display','block');
                    }
                }).request();
            }
            // -->
</script>

<div id="registrationform" class="moduletable_userreg">
    <h1><?php echo $module->title; ?></h1>
    <form action="<?php echo JRoute::_( 'index.php?option=com_user' ); ?>"
          method="post"
          id="josForm"
          name="josForm"
          class="form-validate"
          >
        <input type="hidden" id="valorcookie" value=""/>
        <!--
        <?php //if ( $params->def( 'show_page_title', 1 ) ) : ?>
        <div class="componentheading<?php // echo escape($params->get('pageclass_sfx')); ?>">
        <?php // echo escape($params->get('page_title')); ?>
        </div>
        <?php //endif; ?>
        -->

        <table cellpadding="0"
               cellspacing="0"
               border="0"
               width="100%"
               class="contentpane"
               id="userRegTable"
               >
            <!-- SOLICITUD DE NOMBRE COMPLETO -->
            <tr>
                <td width="30%"
                    height="15"
                    colspan="2"
                    >
                    <label id="namemsg"
                           for="name"
                           >
                        <?php echo '*' . $nameMessage ?>:
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           size="15"
                           value="<?php echo  $user->get( 'name' );?>"
                           class="inputbox required"
                           maxlength="50"
                           />
                </td>
            </tr>
            <!-- SOLICITUD DE NOMBRE DE USUARIO -->
            <tr>
                <td height="15" colspan="2">
                    <label id="usernamemsg"
                           for="username"
                           >
                        <?php echo '*' . $usernameMessage ?>:
                    </label>
                    <input type="text"
                           id="usernamer"
                           name="usernamer"
                           size="15"
                           value="<?php echo $user->get( 'username' );?>"
                           class="inputbox required validate-username"
                           maxlength="25"
                           onblur="checkUsernameDisponibility(this.value);"
                           />
                    <div id="user_message"></div>
                </td>
            </tr>
            <!-- SOLICITUD DE CORREO ELECTRONICO -->
            <tr>
                <td height="15" colspan="2">
                    <label id="emailmsg"
                           for="email"
                           >
                        <?php echo '*' . $emailMessage ?>:
                    </label>
                    <input type="text"
                           id="email"
                           name="email"
                           size="15"
                           value="<?php echo $user->get( 'email' );?>"
                           class="inputbox required validate-email"
                           maxlength="100"
                           />
                </td>
            </tr>
            <!-- SOLICITUD DE CORREO ELECTRONICO AUXILIAR-->
            <tr>
                <td height="15" colspan="2">
                    <label id="email2msg"
                           for="email2"
                           >
                        <?php echo $backupEmailMessage ?>:
                    </label>
                    <input type="text"
                           id="email2"
                           name="email2"
                           size="15"
                           value="<?php echo $user->get( 'email2' );?>"
                           class="inputbox validate-email"
                           maxlength="100"
                           />
                </td>
            </tr>

            <!-- SOLICITUD DE FECHA DE NACIMIENTO -->
            <!-- agregado por G2P -->
            <tr>
                <td colspan="2">
                    <label for="birthdate">
                        <?php echo '*' . $birthdateMessage ?>:
                    </label>
                    <?php echo JHTML::calendar(date('Y-m-d'),'birthdate','birthdate','%Y-%m-%d',array('class' => 'date')) ?>
                </td>
            </tr>
            <!-- agregado por G2P -->
            <!-- SOLICITUD DEL SEXO -->
            <tr>
                <td height="15" colspan="2">
                    <label id="sexmsg"
                           for="sex"
                           >
                        <?php echo '*' . $sexMessage ?>:
                    </label>
                    <div id="reg_sex_container">
                        <div style="float: left">
                            <input type="radio"
                                   name="sex"
                                   value="F"
                                   style="width: auto;"/>
                                   <?php echo $femaleSexMessage ?>
                        </div>
                        <div style="float: right">
                            <input type="radio"
                                   name="sex"
                                   value="M"
                                   style="width: auto;"/>
                                   <?php echo $maleSexMessage ?>
                        </div>
                    </div>
                </td>
            </tr>
            <!-- SOLICITUD DEL ZONAL -->
            <tr>
                <td colspan="2">
                    <div style="margin-top: 20px; border-top: 1px dotted gray">
                        <label
                            class="expandible"
                            id="zonalmsg"
                            for="reg_provincias">
                            <?php echo $chooseZonalMessage ?>:
                        </label>
                        <div id="selectZonalDiv">
                            <div id="reg_z_provincias_container">
                                <p><label class="reg_combo_zonal_label">Provincia</label></p>
                                <?php echo $lists['provincias_select']; ?>
                            </div>
                            <div id="reg_municipio_container" style="display: none;">
                                <p><label class="reg_combo_zonal_label">Municipio</label></p>
                                <div id="reg_z_localidad_container"></div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <?php
                $provid = JRequest::getVar('providerid', '', 'get', 'string');
                //$style = ($provid == '') ? 'block' : 'none';
                $show = ($provid == '');
                ?>
                <td height="15" colspan="2">
                    <div style="margin-top: 10px; border-top: 1px dotted gray">
                        <?php if($show): ?>
                        <label
                            class="expandible"
                            id="providermsg"
                            for="provider">
                                <?php echo $chooseProviderMessage ?>:
                        </label>
                        <?php endif; ?>
                        <?php if($show): ?>
                        <div id="selectProviderDiv" style="display: block; margin-top: 10px">
                            <!-- CREA UNA LISTA DE PROVEEDORES CON LOS CUALES ES POSIBLE ACCEDER -->
                            <select class="providers"
                                    id="selprovider"
                                    name="selprovider"
                                    >
                                            <?php foreach ($providerslist as $provider): ?>
                                                <?php if (!(!$user->guest && $provider->groupname == 'Guest')): ?>
                                <option value="<?php echo $provider->name ?>"
                                        style="background-image: url(<?php echo $provider->icon_url ?>); background-repeat: no-repeat; background-position: right;"
                                        class="providers-option"
                                        onclick="function regfunc<?php echo $provider->name ?>(){ <?php
                                                    if ($provider->type == 'Tradicional') {
                                                        echo 'hideAll();';
                                                        echo 'showPass();}';
                                                    }else {
                                                        echo 'var elements = new Array();';
                                                        foreach ($inputData[$provider->name] as $input) {
                                                            if (!(!$user->guest && $provider->groupname == 'Guest')) {
                                                                echo 'elements.push(\'' . $input['name'] . '\'); ';
                                                            }
                                                        }
                                                        echo 'hidePass();';
                                                        echo 'setElement(elements,\'' . $provider->name . '\',\'' . $provider->type . '\');';
                                                        echo '}';
                                                    }
                                                    echo 'regfunc' . $provider->name . '()';


                                                    ?>"
                                        >
                                                        <?php echo $provider->name ?>
                                </option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                            </select>


                            <!-- aqui va el modulo o el boton de conexion -->
                                <?php foreach ($providerslist as $provider): ?>
                                    <?php if (!(!$user->guest && $provider->groupname == 'Guest')): ?>
                            <div>
                                <ul>
                                    <li>
                                                    <?php    foreach ($inputData[$provider->name] as $inputElement):
                                                        $name = $inputElement['name'];
                                                        $type = $inputElement['type'];
                                                        $message = $inputElement['message'];
                                                        $callback = $inputElement['callback'];
                                                        $value = '';
                                                        echo '<input type="hidden" id="'.$name . '-' . $provider->name.'fixmessage" value="'. JText::_($message) .'" />';
                                                        if (!isset ($elementsHTML[$inputElement['name']])) : ?>
                                        <div style="display: none;"
                                             id="<?php echo $inputElement['name'] . 'set' ?>">
                                                                     <?php

                                                                     if ($type == 'password') continue;
                                                                     $elementsHTML[$name] = 1;

                                                                     $value = sprintf(JText::_($message),$provider->name);
                                                                     $show = 'none';

                                                                     if ($type != 'button') {
                                                                         echo '<label id="ep'. $name .'message" for="'. $name .'" >'. sprintf(JText::_($message),$provider->name) .'</label>';
                                                                         echo '';
                                                                         $value = '';
                                                                         $show = 'block';
                                                                     }


                                                                     echo '<input type="hidden" name="'. $name .'submitshow" id="'. $name .'submitshow" value="'. $show .'" />';


                                                                     echo '<input type="'. $type .'" name="'. $name . '" id="'. $name .'" onclick="'. $callback .'" value="'.$value.'" />';
                                                                     echo '';

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
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td height="15" colspan="2">
                    <a name="passlocation"></a>
                    <label style="display: none;"
                           id="pwmsg"
                           for="passwordt"
                           >
                        <?php echo '*' . $passwordMessage ?>:
                    </label>
                    <input style="display: none;"
                           class=""
                           type="password"
                           id="passwordt"
                           name="passwordt"
                           size="15"
                           value=""
                           />
                </td>
            </tr>
            <tr>
                <td height="15" colspan="2">
                    <label style="display: none;"
                           id="pw2msg"
                           for="password2"
                           >
                        <?php echo '*' . $verifyPasswordMessage ?>:
                    </label>
                    <input style="display: none;"
                           class=""
                           type="password"
                           id="password2"
                           name="password2"
                           size="15"
                           value=""
                           />
                </td>
            </tr>
            <tr>
                <td colspan="2"
                    height="15"
                    >

                    <?php echo $registerRequiredMessage ?>
                </td>
            </tr>
        </table>

        <input  class="reg_button validate"
                type="submit"
                value="<?php echo $confirmRegisterMessage ?>">
        <input type="hidden"
               id="fixbutton"
               name="fixbutton"
               value="<?php echo $fixPart ?>"
               />
        <input type="hidden"
               name="task"
               value="register_save"
               />
        <input type="hidden"
               name="id"
               value="0"
               />
        <input type="hidden"
               name="gid"
               value="0"
               />
        <input type="hidden"
               name="providerid"
               value="<?php echo $providerid; ?>"
               />
        <input type="hidden"
               name="externalid"
               value="<?php echo $externalid; ?>"
               />
        <input type="hidden"
               name="force"
               value="<?php echo $force; ?>"
               />
               <?php echo JHTML::_( 'form.token' ); ?>
    </form>
</div>
