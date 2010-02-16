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

?>
<script type="text/javascript">
    function setElement(id,message,provider){
        if (id != 'mod_login'){
            document.getElementById(id + '_message').innerHTML=message;
            document.getElementById(id + '_provider').value=provider;
        }
        showElement(id);
    }

    function showElement(id) {
<?php
foreach ($providerslist as $prov) {
    if ($prov->module != null && !(!$user->guest && $prov->groupname == 'Guest')) {
        echo 'document.getElementById(\'' . $prov->module . '\').style.display = \'none\';';
    }
}
?>
        document.getElementById(id).style.display = 'block';
    }
</script>

<table border="0" cellspacing="1">
    <thead>
        <tr>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($providerslist as $provider): ?>
            <?php if (!(!$user->guest && $provider->groupname == 'Guest')):
                ?>
        <tr>
            <td>
                <div>

                    <a href=<?php if ($provider->module != null) {
                                echo '"#' .$provider->module. 'location" onClick="setElement(\''.$provider->module.'\',\'Ingrese su identificacion de '.$provider->name.'\',\''.$provider->name.'\')"';
                            }
                            else {
                                $url = 'index.php?option=com_user&task=login&provider=' .
                                    $provider->name . '&' . JUtility::getToken() .'=1';
                                echo '"' . $url . '"';
                            }
                               ?>>
                        <img src="<?php echo $provider->icon_url ?>"
                             alt="<?php echo $provider->name ?>"
                             title="Ingrese a Zonales mediante <?php echo $provider->name ?>"
                             />
                    </a>
                    <a name="<?php echo $provider->name ?>"></a>
                    <div style="display: none;" id="<?php echo $provider->module ?>">
                                <?php if ($provider->module != null) {
                                    $module = JModuleHelper::getModule($provider->module);
                                    $html = JModuleHelper::renderModule($module);
                                    echo $html;
                                }
                                ?>
                    </div>

                </div>
            </td>
        </tr>
            <?php endif ?>
        <?php endforeach ?>
    </tbody>
</table>

