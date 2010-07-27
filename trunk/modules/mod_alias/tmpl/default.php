<?php # muestra el modulo de login de zonales para permitir registrar nuevos alias ?>
<script type="text/javascript">
    window.onload = function (){
        document.getElementById('zlogin').style.display = 'block';
        document.getElementById('submit').value = '<?php echo $submitText ?>';
    }

    function changeStatus() {
        var aliases = document.getElementsByClassName('alias');
        var alias;
        var params = '';
        var i;
        for (i=0;i<aliases.length;i++) {
            alias = aliases[i];
            params = params + '&' + alias.name + '=' + alias.checked;
        }
            $('alias_progress').empty().addClass('ajax-loading');
            var url='index.php?option=com_alias&format=raw&task=unblock&' + '<?php echo JUtility::getToken()?>' + '=1'+params;
            new Ajax(url, {
                method: 'get',
                update: 'alias_progress',
                onComplete: function(response) {
                    $('alias_progress').removeClass('ajax-loading');
                }
            }).request();
    }
</script>

<?php # verifica si hay que mostrar la seccion de habilitacion/deshabilitacion de alias ?>
<?php if ($showAliasBlock): ?>
<h2 class="aliasblocktitle">
    <?php echo $titleAliasBlockAdmin ?>
</h2>

<p class="aliasblockmessage">
    <?php echo $messageAliasBlockAdmin ?>
</p>

<form name="aliasadmin" action="index.php" method="post" id="aliasadmin" >
    <table border="0" 
           cellspacing="4"
    >
        <thead>
            <tr>
                <th></th>
                <th>
                    <?php echo $titleAlias ?>
                </th>
                <th>
                    <?php echo $titleUnblock ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dbaliaslist as $dbalias):?>
            <tr>
                <td>
                    <img src="<?php echo $dbalias->icon_url?>" 
                         alt="<?php echo $dbalias->providername ?>"
                    />
                </td>
                <td>
                    <label class="providername">
                        <?php echo $dbalias->providername ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox"
                           name="<?php echo 'alias'.$dbalias->id ?>"
                           class="alias"
                           <?php echo (!$dbalias->block) ? 'checked="checked"' : '' ?>
                    />
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <input type="button" value="<?php echo JText::_('ZONALES_ALIAS_ACCEPT'); ?>" name="submitChanges" onclick="changeStatus();" />
    <input type="hidden" name="option" value="com_alias" />
    <input type="hidden" name="task" value="unblock" />
    <?php echo JHTML::_( 'form.token' ); ?>

</form>
<div id="alias_progress"></div>
<?php else: ?>

<p class="noalias">
    <?php echo $noAliasMessage ?>
</p>

<?php endif ?>

<h2 class="aliastitle">
    <?php echo $titleNewAlias ?>
</h2>

<p class="newaliasmessage">
    <?php echo $messageNewAlias ?>
</p>
<?php # muestra el modulo de login de zonales para permitir el registro de nuevos alias ?>
    <?php echo $htmlProviders ?>
