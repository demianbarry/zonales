<!-- no tocar este display -->
<div id="zlogin" style="display: <?php echo ($userislogged) ? 'none' : 'block' ?>;">
    <p class="connect-message">
        <?php echo JText::_('ZONALES_PROVIDER_CONNECT_WITH') ?>
    </p>

    <form id="formlogin" name="formlogin" action="<?php echo JRoute::_('index.php') ?>" method="post">

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
                        <select class="providers" id="selprovider" name="selprovider" >
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
                                                echo '<input type="hidden" id="'.$name . '-' . $provider->name.'fixmessage" value="'. JText::_($message) .'" />';
                                                if (!isset ($elementsHTML[$inputElement['name']])) : ?>
                                    <!-- no tocar este display -->
                                    <div style="display: none;" id="<?php echo $inputElement['name'] . 'set' ?>">
                                                        <?php

                                                        $elementsHTML[$name] = 1;

                                                        echo '<label id="'. $name .'message" for="'. $name .'" >'. sprintf(JText::_($message),$provider->name) .'</label>';
                                                        echo '<br>';
                                                        echo '<input type="'. $type .'" name="'. $name . '" id="'. $name .'" />';
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
                               value="<?php echo JText::_('ZONALES_PROVIDER_CONNECT') ?>"
                               onmouseover="setIni()"
                               />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <ul>
                            <li>
                                <a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>">
                                <?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>">
                                <?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
                            </li>
                            <?php
                            $usersConfig = &JComponentHelper::getParams( 'com_users' );
                            if ($usersConfig->get('allowUserRegistration')) : ?>
                            <li>
                                <a href="<?php echo JRoute::_( 'index.php?option=com_user&view=register' ); ?>">
                                    <?php echo JText::_('REGISTER'); ?></a>
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

