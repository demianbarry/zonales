<?php
$user =& JFactory::getUser();

if (!$user->guest): ?>
<p class="greeting">
    <?php echo sprintf(JText::_('ZONALES_SESSION_GREETING'),$user->get('name')); ?>
</p>
<input type="button" 
       value="<?php echo JText::_('ZONALES_SESSION_CLOSE') ?>"
       name="closesession"
       class="closesession"
       onclick="window.location.href='<?php echo JRoute::_('index.php?option=com_user&task=logout') ?>'"
       />
<?php endif ?>
