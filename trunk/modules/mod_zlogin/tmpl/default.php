<!-- no tocar este display -->
<div id="zlogin"
     style="display: <?php echo ($userislogged) ? 'none' : 'block' ?>;">
    <p class="connect-message">
        <?php echo $connectMessage ?>
    </p>

    <form id="formlogin"
          name="formlogin"
          action="<?php echo $routeAction ?>"
          method="post"
    >

        <table border="0">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <!-- aqui va el selector de proveedores -->
                        <select class="providers"
                                id="selprovider"
                                name="selprovider"
                        >
                            <?php foreach ($providerslist as $provider): ?>
                                <?php if (!(!$user->guest && $provider->groupname == 'Guest')): ?>
                            <option value=""
                                    onclick="function func<?php echo $provider->name ?>(){var elements = new Array(); <?php
                                            foreach ($inputData[$provider->name] as $input) {
                                                if (!(!$user->guest && $provider->groupname == 'Guest')) {
                                                    echo 'elements.push(\'' . $input['name'] . '\'); ';
                                                }
                                            }

                                            ?> setElement(elements,'<?php echo $provider->name ?>','<?php echo $provider->type ?>');} func<?php echo $provider->name ?>()"
                                    onkeypress="setIni()"
                                    style="background-image: url(<?php echo $provider->icon_url ?>); background-repeat: no-repeat; background-position: right;"
                                    class="providers-option"
                                    >
                                      <?php echo $provider->name ?>
                            </option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <td>
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
                                    <!-- no tocar este display -->
                                    <div style="display: none;" 
                                         id="<?php echo $inputElement['name'] . 'set' ?>"
                                    >
                                                        <?php

                                                        $elementsHTML[$name] = 1;
                                                        $value = sprintf(JText::_($message),$provider->name);
                                                        $show = 'none';

                                                        if ($type != 'button') {
                                                            echo '<label id="'. $name .'message" for="'. $name .'" >'. sprintf(JText::_($message),$provider->name) .'</label>';
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
                        <input id="submit"
                               type="submit"
                               name="Submit"
                               class="button"
                               value="<?php echo $providerConnectMessage ?>"
                               onmouseover="setIni()"
                               onfocus="setIni()"
                               />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" 
                        align="center"
                    >
                        <ul>
                            <li>
                                <a href="<?php echo $routeReset ?>">
                                <?php echo $forgotPasswordMessage ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $routeRemind ?>">
                                <?php echo $forgotUsernameMessage ?></a>
                            </li>
                            <?php
                            if ($allowRegistration) : ?>
                            <li>
                                <a href="<?php echo $routeRegister; ?>">
                                    <?php echo $registerMessage ?></a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>

        <input type="hidden" id="fixbutton" name="fixbutton" value="<?php echo JText::_('ZONALES_PROVIDER_CONNECT') ?>" />

        <fieldset class="input">
            <input name="option" type="hidden" value="com_user" />
            <input name="task" type="hidden" value="login" />
            <input name="return" type="hidden" value="<?php echo base64_encode(JRoute::_('index.php')) ?>" />
            <input type="hidden" name="providerid" value=<?php echo $providerid ?> />
            <input type="hidden" name="externalid" value="<?php echo urlencode($externalid) ?>" />
            <input id="provider" name="provider" type="hidden" value="OpenID" />
            <?php echo JHTML::_( 'form.token' ); ?>
        </fieldset>
    </form>
</div>

<!-- <input type="button" onclick="FB.Connect.requireSession(); return false;" value="IR"></input> -->
<!-- <a href="#" onClick="FB.Connect.logout(function() { refresh_page(); })">Logout of Facebook</a> -->

