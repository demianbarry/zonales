<h2><?php echo $this->titleAliasBlockAdmin ?></h2>
<p>
    <?php echo $this->messageAliasBlockAdmin ?>
</p>
<form name="aliasadmin" action="<?php echo JRoute::_('index.php') ?>" method="POST">
    <table border="0" cellspacing="4">
        <thead>
            <tr>
                <th><?php echo $this->titleAlias ?></th>
                <th><?php echo $this->titleUnblock ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->aliaslist as $dbalias):?>
            <tr>
                <td>
                    <img src="<?php echo $dbalias->icon_url?>" alt="<?php echo $dbalias->providername ?>"/>
                </td>
                <td>
                    <input type="checkbox" name="<?php echo 'alias'.$dbalias->id ?>"
                           <?php echo (!$dbalias->block) ? 'checked="checked"' : '' ?> />
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <h2><?php echo $this->titleNewAlias ?></h2>
    <p><?php echo $this->messageNewAlias ?></p>
    <?php echo $this->moduleproviders ?>

    <input type="submit" value="<?php echo JText::_('Accept'); ?>" name="submit" />
    <input type="hidden" name="option" value="com_alias" />
    <input type="hidden" name="task" value="unblock" />
    <?php echo JHTML::_( 'form.token' ); ?>

</form>

