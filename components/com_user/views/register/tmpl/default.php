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
foreach ($this->providerslist as $prov) {
    foreach ($this->inputData[$prov->name] as $input) {
        if (!(!$this->user->guest && $prov->groupname == 'Guest') && $prov->type != 'Tradicional') {
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
    <!--


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
        $('provincias').addEvent('change', function(value) {
            loadMunicipios('');
        });

        loadMunicipios(<?php echo $this->selectedOption?>);
        document.formvalidator.setHandler('passverify', function (value) { return ($('passwordt').value == value); }    );
    });

    function loadMunicipios(selected){
        $('z_localidad_container').empty().addClass('ajax-loading');
        var url='index.php?option=com_zonales&format=raw&task=getItemsAjax&id='+$('provincias').value+'&name=zonal&selected='+selected;
            new Ajax(url, {
                method: 'get',
                onComplete: function(response) {
                    $('z_localidad_container').removeClass('ajax-loading').setHTML(response);
                    $('municipio_container').setStyle('display','block');
                }
            }).request();
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
    <input type="hidden" id="valorcookie" value=""/>
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
            <td width="30%"
                height="40"
                >
                <label id="namemsg"
                       for="name"
                       >
                    <?php echo '*' . $this->nameMessage ?>:
                </label>
            </td>
            <td>
                <input type="text"
                       name="name"
                       id="name"
                       size="40"
                       value="<?php echo $this->escape($this->user->get( 'name' ));?>"
                       class="inputbox required"
                       maxlength="50"
                       />
            </td>
        </tr>
        <!-- SOLICITUD DE NOMBRE DE USUARIO -->
        <tr>
            <td height="40">
                <label id="usernamemsg"
                       for="username"
                       >
                    <?php echo '*' . $this->usernameMessage ?>:
                </label>
            </td>
            <td>
                <input type="text"
                       id="usernamer"
                       name="usernamer"
                       size="40"
                       value="<?php echo $this->escape($this->user->get( 'username' ));?>"
                       class="inputbox required validate-username"
                       maxlength="25"
                       onblur="checkUsernameDisponibility(this.value);"
                       />
                <div id="user_message"></div>
            </td>
        </tr>
        <!-- SOLICITUD DE CORREO ELECTRONICO -->
        <tr>
            <td height="40">
                <label id="emailmsg"
                       for="email"
                       >
                    <?php echo '*' . $this->emailMessage ?>:
                </label>
            </td>
            <td>
                <input type="text"
                       id="email"
                       name="email"
                       size="40"
                       value="<?php echo $this->escape($this->user->get( 'email' ));?>"
                       class="inputbox required validate-email"
                       maxlength="100"
                       />
            </td>
        </tr>
        <!-- SOLICITUD DE CORREO ELECTRONICO AUXILIAR-->
        <tr>
            <td height="40">
                <label id="email2msg"
                       for="email2"
                       >
                    <?php echo '*' . $this->backupEmailMessage ?>:
                </label>
            </td>
            <td>
                <input type="text"
                       id="email2"
                       name="email2"
                       size="40"
                       value="<?php echo $this->escape($this->user->get( 'email2' ));?>"
                       class="inputbox validate-email"
                       maxlength="100"
                       />
            </td>
        </tr>

        <!-- SOLICITUD DE FECHA DE NACIMIENTO -->
        <!-- agregado por G2P -->
        <tr>
            <td>
                <label for="birthdate">
                    <?php echo '*' . $this->birthdateMessage ?>:
                </label>
            </td>
            <td>
                <?php echo JHTML::calendar(date('Y-m-d'),'birthdate','birthdate','%Y-%m-%d',array('class' => 'date')) ?>
            </td>
        </tr>
        <!-- agregado por G2P -->
        <!-- SOLICITUD DEL SEXO -->
        <tr>
            <td height="40">
                <label id="sexmsg"
                       for="sex"
                       >
                    <?php echo '*' . $this->sexMessage ?>:
                </label>
            </td>
            <td>
                <ul>
                    <li><input type="radio"
                               name="sex"
                               value="F"
                               /><?php echo $this->femaleSexMessage ?></li>
                    <li><input type="radio"
                               name="sex"
                               value="M"
                               /><?php echo $this->maleSexMessage ?></li>
                </ul>
            </td>
        </tr>

        <!-- SOLICITUD DEL ZONAL -->

        <tr>
            <td>
                <label for="zonal">
                    <?php echo '*' . $this->zonalUserMessage ?>:
                </label>
            </td>
            <td>
                <div>
                    <div id="z_provincias_container">
                        <p><label class="combo_zonal_label">Provincia</label></p>
                        <?php echo $this->lists['provincias_select']; ?>
                    </div>
                    <div id="municipio_container" style="display: none;">
                        <p><label class="combo_zonal_label">Municipio</label></p>
                        <div id="z_localidad_container"></div>
                    <br/>
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
            <td height="40">
                <?php if($show): ?>
                <label
                    style="display: block;"
                    id="providermsg"
                    for="provider">
                        <?php echo $this->chooseProviderMessage ?>:
                </label>
                <?php endif; ?>
            </td>
            <td>
                <?php if($show): ?>
                <div style="display: block;">
                    <!-- CREA UNA LISTA DE PROVEEDORES CON LOS CUALES ES POSIBLE ACCEDER -->




                    <select class="providers"
                            id="selprovider"
                            name="selprovider"
                            >
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
                                                                 echo '<br>';
                                                                 $value = '';
                                                                 $show = 'block';
                                                             }


                                                             echo '<input type="hidden" name="'. $name .'submitshow" id="'. $name .'submitshow" value="'. $show .'" />';


                                                             echo '<input type="'. $type .'" name="'. $name . '" id="'. $name .'" onclick="'. $callback .'" value="'.$value.'" />';
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
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td height="40">
                <a name="passlocation"></a>
                <label style="display: none;"
                       id="pwmsg"
                       for="passwordt"
                       >
                    <?php echo '*' . $this->passwordMessage ?>:
                </label>
            </td>
            <td>
                <input style="display: none;"
                       class=""
                       type="password"
                       id="passwordt"
                       name="passwordt"
                       size="40"
                       value=""
                       />
            </td>
        </tr>
        <tr>
            <td height="40">
                <label style="display: none;"
                       id="pw2msg"
                       for="password2"
                       >
                    <?php echo '*' . $this->verifyPasswordMessage ?>:
                </label>
            </td>
            <td>
                <input style="display: none;"
                       class=""
                       type="password"
                       id="password2"
                       name="password2"
                       size="40"
                       value=""
                       />
            </td>
        </tr>
        <tr>
            <td colspan="2"
                height="40"
                >
                    <?php echo $this->registerRequiredMessage ?>
            </td>
        </tr>
    </table>
    <button class="button validate"
            type="submit">
                <?php echo $this->confirmRegisterMessage ?>
    </button>
    <input type="hidden"
           id="fixbutton"
           name="fixbutton"
           value="<?php echo $this->fixPart ?>"
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
           value="<?php echo $this->providerid; ?>"
           />
    <input type="hidden"
           name="externalid"
           value="<?php echo $this->externalid; ?>"
           />
    <input type="hidden"
           name="force"
           value="<?php echo $this->force; ?>"
           />
           <?php echo JHTML::_( 'form.token' ); ?>
</form>
