<form name="aliasadmin" action="/index.php" method="POST">
    <table border="0" cellspacing="4">
        <thead>
            <tr>
                <th>Alias</th>
                <th>Habilitar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->aliaslist as $dbalias):?>
            <tr>
                <td>
                    <img src="<?php echo 'images'.DS.$dbalias->icon_url?>" alt="<?php echo $dbalias->providername ?>"/>
                </td>
                <td>
                    <input type="checkbox" name="<?php echo 'alias'.$dbalias->id ?>"
                           <?php echo (!$dbalias->block) ? 'checked="checked"' : '' ?> />
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <input type="submit" value="<?php echo JText::_('Accept'); ?>" name="submit" />
    <input type="hidden" name="option" value="com_alias" />
    <input type="hidden" name="task" value="unblock" />
    <?php echo JHTML::_( 'form.token' ); ?>

</form>

