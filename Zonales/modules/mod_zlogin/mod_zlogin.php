<?php
jimport('joomla.application.module.helper');

$db = &JFactory::getDBO();

$selectProviders = 'select p.name, p.icon_url, p.module ' . 
                    'from #__providers p';
$db->setQuery($selectProviders);
$providerslist = $db->loadObjectList();

?>
<script type="text/javascript">
    function showElement(id) {
<?php
foreach ($providerslist as $prov) {
    if ($prov->module != null){
        echo 'document.getElementById(\'' . $prov->module . '\').style.display = \'none\';';
    }
}
?>
                document.getElementById(id).style.display = 'block';
            }
            <!--
            Window.onDomReady(function(){
                document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
            });
            // -->
</script>

<table border="0" cellspacing="1">
    <thead>
        <tr>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($providerslist as $provider): ?>
        <tr>
            <td>
                <div>

            <a href=<?php if ($provider->module != null) {
                    echo '"#' .$provider->module. 'location" onClick="showElement(\''.$provider->module.'\')"';
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
        <?php endforeach ?>
    </tbody>
</table>

