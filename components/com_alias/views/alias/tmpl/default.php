<?php # muestra el modulo de login de zonales para permitir registrar nuevos alias ?>
<script type="text/javascript">
    window.onload = function (){
        document.getElementById('zlogin').style.display = 'block';
    }
</script>

<?php # verifica si hay que mostrar la seccion de habilitacion/deshabilitacion de alias ?>
<?php if ($this->showAliasBlock): ?>
<h2 class="aliasblocktitle">
    <?php echo $this->titleAliasBlockAdmin ?>
</h2>

<p class="aliasblockmessage">
    <?php echo $this->messageAliasBlockAdmin ?>
</p>

<form name="aliasadmin"
      action="<?php echo JRoute::_('index.php') ?>"
      method="POST"
>
    <table border="0" 
           cellspacing="4"
    >
        <thead>
            <tr>
                <th></th>
                <th>
                    <?php echo $this->titleAlias ?>
                </th>
                <th>
                    <?php echo $this->titleUnblock ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->aliaslist as $dbalias):?>
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
                    <label class="aliaslabel">
                        <?php echo $dbalias->label ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox"
                           name="<?php echo 'alias'.$dbalias->id ?>"
                           <?php echo (!$dbalias->block) ? 'checked="checked"' : '' ?>
                    />
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <input type="submit"
           value="<?php echo JText::_('ZONALES_ALIAS_ACCEPT'); ?>"
           name="submit"
    />
    <input type="hidden"
           name="option"
           value="com_alias"
    />
    <input type="hidden"
           name="task"
           value="unblock"
    />
    <?php echo JHTML::_( 'form.token' ); ?>

</form>

<?php else: ?>

<p class="noalias">
    <?php echo $this->noAliasMessage ?>
</p>

<?php endif ?>

<h2 class="aliastitle">
    <?php echo $this->titleNewAlias ?>
</h2>

<p class="newaliasmessage">
    <?php echo $this->messageNewAlias ?>
</p>
<?php # muestra el modulo de login de zonales para permitir el registro de nuevos alias ?>
    <?php echo $this->moduleproviders ?>
