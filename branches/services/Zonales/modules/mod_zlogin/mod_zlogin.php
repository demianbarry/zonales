<?php
jimport('joomla.application.module.helper');

$db = &JFactory::getDBO();

$selectProviders = 'select p.name, p.icon_url, p.module, g.name as groupname ' .
                    'from #__providers p, #__groups g ' .
                    'where p.access=g.id';
$db->setQuery($selectProviders);
$providerslist = $db->loadObjectList();

$user =& JFactory::getUser();
$userislogged = (!$user->guest);

$elements = array();
$elementsHTML = array();

?>
<script type="text/javascript">
    function setElement(id,message,provider){
        if (id != 'mod_login' && id != 'connect'){
            document.getElementById(id + '_message').innerHTML=message;
            document.getElementById(id + '_provider').value=provider;
        }
        showElement(id);
    }

    function showElement(id) {
<?php
foreach ($providerslist as $prov) {
    if ($prov->module != null && !(!$user->guest && $prov->groupname == 'Guest')) {
        if (!isset ($elements[$prov->module])){
            $elements[$prov->module] = 1;
            echo 'document.getElementById(\'' . $prov->module . '\').style.display = \'none\';';
        }
    }
}
?>
        document.getElementById(id).style.display = 'block';
    }
</script>

<p class="connect-message">
    <?php echo JText::_('ZONALES_PROVIDER_CONNECT_WITH') ?>
</p>
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
                <select class="providers" id="selprovider" name="selprovider"
                        >
                    <?php foreach ($providerslist as $provider): ?>
                        <?php if (!(!$user->guest && $provider->groupname == 'Guest')): ?>
                    <option value="<?php echo ($provider->module == null) ? 'connect' : $provider->module ?>" onclick="setElement(document.getElementById('selprovider').value,
                            <?php echo '\''.sprintf(JText::_('ZONALES_PROVIDER_ENTER_ID'),$provider->name).'\',\''.$provider->name.'\')"' ?>
                            "
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
                                    <?php if ($provider->module != null):
                                        if (!isset ($elementsHTML[$provider->module])) :
                                            $elementsHTML[$provider->module] = 1;
                                            ?>
                            <a name="<?php echo $provider->name ?>"></a>
                            <div style="display: none;" id="<?php echo $provider->module ?>">
                                                <?php
                                                $module = JModuleHelper::getModule($provider->module);
                                                $html = JModuleHelper::renderModule($module);
                                                echo $html;
                                                ?>
                            </div>
                                        <?php endif ?>
                                    <?php else: ?>
                            <div style="display: none;" id="connect">
                            <input class="login" type="button" value="<?php echo JText::_('ZONALES_PROVIDER_CONNECT') ?>" name="connectbutton" />
                            &nbsp;
                            <a href="<?php
                                        $url = 'index.php?option=com_user&task=login&provider=' .
                                            $provider->name . '&' . JUtility::getToken() .'=1';
                                        echo '"' . $url . '"';
                                           ?>">
                            </a>
                            </div>
                                       <?php endif ?>
                        </li>
                    </ul>
                </div>
                    <?php endif ?>
                <?php endforeach ?>

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
